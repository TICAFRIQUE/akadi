@extends('admin.layouts.app')
@section('title', 'Nouvelle vente / commande')
@section('sub-title', 'Point de vente – Créer une commande')

@section('css')
    <link rel="stylesheet" href="{{ asset('admin/assets/bundles/select2/dist/css/select2.min.css') }}">
    <style>
        /* ── Layout POS ── */
        .pos-section-title {
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #6c757d;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 6px;
            margin-bottom: 14px;
        }

        /* Tableau produits */
        #tbl-items {
            min-width: 700px;
        }

        #tbl-items thead th {
            background: #f8f9fa;
            white-space: nowrap;
            font-size: .82rem;
            padding: 10px 12px;
        }

        #tbl-items tbody td {
            vertical-align: middle;
            padding: 8px 10px;
        }

        #tbl-items .input-group-sm .form-control {
            min-width: 52px;
        }

        #tbl-items .input-group-sm .btn {
            min-width: 30px;
        }

        .btn-remove-row {
            width: 32px;
            height: 32px;
            padding: 0;
        }

        /* Totaux */
        .recap-line {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .recap-line.total-final {
            font-size: 1.3rem;
            font-weight: 700;
            border-top: 2px solid #dee2e6;
            padding-top: 10px;
            margin-top: 5px;
        }

        .recap-line.solde {
            color: #dc3545;
            font-weight: 600;
        }

        /* Source badges */
        .source-btn {
            cursor: pointer;
        }

        .source-btn.active {
            box-shadow: 0 0 0 2px #4e73df;
        }

        /* Caisse badge */
        .caisse-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f0f4ff;
            border-radius: 8px;
            padding: 4px 12px;
            font-size: .85rem;
            font-weight: 600;
            color: #4e73df;
        }

        /* Client card */
        .client-found {
            border: 2px solid #28a745;
            border-radius: 10px;
            padding: 12px 16px;
            background: #f6fff8;
        }
    </style>
@endsection

