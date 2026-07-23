@extends('site.layouts.app')

@section('title', 'Panier')

@section('content')

<style>
/* ── Cart section ── */
.ak-cart-section { padding: 48px 0 64px; background: #fafafa; }

/* ── Cart item card ── */
.ak-cart-item {
    background: #fff;
    border-radius: 16px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    transition: box-shadow .2s;
    position: relative;
}
.ak-cart-item:hover { box-shadow: 0 4px 20px rgba(0,0,0,.1); }

.ak-cart-item-img {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
    background: #f3f3f3;
}
.ak-cart-item-img img { width: 100%; height: 100%; object-fit: cover; }

.ak-cart-item-info { flex: 1; min-width: 0; }
.ak-cart-item-name {
    font-size: .9rem;
    font-weight: 700;
    color: #1a1a1a;
    text-decoration: none;
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 4px;
}
.ak-cart-item-name:hover { color: var(--ak-red,#eb0029); }
.ak-cart-item-unit { font-size: .78rem; color: #999; }
.ak-cart-item-unit span { color: #555; font-weight: 600; }

/* Quantity controls */
.ak-qty-ctrl {
    display: flex;
    align-items: center;
    gap: 0;
    border: 1.5px solid #e8e8e8;
    border-radius: 10px;
    overflow: hidden;
    flex-shrink: 0;
}
.ak-qty-btn {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f8f8;
    border: none;
    color: #555;
    font-size: .75rem;
    cursor: pointer;
    transition: all .15s;
}
.ak-qty-btn:hover { background: var(--ak-orange,#f85d05); color: #fff; }
.ak-qty-btn:disabled { opacity: .4; cursor: not-allowed; }
.ak-qty-input {
    width: 44px;
    text-align: center;
    border: none;
    border-left: 1px solid #e8e8e8;
    border-right: 1px solid #e8e8e8;
    font-size: .88rem;
    font-weight: 700;
    color: #1a1a1a;
    background: #fff;
    padding: 0;
    height: 34px;
}
.ak-qty-input:focus { outline: none; }

/* Item total */
.ak-cart-item-total {
    font-size: 1rem;
    font-weight: 800;
    color: var(--ak-red,#eb0029);
    flex-shrink: 0;
    min-width: 100px;
    text-align: right;
}

/* Remove button */
.ak-cart-remove {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(220,53,69,.07);
    border: none;
    color: #dc3545;
    font-size: .8rem;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all .15s;
    flex-shrink: 0;
    text-decoration: none;
}
.ak-cart-remove:hover { background: #dc3545; color: #fff; }

/* ── Header items list ── */
.ak-cart-list-header {
    display: grid;
    grid-template-columns: 80px 1fr 120px 110px 130px 40px;
    gap: 16px;
    align-items: center;
    padding: 0 16px 12px;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: #999;
    border-bottom: 1.5px solid #e8e8e8;
    margin-bottom: 12px;
}
.ak-cart-item-grid {
    display: grid;
    grid-template-columns: 80px 1fr 120px 110px 130px 40px;
    gap: 16px;
    align-items: center;
}

/* ── Sidebar summary ── */
.ak-cart-sidebar {
    background: #fff;
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 4px 24px rgba(0,0,0,.07);
    position: sticky;
    top: 90px;
}
.ak-sidebar-title {
    font-size: 1rem;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1.5px solid #f0f0f0;
}
.ak-sidebar-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    font-size: .88rem;
    color: #666;
    border-bottom: 1px solid #f8f8f8;
}
.ak-sidebar-row:last-of-type { border-bottom: none; }
.ak-sidebar-row.total-row {
    padding: 16px 0 0;
    font-size: 1.1rem;
    font-weight: 800;
    color: #1a1a1a;
    border-top: 1.5px solid #f0f0f0;
    margin-top: 8px;
}
.ak-sidebar-row.total-row .val { color: var(--ak-red,#eb0029); font-size: 1.3rem; }
.ak-sidebar-actions { display: flex; flex-direction: column; gap: 10px; margin-top: 20px; }
.ak-sidebar-continue {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px;
    background: #f8f8f8;
    color: #555;
    font-size: .85rem;
    font-weight: 600;
    border-radius: 10px;
    text-decoration: none;
    transition: all .15s;
    border: 1.5px solid #e8e8e8;
}
.ak-sidebar-continue:hover { border-color: var(--ak-orange,#f85d05); color: var(--ak-orange,#f85d05); background: rgba(248,93,5,.04); text-decoration: none; }
.ak-sidebar-checkout {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 14px;
    background: var(--ak-orange,#f85d05);
    color: #fff;
    font-size: .92rem;
    font-weight: 700;
    border-radius: 10px;
    text-decoration: none;
    transition: all .2s;
    box-shadow: 0 4px 16px rgba(248,93,5,.35);
}
.ak-sidebar-checkout:hover { background: #d44d00; color: #fff; text-decoration: none; transform: translateY(-2px); }

/* ── Empty cart ── */
.ak-empty-cart {
    text-align: center;
    padding: 64px 24px;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 24px rgba(0,0,0,.06);
}
.ak-empty-cart-icon {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: rgba(235,0,41,.06);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 2rem;
    color: var(--ak-red,#eb0029);
}
.ak-empty-cart h3 { font-size: 1.2rem; font-weight: 700; color: #333; margin-bottom: 8px; }
.ak-empty-cart p { font-size: .88rem; color: #888; margin-bottom: 24px; }
.ak-empty-cart-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 28px;
    background: var(--ak-red,#eb0029);
    color: #fff;
    font-size: .88rem;
    font-weight: 700;
    border-radius: 10px;
    text-decoration: none;
    transition: background .2s;
}
.ak-empty-cart-cta:hover { background: #c4001f; color: #fff; text-decoration: none; }

/* Responsive */
@media (max-width: 767px) {
    .ak-cart-list-header { display: none; }
    .ak-cart-item-grid {
        grid-template-columns: 70px 1fr;
        grid-template-rows: auto auto auto;
    }
    .ak-cart-item-grid .col-qty { grid-column: 1 / -1; }
    .ak-cart-item-grid .col-total { grid-column: 1 / -1; text-align: left; }
    .ak-cart-item-grid .col-remove { position: absolute; top: 12px; right: 12px; }
    .ak-cart-item { flex-wrap: wrap; }
    .ak-cart-sidebar { margin-top: 24px; position: static; }
}
</style>

{{-- ── Breadcrumb ── --}}
<div class="ak-breadcrumb">
    <div class="container">
        <h1 class="ak-breadcrumb-title">
            Mon panier
            <span class="ak-breadcrumb-count quantityProduct">{{ count((array) session('cart')) }}</span>
        </h1>
        <ul class="ak-breadcrumb-nav">
            <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li><a href="{{ route('liste-produit') }}">Nos plats</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li class="active">Panier</li>
        </ul>
    </div>
</div>

{{-- ── Cart ── --}}
<section class="ak-cart-section">
    <div class="container">
        @include('admin.components.validationMessage')

        @if (session('cart'))
            @php $sousTotal = 0; @endphp
            @foreach (session('cart') as $id => $details)
                @php $sousTotal += $details['price'] * $details['quantity']; @endphp
            @endforeach

            <div class="row gy-4 align-items-start">
                {{-- Items --}}
                <div class="col-lg-8">
                    {{-- Header labels (desktop) --}}
                    <div class="ak-cart-list-header">
                        <div></div>
                        <div>Plat</div>
                        <div style="text-align:center">Quantité</div>
                        <div style="text-align:right">Prix unitaire</div>
                        <div style="text-align:right">Total</div>
                        <div></div>
                    </div>

                    {{-- Items list --}}
                    <div class="d-flex flex-column gap-3">
                        @foreach (session('cart') as $id => $details)
                            @php $itemTotal = $details['price'] * $details['quantity']; @endphp
                            <div class="ak-cart-item" id="row_{{ $id }}">
                                {{-- Image --}}
                                <a href="{{ route('detail-produit', $details['slug']) }}" class="ak-cart-item-img" style="flex-shrink:0;">
                                    <img src="{{ $details['image'] }}" alt="{{ $details['title'] }}">
                                </a>
                                {{-- Name + unit price --}}
                                <div class="ak-cart-item-info">
                                    <a href="{{ route('detail-produit', $details['slug']) }}" class="ak-cart-item-name">
                                        {{ $details['title'] }}
                                    </a>
                                    <div class="ak-cart-item-unit">
                                        Prix unitaire : <span>{{ number_format($details['price'], 0, ',', ' ') }} FCFA</span>
                                    </div>
                                    <div class="ak-cart-item-unit">
                                        En panier : <span id="qte{{ $id }}">{{ $details['quantity'] }}</span>
                                    </div>
                                </div>
                                {{-- Qty controls --}}
                                <div class="ak-qty-ctrl">
                                    <button class="ak-qty-btn qte-decrease_{{ $id }}" onclick="decreaseValue({{ $id }})"
                                            {{ $details['quantity'] <= 1 ? 'disabled' : '' }}>
                                        <i class="far fa-minus"></i>
                                    </button>
                                    <input type="number"
                                           id="{{ $id }}"
                                           class="ak-qty-input qty-input{{ $id }} qte-input"
                                           value="{{ $details['quantity'] }}"
                                           min="1" max="99" readonly>
                                    <button class="ak-qty-btn qte-increase_{{ $id }}" onclick="increaseValue({{ $id }})">
                                        <i class="far fa-plus"></i>
                                    </button>
                                </div>
                                {{-- Total --}}
                                <div class="ak-cart-item-total">
                                    <span id="totalPriceQty{{ $id }}">{{ number_format($itemTotal) }}</span> FCFA
                                </div>
                                {{-- Remove --}}
                                <a href="#" class="ak-cart-remove remove-from-cart" data-id="{{ $id }}" title="Retirer">
                                    <i class="fal fa-trash-alt"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Sidebar summary --}}
                <div class="col-lg-4">
                    <div class="ak-cart-sidebar">
                        <h3 class="ak-sidebar-title">Récapitulatif</h3>

                        <div class="ak-sidebar-row">
                            <span>Sous-total</span>
                            <span class="sousTotal" style="font-weight:700;color:#1a1a1a;">
                                {{ number_format($sousTotal, 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        <div class="ak-sidebar-row">
                            <span>Livraison</span>
                            <span style="color:#888;font-size:.8rem;">Calculée à l'étape suivante</span>
                        </div>

                        <div class="ak-sidebar-row total-row">
                            <span>Total estimé</span>
                            <span class="val">{{ number_format($sousTotal, 0, ',', ' ') }} FCFA</span>
                        </div>

                        <div class="ak-sidebar-actions">
                            <a href="{{ route('liste-produit') }}" class="ak-sidebar-continue">
                                <i class="fas fa-arrow-left"></i> Continuer mes achats
                            </a>
                            <a href="{{ route('checkout') }}" class="ak-sidebar-checkout nextBtn" data-sousTotal="{{ $sousTotal }}">
                                Finaliser la commande <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        @else
            {{-- Panier vide --}}
            <div class="ak-empty-cart">
                <div class="ak-empty-cart-icon"><i class="fas fa-shopping-bag"></i></div>
                <h3>Votre panier est vide</h3>
                <p>Explorez notre menu et ajoutez vos plats préférés.</p>
                <a href="{{ route('liste-produit') }}" class="ak-empty-cart-cta">
                    <i class="fas fa-utensils"></i> Découvrir nos plats
                </a>
            </div>
        @endif
    </div>
</section>

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    function formatFCFA(amount) {
        return Number(amount).toLocaleString('fr-FR') + ' FCFA';
    }

    function updateSummary(sousTotal) {
        var formatted = formatFCFA(sousTotal);
        $('.sousTotal').html(formatted);
        $('.ak-sidebar-row.total-row .val').html(formatted);
        $('.nextBtn').attr('data-sousTotal', sousTotal);
    }

    $('.nextBtn').click(function(e) {
        var sousTotal = $(this).attr('data-sousTotal');
        localStorage.setItem('sousTotal', sousTotal);
    });

    function increaseValue(id) {
        var value = parseInt(document.getElementById(id).value);
        value = isNaN(value) ? 0 : value;
        value++;
        document.getElementById(id).value = value;
        if (value > 1) { $('.qte-decrease_' + id).attr('disabled', false); }
        $.ajax({
            url: '{{ route('update.cart') }}',
            method: "patch",
            data: { _token: '{{ csrf_token() }}', id: id, quantity: value },
            success: function(response) {
                $('.qty-input' + id).val(response.cart[id].quantity);
                $('#qte' + id).html(response.cart[id].quantity);
                var itemTotal = (response.cart[id].quantity * response.cart[id].price).toLocaleString('fr-FR');
                $('#totalPriceQty' + id).html(itemTotal);
                updateSummary(response.sousTotal);
                $('.badge').html(response.totalQte);
                Swal.fire({ toast:true, icon:'success', title:'Quantité mise à jour', animation:false, position:'top-right', background:'#3da108e0', iconColor:'#fff', color:'#fff', showConfirmButton:false, timer:1500, timerProgressBar:true });
            }
        });
    }

    function decreaseValue(id) {
        var value = parseInt(document.getElementById(id).value, 10);
        value = isNaN(value) ? 1 : value;
        if (value <= 1) return;
        value--;
        document.getElementById(id).value = value;
        if (value <= 1) { $('.qte-decrease_' + id).attr('disabled', true); }
        $.ajax({
            url: '{{ route('update.cart') }}',
            method: "patch",
            data: { _token: '{{ csrf_token() }}', id: id, quantity: value },
            success: function(response) {
                $('.qty-input' + id).val(response.cart[id].quantity);
                $('#qte' + id).html(response.cart[id].quantity);
                var itemTotal = (response.cart[id].quantity * response.cart[id].price).toLocaleString('fr-FR');
                $('#totalPriceQty' + id).html(itemTotal);
                updateSummary(response.sousTotal);
                $('.badge').html(response.totalQte);
                Swal.fire({ toast:true, icon:'success', title:'Panier mis à jour', animation:false, position:'top-right', background:'#3da108e0', iconColor:'#fff', color:'#fff', showConfirmButton:false, timer:1500, timerProgressBar:true });
            }
        });
    }

    $(".remove-from-cart").click(function(e) {
        e.preventDefault();
        var IdProduct = $(this).attr('data-id');
        Swal.fire({
            title: 'Retirer du panier',
            text: "Voulez-vous retirer ce plat du panier ?",
            width: '360px',
            showCancelButton: true,
            cancelButtonText: 'Annuler',
            confirmButtonColor: '#eb0029',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Oui, retirer'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('remove.from.cart') }}',
                    method: "DELETE",
                    data: { _token: '{{ csrf_token() }}', id: IdProduct },
                    success: function(response) {
                        updateSummary(response.sousTotal);
                        $('.badge').html(response.totalQte);
                        $('.quantityProduct').html(response.countCart);
                        $('#row_' + IdProduct).fadeOut(300, function() { $(this).remove(); });
                        Swal.fire({ toast:true, icon:'success', title:'Plat retiré du panier', animation:false, position:'top-right', background:'#3da108e0', iconColor:'#fff', color:'#fff', showConfirmButton:false, timer:1200, timerProgressBar:true });
                        if (response.countCart == 0) {
                            setTimeout(function() { window.location.href = "{{ route('panier') }}"; }, 1400);
                        }
                    }
                });
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

@endsection
