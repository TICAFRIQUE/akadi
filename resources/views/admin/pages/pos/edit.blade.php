@extends('admin.layouts.app')
@section('title', 'Modifier la commande')
@section('sub-title', 'Commande #' . $order->code)

@section('css')
    <link rel="stylesheet" href="{{ asset('admin/assets/bundles/select2/dist/css/select2.min.css') }}">
    <style>
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
        #tbl-items { min-width: 700px; }
        #tbl-items thead th { background: #f8f9fa; white-space: nowrap; font-size: .82rem; padding: 10px 12px; }
        #tbl-items tbody td { vertical-align: middle; padding: 8px 10px; }
        #tbl-items .input-group-sm .form-control { min-width: 52px; }
        #tbl-items .input-group-sm .btn { min-width: 30px; }
        .btn-remove-row { width: 32px; height: 32px; padding: 0; }
        .recap-line { display: flex; justify-content: space-between; padding: 5px 0; }
        .recap-line.total-final { font-size: 1.3rem; font-weight: 700; border-top: 2px solid #dee2e6; padding-top: 10px; margin-top: 5px; }
        .recap-line.solde { color: #dc3545; font-weight: 600; }
        .source-btn { cursor: pointer; }
        .source-btn.active { box-shadow: 0 0 0 2px #4e73df; }
        .caisse-badge { display: inline-flex; align-items: center; gap: 8px; background: #f0f4ff; border-radius: 8px; padding: 4px 12px; font-size: .85rem; font-weight: 600; color: #4e73df; }
        .client-found { border: 2px solid #28a745; border-radius: 10px; padding: 12px 16px; background: #f6fff8; }

        /* Boutons type remise */
        .btn-xs { padding: 2px 7px; font-size: .75rem; line-height: 1.4; border-radius: 3px; }
        .type-disc-btn {
            height: 31px;
            padding: 0 8px;
            font-size: .75rem;
            font-weight: 600;
            line-height: 29px;
            border-radius: 0;
            border: 1px solid #ced4da !important;
            background: #f8f9fa !important;
            color: #495057 !important;
            box-shadow: none !important;
            transition: background .15s, color .15s, border-color .15s;
        }
        .type-disc-btn:first-child { border-radius: 0; }
        .type-disc-btn:last-child  { border-radius: 0 4px 4px 0; }
        .type-disc-btn.active-pct,
        .type-disc-btn.active-pct:focus,
        .type-disc-btn.active-pct:active  { background: #4e73df !important; border-color: #4e73df !important; color: #fff !important; box-shadow: none !important; }
        .type-disc-btn.active-fixed,
        .type-disc-btn.active-fixed:focus,
        .type-disc-btn.active-fixed:active { background: #fd7e14 !important; border-color: #fd7e14 !important; color: #fff !important; box-shadow: none !important; }
        .global-disc-btn { font-size: .75rem; font-weight: 600; padding: 2px 8px; }
        .global-disc-btn.active-pct   { background: #4e73df !important; border-color: #4e73df !important; color: #fff !important; }
        .global-disc-btn.active-fixed { background: #fd7e14 !important; border-color: #fd7e14 !important; color: #fff !important; }
    </style>
@endsection

@section('content')
<div class="section-body">

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif
    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <form action="{{ route('pos.update', $order->id) }}" method="POST" id="pos-form">
        @csrf

        {{-- ══ ROW 1 : Recherche + Tableau panier (full width) ══ --}}
        <div class="row">
            <div class="col-md-12">

                <div class="mb-3">
                    <span class="caisse-badge">
                        <i class="fas fa-file-invoice"></i>
                        Commande : {{ $order->code }}
                    </span>
                    <a href="{{ route('order.show', $order->id) }}" class="btn btn-sm btn-outline-secondary ml-2">
                        <i class="fas fa-arrow-left mr-1"></i> Retour
                    </a>
                </div>

                {{-- Sélecteur de produit --}}
                <div class="card mb-3" style="overflow:visible">
                    <div class="card-body pb-2" style="overflow:visible">
                        <p class="pos-section-title"><i class="fas fa-search mr-1"></i>Ajouter un produit</p>
                        <select id="product-select" class="form-control select2" style="width:100%">
                            <option value="">Rechercher un produit…</option>
                            @foreach($products as $p)
                                @php
                                    $stockDisponible = $p->getStockDisponible();
                                    $isInfini = $p->isStockInfini();
                                @endphp
                                <option value="{{ $p->id }}"
                                    data-price="{{ $p->price }}"
                                    data-stock="{{ $isInfini ? '' : $stockDisponible }}"
                                    data-img="{{ $p->getFirstMediaUrl('principal_img') }}"
                                    data-title="{{ $p->title }}">
                                    {{ $p->title }} — {{ number_format($p->price, 0, ',', ' ') }} FCFA
                                </option>
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
                                        <th class="text-center">P.U</th>
                                        <th class="text-center">Qté</th>
                                        <th class="text-center">Remise <small class="text-muted">(% ou FCFA)</small></th>
                                        <th class="text-center">Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="items-body">
                                    <tr id="empty-row">
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                            Chargement des produits…
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>{{-- /row 1 --}}

        {{-- ══ ROW 2 : Gauche (Client + Options + Provenance) | Droite (Récap + Livraison + Paiement + Bouton) ══ --}}
        <div class="row">

            {{-- ── Colonne gauche ── --}}
            <div class="col-md-6">

                {{-- Client --}}
                <div class="col-12 col-md-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <p class="pos-section-title"><i class="fas fa-user mr-1"></i>Client</p>
                            @if($order->user)
                                <div class="client-found mb-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-user-check text-success mr-1"></i>
                                            <strong>{{ $order->user->name }}</strong><br>
                                            <small class="text-muted">{{ $order->user->phone }}</small>
                                        </div>
                                        <span class="badge badge-success">Compte</span>
                                    </div>
                                </div>
                                <input type="hidden" name="user_id" value="{{ $order->user_id }}">
                            @else
                                <div class="form-group">
                                    <label class="small">Rechercher un client existant</label>
                                    <input type="text" id="client-search" class="form-control"
                                        placeholder="Nom ou téléphone…" autocomplete="off">
                                    <div id="client-results" class="list-group position-absolute"
                                        style="z-index:999;width:90%;display:none;"></div>
                                </div>
                                <div id="client-found-box" class="d-none mb-3">
                                    <div class="client-found">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <i class="fas fa-user-check text-success mr-1"></i>
                                                <strong id="cf-name"></strong><br>
                                                <small id="cf-phone" class="text-muted"></small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger" id="btn-clear-client">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="user_id" id="user_id">
                                </div>
                                <div id="new-client-box">
                                    <div class="form-group">
                                        <label class="small">Nom du client</label>
                                        <input type="text" name="client_name" class="form-control"
                                            placeholder="Nom complet"
                                            value="{{ old('client_name', $order->client_name) }}">
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="small">Téléphone</label>
                                        <input type="text" name="client_phone" class="form-control"
                                            placeholder="+225 07 …"
                                            value="{{ old('client_phone', $order->client_phone) }}">
                                    </div>
                                </div>
                            @endif
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
                                    <option value="normal"      {{ $order->type_order == 'normal'      ? 'selected' : '' }}>Normal</option>
                                    <option value="precommande" {{ $order->type_order == 'precommande' ? 'selected' : '' }}>Précommande</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="small">Date de livraison prévue</label>
                                <input type="date" name="delivery_planned" class="form-control"
                                    value="{{ old('delivery_planned', $order->delivery_planned) }}">
                            </div>
                            <div class="form-group mb-0">
                                <label class="small">Note interne</label>
                                <textarea name="note" class="form-control" rows="2"
                                    placeholder="Instructions, remarques…">{{ old('note', $order->note) }}</textarea>
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
                                @foreach($sources as $key => $src)
                                <div class="col-6 mb-2">
                                    <div class="btn btn-outline-secondary btn-block source-btn {{ $order->source == $key ? 'active' : '' }}"
                                        data-value="{{ $key }}" onclick="selectSource(this)">
                                        <i class="fab {{ $src['icon'] }} mr-1"></i> {{ $src['label'] }}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <input type="hidden" name="source" id="source-input"
                                value="{{ old('source', $order->source) }}">
                        </div>
                    </div>
                </div>

            </div>{{-- /col gauche --}}

            {{-- ── Colonne droite ── --}}
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
                                <span>Remise globale
                                    <span class="btn-group btn-group-sm ml-1" role="group">
                                        <button type="button" class="btn btn-xs global-disc-btn {{ ($order->type_discount ?? 'fixed') === 'percent' ? 'active-pct' : '' }}" id="global-disc-pct" onclick="setGlobalDiscountType('percent', this)" title="En pourcentage">%</button>
                                        <button type="button" class="btn btn-xs global-disc-btn {{ ($order->type_discount ?? 'fixed') === 'fixed' ? 'active-fixed' : '' }}" id="global-disc-fixed" onclick="setGlobalDiscountType('fixed', this)" title="Montant fixe FCFA">FCFA</button>
                                    </span>
                                </span>
                                <div class="d-flex align-items-center">
                                    <input type="number" name="discount" id="discount"
                                        class="form-control form-control-sm text-right" style="width:110px"
                                        value="{{ old('discount', $order->discount ?? 0) }}" min="0" step="any">
                                    <span id="global-disc-unit" class="ml-1 small text-muted">{{ ($order->type_discount ?? 'fixed') === 'percent' ? '%' : 'FCFA' }}</span>
                                </div>
                                <input type="hidden" name="type_discount" id="type_discount" value="{{ old('type_discount', $order->type_discount ?? 'fixed') }}">
                            </div>
                            <div class="recap-line align-items-center">
                                <span>Frais de livraison</span>
                                <input type="number" name="delivery_price" id="delivery_price"
                                    class="form-control form-control-sm text-right" style="width:140px"
                                    value="{{ old('delivery_price', $order->delivery_price ?? 0) }}" min="0" step="any">
                            </div>
                            <div class="recap-line total-final">
                                <span>TOTAL</span>
                                <span id="display-total" class="text-dark">0 FCFA</span>
                            </div>
                            <div class="recap-line align-items-center mt-2">
                                <span class="font-weight-bold">Acompte reçu</span>
                                <input type="number" name="acompte" id="acompte"
                                    class="form-control form-control-sm text-right" style="width:140px"
                                    value="{{ old('acompte', $order->acompte ?? 0) }}" min="0" step="any">
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
                                <label class="small">Mode de livraison</label>
                                <select name="mode_livraison" class="form-control">
                                    <option value="">— Sélectionner —</option>
                                    <option value="Je passe récupérer" {{ $order->mode_livraison == 'Je passe récupérer' ? 'selected' : '' }}>Récupérer sur place</option>
                                    <option value="Livraison Yango Moto" {{ $order->mode_livraison == 'Livraison Yango Moto' ? 'selected' : '' }}>Livraison Yango</option>
                                </select>
                            </div>
                            <div class="form-group mb-0">
                                <label class="small">Adresse précise si livraison Yango</label>
                                <textarea name="address" class="form-control" rows="2"
                                    placeholder="Quartier, rue…">{{ old('address', $order->address) }}</textarea>
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
                                    <option value="">— Sélectionner —</option>
                                    @foreach($paymentMethods as $pm)
                                        <option value="{{ $pm->id }}" {{ $order->payment_method_id == $pm->id ? 'selected' : '' }}>
                                            {{ $pm->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-0">
                                <label class="small">Statut de la commande <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="">— Sélectionner —</option>
                                    @foreach($statuts as $key => $st)
                                        <option value="{{ $key }}" {{ $order->status == $key ? 'selected' : '' }}>
                                            {{ $st['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Boutons --}}
                <div class="row mb-4">
                    <div class="col-12 col-md-12">
                        <button type="submit" class="btn btn-success btn-lg btn-block mb-2">
                            <i class="fas fa-save mr-1"></i> Enregistrer les modifications
                        </button>
                        <a href="{{ route('order.show', $order->id) }}" class="btn btn-outline-secondary btn-block">
                            <i class="fas fa-times mr-1"></i> Annuler
                        </a>
                    </div>
                </div>

            </div>{{-- /col droite --}}

        </div>{{-- /row 2 --}}

    </form>
</div>

@php
    $existingItemsJson = $order->products->map(fn($p) => [
        'id'       => $p->id,
        'title'    => $p->title,
        'price'    => $p->price,
        'stock'    => $p->isStockInfini() ? null : $p->getStockDisponible(),
        'img'      => $p->getFirstMediaUrl('principal_img') ?: null,
        'qty'      => $p->pivot->quantity,
        'discount'      => (float)($p->pivot->discount ?? 0),
        'type_discount' => $p->pivot->type_discount ?? 'percent',
    ]);
@endphp
<script>
    let cartItems    = {};
    let currentTotal = 0;

    $(document).ready(function () {

        // ── Select2 produit ──────────────────────────────────────────────────────
        $('#product-select').on('change', function () {
            var val = $(this).val();
            if (!val) return;
            var opt = $(this).find('option[value="' + val + '"]');
            var rawStock = opt.attr('data-stock');
            var p = {
                id:    val,
                title: opt.attr('data-title') || opt.text().split('—')[0].trim(),
                price: parseFloat(opt.attr('data-price')) || 0,
                stock: (rawStock !== undefined && rawStock !== '') ? parseInt(rawStock) : null,
                img:   opt.attr('data-img') || null,
            };
            $(this).val(null).trigger('change');
            addProduct(p);
        });

        // ── Pré-chargement des produits existants ────────────────────────────────
        const EXISTING_ITEMS = @json($existingItemsJson);
        EXISTING_ITEMS.forEach(p => {
            cartItems[p.id] = p;
            addProductExisting(p, p.qty, p.discount, p.type_discount || 'percent');
        });
        document.getElementById('empty-row')?.remove();
        recalcTotals();

    }); // end document.ready

    // ── Ajouter un produit existant (édition) ──────────────────────────────────
    function addProductExisting(p, qty, discountVal, typeDiscount) {
        const tbody = document.getElementById('items-body');
        const tr = document.createElement('tr');
        tr.id = 'row-' + p.id;
        tr.innerHTML = buildRowHtml(p, qty, discountVal, typeDiscount || 'percent');
        tbody.appendChild(tr);
        recalcRow(p.id);
    }

    // ── Ajouter un produit depuis Select2 ─────────────────────────────────────
    function addProduct(p) {
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
        document.getElementById('empty-row')?.remove();
        const tbody = document.getElementById('items-body');
        const tr = document.createElement('tr');
        tr.id = 'row-' + p.id;
        tr.innerHTML = buildRowHtml(p, 1, 0, 'percent');
        tbody.appendChild(tr);
        recalcRow(p.id.toString());
    }

    // ── Construire une ligne du tableau ───────────────────────────────────────
    function buildRowHtml(p, qty, discountVal, typeDiscount) {
        typeDiscount = typeDiscount || 'percent';
        const stockBadge = p.stock !== null
            ? `<span class="badge badge-${p.stock <= 5 ? 'danger' : 'light'} border">${p.stock}</span>`
            : `<span class="badge badge-light border">∞</span>`;
        const maxAttr = p.stock !== null ? `max="${p.stock}"` : '';
        const pctActive   = typeDiscount === 'percent' ? 'active-pct'   : '';
        const fixedActive = typeDiscount === 'fixed'   ? 'active-fixed' : '';
        return `
        <td>
            <span class="font-weight-600">${p.title}</span>
            <input type="hidden" name="products[${p.id}][product_id]" value="${p.id}">
        </td>
        <td class="text-center">${stockBadge}</td>
        <td style="width:110px">
            <input type="number" name="products[${p.id}][unit_price]"
                class="form-control form-control-sm unit-price-input bg-light text-right"
                style="width:100px" value="${p.price}" readonly>
        </td>
        <td style="width:100px ; padding-left:20px">
            <div class="input-group input-group-sm" style="width:96px;flex-wrap:nowrap">
                <div class="input-group-prepend">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-1" onclick="changeQty('${p.id}', -1)">−</button>
                </div>
                <input type="number" name="products[${p.id}][quantity]"
                    class="form-control form-control-sm qty-input text-center"
                    style="min-width:56px"
                    value="${qty}" min="1" ${maxAttr}
                    onchange="recalcRow('${p.id}')">
                <div class="input-group-append">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-1" onclick="changeQty('${p.id}', 1)">+</button>
                </div>
            </div>
        </td>
        <td style="width:150px; padding-left:35px">
            <div class="input-group input-group-sm" style="width:140px;flex-wrap:nowrap">
                <input type="number" name="products[${p.id}][discount]"
                    class="form-control form-control-sm discount-input text-center"
                    style="min-width:100px"
                    value="${discountVal}" min="0" max="${typeDiscount === 'percent' ? 100 : p.price}" step="1"
                    onchange="recalcRow('${p.id}')">
                <div class="input-group-append">
                    <button type="button" class="btn type-disc-btn type-disc-pct ${pctActive}" onclick="setDiscountType('${p.id}', 'percent', this)" title="En pourcentage">%</button>
                    <button type="button" class="btn type-disc-btn type-disc-fixed ${fixedActive}" onclick="setDiscountType('${p.id}', 'fixed', this)" title="Montant fixe">FCFA</button>
                </div>
                <input type="hidden" name="products[${p.id}][type_discount]" class="type-discount-input" value="${typeDiscount}">
            </div>
        </td>
        <td class="text-right">
            <span id="total-${p.id}" class="font-weight-bold text-dark">${formatMoney(p.price)} FCFA</span>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row" onclick="removeRow('${p.id}')">
                <i class="fas fa-times"></i>
            </button>
        </td>`;
    }

    // ── Changer quantité via boutons ───────────────────────────────────────────
    function changeQty(pid, delta) {
        const row = document.getElementById('row-' + pid);
        const input = row.querySelector('.qty-input');
        let val = Math.max(1, (parseInt(input.value) || 1) + delta);
        const stock = cartItems[pid]?.stock;
        if (stock !== null && stock !== undefined && val > stock) val = stock;
        input.value = val;
        recalcRow(pid);
    }

    // ── Changer type de remise par ligne (% ou fixe FCFA) ────────────────────
    function setDiscountType(pid, type, btn) {
        const row = document.getElementById('row-' + pid);
        const discountInput = row.querySelector('.discount-input');
        const typeInput     = row.querySelector('.type-discount-input');
        row.querySelectorAll('.type-disc-btn').forEach(b => b.classList.remove('active-pct', 'active-fixed'));
        if (type === 'percent') {
            row.querySelector('.type-disc-pct').classList.add('active-pct');
            discountInput.max = 100;
        } else {
            row.querySelector('.type-disc-fixed').classList.add('active-fixed');
            const unitPrice = parseFloat(row.querySelector('.unit-price-input').value) || 0;
            discountInput.max = unitPrice;
        }
        typeInput.value = type;
        discountInput.value = 0;
        recalcRow(pid);
    }

    // ── Changer type de remise globale ────────────────────────────────────────
    function setGlobalDiscountType(type, btn) {
        document.querySelectorAll('.global-disc-btn').forEach(b => b.classList.remove('active-pct', 'active-fixed'));
        if (type === 'percent') {
            document.getElementById('global-disc-pct').classList.add('active-pct');
            document.getElementById('global-disc-unit').textContent = '%';
            document.getElementById('discount').max = 100;
        } else {
            document.getElementById('global-disc-fixed').classList.add('active-fixed');
            document.getElementById('global-disc-unit').textContent = 'FCFA';
            document.getElementById('discount').removeAttribute('max');
        }
        document.getElementById('type_discount').value = type;
        document.getElementById('discount').value = 0;
        recalcTotals();
    }

    // ── Recalcul ligne ─────────────────────────────────────────────────────────
    function recalcRow(pid) {
        const row = document.getElementById('row-' + pid);
        if (!row) return;
        const qty          = parseInt(row.querySelector('.qty-input').value) || 1;
        const unitPrice    = parseFloat(row.querySelector('.unit-price-input').value) || 0;
        let discountVal    = parseFloat(row.querySelector('.discount-input').value) || 0;
        const typeDiscount = row.querySelector('.type-discount-input').value || 'percent';
        const stock        = cartItems[pid]?.stock;
        if (discountVal < 0) { discountVal = 0; row.querySelector('.discount-input').value = 0; }
        if (typeDiscount === 'percent' && discountVal > 100)  { discountVal = 100;      row.querySelector('.discount-input').value = 100; }
        if (typeDiscount === 'fixed'   && discountVal > unitPrice) { discountVal = unitPrice; row.querySelector('.discount-input').value = unitPrice; }
        if (stock !== null && stock !== undefined && qty > stock) row.querySelector('.qty-input').value = stock;
        let prixApres;
        if (typeDiscount === 'percent') {
            prixApres = unitPrice * (1 - discountVal / 100);
        } else {
            prixApres = unitPrice - discountVal;
        }
        const total = Math.max(0, prixApres) * qty;
        document.getElementById('total-' + pid).textContent = formatMoney(total) + ' FCFA';
        recalcTotals();
    }

    // ── Supprimer une ligne ────────────────────────────────────────────────────
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

    // ── Recalcul totaux globaux ────────────────────────────────────────────────
    function recalcTotals() {
        let subtotal = 0;
        Object.keys(cartItems).forEach(pid => {
            const row = document.getElementById('row-' + pid);
            if (!row) return;
            const qty          = parseInt(row.querySelector('.qty-input').value) || 1;
            const unitPrice    = parseFloat(row.querySelector('.unit-price-input').value) || 0;
            const discountVal  = parseFloat(row.querySelector('.discount-input').value) || 0;
            const typeDiscount = row.querySelector('.type-discount-input').value || 'percent';
            let prixApres;
            if (typeDiscount === 'percent') {
                prixApres = unitPrice * (1 - discountVal / 100);
            } else {
                prixApres = unitPrice - discountVal;
            }
            subtotal += Math.max(0, prixApres) * qty;
        });
        const globalDiscountVal  = parseFloat(document.getElementById('discount').value) || 0;
        const globalDiscountType = document.getElementById('type_discount').value || 'fixed';
        let globalDiscountAmount;
        if (globalDiscountType === 'percent') {
            globalDiscountAmount = subtotal * Math.min(globalDiscountVal, 100) / 100;
        } else {
            globalDiscountAmount = globalDiscountVal;
        }
        const delivery = parseFloat(document.getElementById('delivery_price').value) || 0;
        const acompte  = parseFloat(document.getElementById('acompte').value) || 0;
        const total    = Math.max(0, subtotal - globalDiscountAmount + delivery);
        const solde    = Math.max(0, total - acompte);
        currentTotal = total;
        document.getElementById('display-subtotal').textContent = formatMoney(subtotal) + ' FCFA';
        document.getElementById('display-total').textContent    = formatMoney(total)    + ' FCFA';
        document.getElementById('display-solde').textContent    = formatMoney(solde)    + ' FCFA';
    }

    document.getElementById('discount').addEventListener('input', recalcTotals);
    document.getElementById('delivery_price').addEventListener('input', recalcTotals);
    document.getElementById('acompte').addEventListener('input', recalcTotals);

    // ── Recherche client AJAX ──────────────────────────────────────────────────
    const csEl = document.getElementById('client-search');
    if (csEl) {
        const clientResults = document.getElementById('client-results');
        let clientTimer;
        csEl.addEventListener('input', function () {
            clearTimeout(clientTimer);
            const q = this.value.trim();
            if (q.length < 2) { clientResults.style.display = 'none'; return; }
            clientTimer = setTimeout(() => {
                fetch(`{{ route('pos.searchClient') }}?q=${encodeURIComponent(q)}`)
                    .then(r => r.json())
                    .then(users => {
                        if (!users.length) { clientResults.style.display = 'none'; return; }
                        clientResults.innerHTML = users.map(u => `
                            <a href="#" class="list-group-item list-group-item-action"
                                onclick="selectClient(event, ${u.id}, '${u.name.replace(/'/g,"\\'")}', '${(u.phone||'').replace(/'/g,"\\'")}')">
                                <strong>${u.name}</strong><br>
                                <small class="text-muted">${u.phone ?? ''}</small>
                            </a>`).join('');
                        clientResults.style.display = 'block';
                    });
            }, 300);
        });
        document.getElementById('btn-clear-client')?.addEventListener('click', function () {
            document.getElementById('user_id').value = '';
            document.getElementById('client-found-box').classList.add('d-none');
            document.getElementById('new-client-box').classList.remove('d-none');
            csEl.value = '';
        });
        document.addEventListener('click', e => {
            if (!e.target.closest('#client-search') && !e.target.closest('#client-results'))
                clientResults.style.display = 'none';
        });
    }

    function selectClient(e, id, name, phone) {
        e.preventDefault();
        document.getElementById('client-results').style.display = 'none';
        document.getElementById('client-search').value = name + ' — ' + phone;
        document.getElementById('user_id').value = id;
        document.getElementById('cf-name').textContent = name;
        document.getElementById('cf-phone').textContent = phone;
        document.getElementById('client-found-box').classList.remove('d-none');
        document.getElementById('new-client-box').classList.add('d-none');
    }

    // ── Sélection source ───────────────────────────────────────────────────────
    function selectSource(el) {
        document.querySelectorAll('.source-btn').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('source-input').value = el.dataset.value;
    }

    // ── Validation avant soumission ────────────────────────────────────────────
    document.getElementById('pos-form').addEventListener('submit', function (e) {
        e.preventDefault();
        const errors    = [];
        const status   = document.querySelector('[name="status"]').value;
        const isAttente = ['attente', 'precommande', 'en_attente_acompte', 'annulée'].includes(status);
        const isLivree  = status === 'livrée';

        if (!Object.keys(cartItems).length)
            errors.push('Le panier est vide. Ajoutez au moins un produit.');

        const phoneEl = document.querySelector('[name="client_phone"]');
        const uidEl   = document.querySelector('[name="user_id"]');
        if (phoneEl && uidEl && !uidEl.value && phoneEl.value.trim().length < 8)
            errors.push('Le téléphone du client est obligatoire (8 chiffres minimum).');

        if (!isAttente) {
            const acompte = parseFloat(document.getElementById('acompte').value) || 0;
            if (isLivree) {
                if (Math.round(acompte) !== Math.round(currentTotal)) {
                    errors.push(`Pour une commande livrée, l'acompte (${formatMoney(acompte)} FCFA) doit être égal au total (${formatMoney(currentTotal)} FCFA).`);
                }
            } else if (acompte <= 0) {
                errors.push("L'acompte doit être supérieur à 0 pour ce statut.");
            }
            const pm = document.querySelector('[name="payment_method_id"]').value;
            if (!pm) errors.push('Le moyen de paiement est obligatoire pour ce statut.');
        }

        if (errors.length) {
            Swal.fire({
                icon: 'warning',
                title: 'Vérifiez le formulaire',
                html: '<ul class="text-left mb-0">' + errors.map(err => `<li>${err}</li>`).join('') + '</ul>',
                confirmButtonText: 'Corriger',
                confirmButtonColor: '#4e73df'
            });
            return;
        }
        this.submit();
    });

    // ── Helper formatage ───────────────────────────────────────────────────────
    function formatMoney(n) { return Math.round(n).toLocaleString('fr-FR'); }
</script>

@endsection

@section('script')
    <script src="{{ asset('admin/assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
@endsection