@section('content')
    <div class="section-body">

        {{-- Erreurs --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('pos.store') }}" method="POST" id="pos-form">
            @csrf

            {{-- ══ ROW 1 : Recherche + Tableau panier (full width) ══ --}}
            <div class="row">
                <div class="col-md-12">

                    {{-- Caisse active --}}
                    @if ($caisse)
                        <div class="mb-3">
                            <span class="caisse-badge">
                                <i class="fas fa-cash-register"></i>
                                Caisse : {{ $caisse->nom }}
                            </span>
                        </div>
                    @endif

                    {{-- Sélecteur de produit --}}
                    <div class="card mb-3" style="overflow:visible">
                        <div class="card-body pb-2" style="overflow:visible">
                            <p class="pos-section-title"><i class="fas fa-search mr-1"></i>Ajouter un produit</p>
                            <select id="product-select" class="form-control select2" style="width:100%">
                                <option value="">Rechercher un produit…</option>
                                @foreach ($products as $p)
                                    <option value="{{ $p->id }}" data-price="{{ $p->price }}"
                                        data-stock="{{ $p->stock }}"
                                        data-img="{{ $p->getFirstMediaUrl('principal_img') }}"
                                        data-title="{{ $p->title }}">{{ $p->title }} —
                                        {{ number_format($p->price, 0, ',', ' ') }} FCFA</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Tableau des produits --}}
                    <div class="card mb-3">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="tbl-items">
                                    <thead>
                                        <tr>
                                            <th>Prod</th>
                                            <th class="text-center">Stock</th>
                                            <th>P.U</th>
                                            <th>Qté</th>
                                            <th>Remise (%)</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-body">
                                        <tr id="empty-row">
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                                Aucun produit ajouté — utilisez la recherche ci-dessus.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>{{-- /row 1 --}}


            <div class="row">
                <div class="col-md-6">
                    {{-- Client --}}
                    <div class="col-12 col-md-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <p class="pos-section-title"><i class="fas fa-user mr-1"></i>Client</p>
                                <div class="form-group">
                                    <label class="small">Rechercher un client existant</label>
                                    <input type="text" id="client-search" class="form-control"
                                        placeholder="Nom ou téléphone…" autocomplete="off">
                                    <div id="client-results" class="list-group position-absolute"
                                        style="z-index:999;width:90%;display:none;"></div>
                                </div>
                                {{-- Client trouvé --}}
                                <div id="client-found-box" class="d-none mb-3">
                                    <div class="client-found">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <i class="fas fa-user-check text-success mr-1"></i>
                                                <strong id="cf-name"></strong><br>
                                                <small id="cf-phone" class="text-muted"></small><br>
                                                <small id="cf-email" class="text-muted"></small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                id="btn-clear-client">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="user_id" id="user_id">
                                </div>
                                {{-- Nouveau client --}}
                                <div id="new-client-box">
                                    <div class="form-group">
                                        <label class="small">Nom du client</label>
                                        <input type="text" name="client_name" class="form-control"
                                            placeholder="Nom complet" value="{{ old('client_name') }}">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="small">Téléphone</label>
                                        <input type="text" name="client_phone" class="form-control"
                                            placeholder="+225 07 …" value="{{ old('client_phone') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Options avancées --}}
                    <div class="col-12 col-md-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <p class="pos-section-title"><i class="fas fa-cog mr-1"></i>Options</p>
                                <div class="form-group">
                                    <label class="small">Type de commande</label>
                                    <select name="type_order" class="form-control">
                                        <option value="normal" {{ old('type_order') == 'normal' ? 'selected' : '' }}>
                                            Normal
                                        </option>
                                        <option value="precommande"
                                            {{ old('type_order') == 'precommande' ? 'selected' : '' }}>Précommande
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="small">Date de livraison prévue</label>
                                    <input type="date" name="delivery_planned" class="form-control"
                                        value="{{ old('delivery_planned') }}">
                                </div>
                                <div class="form-group mb-0">
                                    <label class="small">Note interne</label>
                                    <textarea name="note" class="form-control" rows="2" placeholder="Instructions, remarques…">{{ old('note') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Provenance --}}
                    <div class="col-12 col-md-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <p class="pos-section-title"><i class="fas fa-share-alt mr-1"></i>Provenance</p>
                                <div class="row">
                                    @foreach ($sources as $key => $src)
                                        <div class="col-6 mb-2">
                                            <div class="btn btn-outline-secondary btn-block source-btn {{ old('source', 'backoffice') == $key ? 'active' : '' }}"
                                                data-value="{{ $key }}" onclick="selectSource(this)">
                                                <i class="fab {{ $src['icon'] }} mr-1"></i> {{ $src['label'] }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="source" id="source-input"
                                    value="{{ old('source', 'backoffice') }}">
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    {{-- Récapitulatif --}}
                    <div class="col-12 col-md-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <p class="pos-section-title"><i class="fas fa-calculator mr-1"></i>Récapitulatif</p>
                                <div class="recap-line">
                                    <span>Sous-total</span>
                                    <strong id="display-subtotal">0 FCFA</strong>
                                </div>
                                <div class="recap-line align-items-center">
                                    <span>Remise globale (FCFA)</span>
                                    <input type="number" name="discount" id="discount"
                                        class="form-control form-control-sm text-right" style="width:140px"
                                        value="{{ old('discount', 0) }}" min="0" step="any">
                                </div>
                                <div class="recap-line align-items-center">
                                    <span>Frais de livraison</span>
                                    <input type="number" name="delivery_price" id="delivery_price"
                                        class="form-control form-control-sm text-right" style="width:140px"
                                        value="{{ old('delivery_price', 0) }}" min="0" step="any">
                                </div>
                                <div class="recap-line total-final">
                                    <span>TOTAL</span>
                                    <span id="display-total" class="text-dark">0 FCFA</span>
                                </div>
                                <div class="recap-line align-items-center mt-2">
                                    <span class="font-weight-bold">Acompte reçu <span class="text-danger">*</span></span>
                                    <input type="number" name="acompte" id="acompte"
                                        class="form-control form-control-sm text-right" style="width:140px"
                                        value="{{ old('acompte', 0) }}" min="0" step="any" required>
                                </div>
                                <div class="recap-line solde">
                                    <span>Solde restant</span>
                                    <strong id="display-solde">0 FCFA</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Livraison --}}
                    <div class="col-12 col-md-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <p class="pos-section-title"><i class="fas fa-map-marker-alt mr-1"></i>Livraison</p>
                                <div class="form-group">
                                    <label class="small">Mode de livraison <span class="text-danger">*</span></label>
                                    <select name="mode_livraison" class="form-control" required>
                                        <option value="">— Sélectionner —</option>
                                        {{-- <option value="sur_place" {{ old('mode_livraison') == 'sur_place' ? 'selected' : '' }}>Sur place</option> --}}
                                        <option value="Je passe récupérer"
                                            {{ old('mode_livraison') == 'Je passe récupérer' ? 'selected' : '' }}>Récupérer
                                            sur
                                            place</option>
                                        <option value="Livraison Yango Moto"
                                            {{ old('mode_livraison') == 'Livraison Yango Moto' ? 'selected' : '' }}>
                                            Livraison
                                            Yango</option>
                                    </select>
                                </div>
                                {{-- <div class="form-group">
                                <label class="small">Zone</label>
                                <select name="delivery_name" class="form-control">
                                    <option value="">— Sélectionner —</option>
                                    @foreach ($deliveries as $d)
                                        <option value="{{ $d->zone }}"
                                            {{ old('delivery_name') == $d->zone ? 'selected' : '' }}>
                                            {{ $d->zone }} — {{ number_format($d->tarif, 0, ',', ' ') }} FCFA
                                        </option>
                                    @endforeach
                                </select>
                            </div> --}}
                                <div class="form-group mb-0">
                                    <label class="small">Adresse précise si livraison Yango</label>
                                    <textarea name="address" class="form-control" rows="2" placeholder="Quartier, rue…">{{ old('address') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Paiement & Statut --}}
                    <div class="col-12 col-md-12">
                        <div class="card mb-3">
                            <div class="card-body">
                                <p class="pos-section-title"><i class="fas fa-credit-card mr-1"></i>Paiement & Statut</p>
                                <div class="form-group">
                                    <label class="small">Moyen de paiement</label>
                                    <select name="payment_method_id" class="form-control">
                                        {{-- <option value="">— Sélectionner —</option> --}}
                                        @foreach ($paymentMethods as $pm)
                                            <option value="{{ $pm->id }}"
                                                {{ old('payment_method_id') == $pm->id ? 'selected' : '' }}>
                                                {{ $pm->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-0">
                                    <label class="small">Statut de la commande <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" required>
                                        <option value="">— Sélectionner —</option>
                                        @foreach ($statuts as $key => $st)
                                            {{-- {{ old('status', 'attente') == $key ? 'selected' : '' }} --}}
                                            <option value="{{ $key }}">
                                                {{ $st['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══ Boutons de validation ══ --}}
                    <div class="row mb-4">
                        <div class="col-12 col-md-12">
                            <button type="submit" class="btn btn-success btn-lg btn-block mb-2" id="btn-confirm">
                                <i class="fas fa-check-circle mr-1"></i> Enregistrer la commande
                            </button>
                            {{-- <button type="submit" name="status" value="attente" class="btn btn-warning btn-block">
                                <i class="fas fa-pause-circle mr-1"></i> Mettre en attente
                            </button> --}}
                        </div>
                    </div>
                </div>



            </div>{{-- /row 2 --}}





        </form>
    </div>

    <script>
        let cartItems = {};

        $(document).ready(function() {

            // ── Select2 produit ──────────────────────────────────────────────────────────
            $('#product-select').on('change', function() {
                var val = $(this).val();
                if (!val) return;
                var opt = $(this).find('option[value="' + val + '"]');
                var rawStock = opt.attr('data-stock');
                var p = {
                    id: val,
                    title: opt.attr('data-title') || opt.text().split('—')[0].trim(),
                    price: parseFloat(opt.attr('data-price')) || 0,
                    stock: (rawStock !== undefined && rawStock !== '') ? parseInt(rawStock) : null,
                    img: opt.attr('data-img') || null
                };
                $(this).val(null).trigger('change');
                addProduct(p);
            });

        }); // end document.ready

        // ── Ajouter un produit au tableau ───────────────────────────────────────────
        function addProduct(p) {
            // Si déjà présent, incrémenter la quantité
            if (cartItems[p.id]) {
                const row = document.getElementById('row-' + p.id);
                const qtyInput = row.querySelector('.qty-input');
                let qty = parseInt(qtyInput.value) + 1;
                if (p.stock !== null && qty > p.stock) qty = p.stock;
                qtyInput.value = qty;
                recalcRow(p.id);
                return;
            }

            cartItems[p.id] = p;

            const emptyRow = document.getElementById('empty-row');
            if (emptyRow) emptyRow.remove();

            const tbody = document.getElementById('items-body');
            const tr = document.createElement('tr');
            tr.id = 'row-' + p.id;
            tr.innerHTML = `
        <td>
            <span class="font-weight-600">${p.title}</span>
            <input type="hidden" name="products[${p.id}][product_id]" value="${p.id}">
        </td>
        <td class="text-center">
            <span class="badge badge-${p.stock !== null && p.stock <= 5 ? 'danger' : 'light'} border">
                ${p.stock !== null ? p.stock : '∞'}
            </span>
        </td>
        <td>
            <input type="number" name="products[${p.id}][unit_price]"
                class="form-control form-control-sm unit-price-input bg-light"
                value="${p.price}" readonly>
        </td>
        <td>
            <div class="input-group">
                <div class="input-group-prepend">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="changeQty('${p.id}', -1)">−</button>
                </div>
                <input type="number" name="products[${p.id}][quantity]"
                    class="form-control form-control-sm qty-input text-center"
                    value="1" min="1" ${p.stock !== null ? 'max="'+p.stock+'"' : ''}
                    onchange="recalcRow('${p.id}')">
                <div class="input-group-append">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="changeQty('${p.id}', 1)">+</button>
                </div>
            </div>
        </td>
        <td>
            <div class="input-group">
                <div class="input-group-prepend">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="changeDiscount('${p.id}', -1)">&minus;</button>
                </div>
                <input type="number" name="products[${p.id}][discount]"
                    class="form-control form-control-sm discount-input text-center"
                    value="0" min="0" max="100" step="1"
                    onchange="recalcRow('${p.id}')">
                <div class="input-group-append">
                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="changeDiscount('${p.id}', 1)">+</button>
                </div>
            </div>
        </td>
        <td class="text-right">
            <span id="total-${p.id}" class="font-weight-bold text-dark">${formatMoney(p.price)} FCFA</span>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" onclick="removeRow('${p.id}')">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;
            tbody.appendChild(tr);
            recalcRow(p.id.toString());
        }

        // ── Changer quantité via boutons ───────────────────────────────────────────
        function changeQty(pid, delta) {
            const row = document.getElementById('row-' + pid);
            const input = row.querySelector('.qty-input');
            let val = parseInt(input.value) || 1;
            val = Math.max(1, val + delta);
            const stock = cartItems[pid].stock;
            if (stock !== null && val > stock) {
                val = stock;
            }
            input.value = val;
            recalcRow(pid);
        }

        // ── Changer remise via boutons ──────────────────────────────────────────────
        function changeDiscount(pid, delta) {
            const row = document.getElementById('row-' + pid);
            const input = row.querySelector('.discount-input');
            let val = parseInt(input.value) || 0;
            val = Math.min(100, Math.max(0, val + delta));
            input.value = val;
            recalcRow(pid);
        }

        // ── Recalcul ligne ──────────────────────────────────────────────────────────
        function recalcRow(pid) {
            const row = document.getElementById('row-' + pid);
            const qty = parseInt(row.querySelector('.qty-input').value) || 1;
            const unitPrice = parseFloat(row.querySelector('.unit-price-input').value) || 0;
            let discountPct = parseFloat(row.querySelector('.discount-input').value) || 0;
            const stock = cartItems[pid].stock;

            // Vérif max 100%
            if (discountPct > 100) {
                discountPct = 100;
                row.querySelector('.discount-input').value = 100;
            }
            if (discountPct < 0) {
                discountPct = 0;
                row.querySelector('.discount-input').value = 0;
            }

            // Vérif stock
            const qtyInput = row.querySelector('.qty-input');
            if (stock !== null && qty > stock) {
                qtyInput.value = stock;
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock insuffisant',
                    text: `Stock disponible : ${stock}`,
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            const prixApres = unitPrice * (1 - discountPct / 100);
            const total = Math.max(0, prixApres) * qty;
            document.getElementById('total-' + pid).textContent = formatMoney(total) + ' FCFA';
            recalcTotals();
        }

        // ── Supprimer une ligne ─────────────────────────────────────────────────────
        function removeRow(pid) {
            document.getElementById('row-' + pid)?.remove();
            delete cartItems[pid];
            if (!Object.keys(cartItems).length) {
                document.getElementById('items-body').innerHTML = `
            <tr id="empty-row">
                <td colspan="7" class="text-center text-muted py-4">
                    <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                    Aucun produit ajouté — utilisez la recherche ci-dessus.
                </td>
            </tr>`;
            }
            recalcTotals();
        }

        // ── Recalcul totaux globaux ─────────────────────────────────────────────────
        function recalcTotals() {
            let subtotal = 0;
            Object.keys(cartItems).forEach(pid => {
                const row = document.getElementById('row-' + pid);
                if (!row) return;
                const qty = parseInt(row.querySelector('.qty-input').value) || 1;
                const unitPrice = parseFloat(row.querySelector('.unit-price-input').value) || 0;
                const discountPct = parseFloat(row.querySelector('.discount-input').value) || 0;
                subtotal += Math.max(0, unitPrice * (1 - discountPct / 100)) * qty;
            });

            const globalDiscount = parseFloat(document.getElementById('discount').value) || 0;
            const delivery = parseFloat(document.getElementById('delivery_price').value) || 0;
            const acompte = parseFloat(document.getElementById('acompte').value) || 0;
            const total = Math.max(0, subtotal - globalDiscount + delivery);
            const solde = Math.max(0, total - acompte);

            document.getElementById('display-subtotal').textContent = formatMoney(subtotal) + ' FCFA';
            document.getElementById('display-total').textContent = formatMoney(total) + ' FCFA';
            document.getElementById('display-solde').textContent = formatMoney(solde) + ' FCFA';
        }

        document.getElementById('discount').addEventListener('input', recalcTotals);
        document.getElementById('delivery_price').addEventListener('input', recalcTotals);
        document.getElementById('acompte').addEventListener('input', recalcTotals);

        // ── Recherche client (AJAX) ─────────────────────────────────────────────────
        const clientSearch = document.getElementById('client-search');
        const clientResults = document.getElementById('client-results');

        let clientTimer;
        clientSearch.addEventListener('input', function() {
            clearTimeout(clientTimer);
            const q = this.value.trim();
            if (q.length < 2) {
                clientResults.style.display = 'none';
                return;
            }
            clientTimer = setTimeout(() => {
                fetch(`{{ route('pos.searchClient') }}?q=${encodeURIComponent(q)}`)
                    .then(r => r.json())
                    .then(users => {
                        if (!users.length) {
                            clientResults.style.display = 'none';
                            return;
                        }
                        clientResults.innerHTML = users.map(u => `
                    <a href="#" class="list-group-item list-group-item-action" onclick="selectClient(event, ${u.id}, '${u.name.replace(/'/g,"\\'")}', '${(u.phone||'').replace(/'/g,"\\'")}', '${(u.email||'').replace(/'/g,"\\'")}')">
                        <strong>${u.name}</strong><br>
                        <small class="text-muted">${u.phone ?? ''} ${u.email ? '| '+u.email : ''}</small>
                    </a>
                `).join('');
                        clientResults.style.display = 'block';
                    });
            }, 300);
        });

        function selectClient(e, id, name, phone, email) {
            e.preventDefault();
            clientResults.style.display = 'none';
            clientSearch.value = name + ' — ' + phone;

            document.getElementById('user_id').value = id;
            document.getElementById('cf-name').textContent = name;
            document.getElementById('cf-phone').textContent = phone;
            document.getElementById('cf-email').textContent = email;
            document.getElementById('client-found-box').classList.remove('d-none');
            document.getElementById('new-client-box').classList.add('d-none');
        }

        document.getElementById('btn-clear-client').addEventListener('click', function() {
            document.getElementById('user_id').value = '';
            document.getElementById('client-found-box').classList.add('d-none');
            document.getElementById('new-client-box').classList.remove('d-none');
            clientSearch.value = '';
        });

        document.addEventListener('click', e => {
            if (!e.target.closest('#client-search') && !e.target.closest('#client-results')) {
                clientResults.style.display = 'none';
            }
        });

        // ── Sélection source ────────────────────────────────────────────────────────
        function selectSource(el) {
            document.querySelectorAll('.source-btn').forEach(b => b.classList.remove('active'));
            el.classList.add('active');
            document.getElementById('source-input').value = el.dataset.value;
        }

        // ── Validation avant soumission ─────────────────────────────────────────────
        document.getElementById('pos-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const errors = [];
            const status = document.querySelector('[name="status"]').value;
            // attente / précommande / en_attente_acompte → acompte = 0 autorisé
            const isAttente = (status === 'attente' || status === 'precommande' || status === 'en_attente_acompte');

            // 1. Panier non vide
            if (!Object.keys(cartItems).length) {
                errors.push('Le panier est vide. Ajoutez au moins un produit.');
            }

            // 2. Téléphone client obligatoire (si pas de client trouvé via recherche)
            const userId = document.getElementById('user_id').value;
            const phone = document.querySelector('[name="client_phone"]').value.trim();
            if (!userId && phone.length < 8) {
                errors.push('Le téléphone du client est obligatoire (8 chiffres minimum).');
            }

            // 3. Champs requis hors "en attente"
            if (!isAttente) {
                const acompte = parseFloat(document.getElementById('acompte').value) || 0;
                if (acompte <= 0) {
                    errors.push('L\'acompte doit être supérieur à 0 pour ce statut.');
                }
                const paiement = document.querySelector('[name="payment_method_id"]').value;
                if (!paiement) {
                    errors.push('Le moyen de paiement est obligatoire pour ce statut.');
                }
            }

            if (errors.length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Vérifiez le formulaire',
                    html: '<ul class="text-left mb-0">' + errors.map(err => `<li>${err}</li>`).join('') +
                        '</ul>',
                    confirmButtonText: 'Corriger',
                    confirmButtonColor: '#4e73df'
                });
                return;
            }

            // Tout est OK → soumettre
            this.submit();
        });

        // ── Helper formatage ────────────────────────────────────────────────────────
        function formatMoney(n) {
            return Math.round(n).toLocaleString('fr-FR');
        }
    </script>

@endsection

{{-- ─── Scripts ─────────────────────────────────────────────────────────── --}}
@section('script')
    <script src="{{ asset('admin/assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
@endsection
