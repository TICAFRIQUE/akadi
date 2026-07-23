@extends('site.layouts.app')
@section('title', 'Finaliser ma commande')

@section('content')

{{-- Breadcrumb --}}
<div class="ak-breadcrumb">
    <div class="container">
        <h1 class="ak-breadcrumb-title">
            <span class="ak-breadcrumb-icon"><i class="fas fa-clipboard-list"></i></span>
            Finaliser ma commande
        </h1>
        <ul class="ak-breadcrumb-nav">
            <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li><a href="{{ route('panier') }}">Panier</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li class="active">Commande</li>
        </ul>
    </div>
</div>

@include('admin.components.validationMessage')

<section class="checkout-section">
    <div class="container">

        @if (session('cart'))

        @php $sousTotal = 0; @endphp

        <div class="checkout-grid">

            {{-- ══════════════ COLONNE GAUCHE — Articles ══════════════ --}}
            <div class="checkout-left">

                <div class="checkout-block">
                    <div class="checkout-block-header">
                        <i class="fas fa-shopping-bag"></i>
                        <h2>Mon panier <span class="cart-count quantityProduct">{{ count((array) session('cart')) }}</span></h2>
                    </div>

                    <div class="cart-items">
                        @foreach (session('cart') as $id => $details)
                            @php $sousTotal += $details['price'] * $details['quantity']; @endphp
                            @php $lineTotal = $details['price'] * $details['quantity']; @endphp

                            <div class="cart-item" id="row_{{ $id }}">
                                <a href="{{ route('detail-produit', $details['slug']) }}" class="cart-item-img-wrap">
                                    <img src="{{ $details['image'] }}" alt="{{ $details['title'] }}" class="cart-item-img">
                                </a>
                                <div class="cart-item-body">
                                    <a href="{{ route('detail-produit', $details['slug']) }}" class="cart-item-name">
                                        {{ $details['title'] }}
                                    </a>
                                    <div class="cart-item-meta">
                                        <span class="cart-item-price">{{ number_format($details['price'], 0, ',', ' ') }} FCFA</span>
                                        <span class="cart-item-sep">×</span>
                                        <span class="cart-item-qty">{{ $details['quantity'] }}</span>
                                    </div>
                                    <div class="cart-item-total">
                                        <span id="totalPriceQty{{ $id }}">{{ number_format($lineTotal, 0, ',', ' ') }}</span> FCFA
                                    </div>

                                    {{-- Coupon produit --}}
                                    @if ($product_coupon)
                                        @foreach ($product_coupon as $item)
                                            @if (in_array($id, [$item['coupon'][0]['pivot']['product_id']]))
                                                <div class="cart-coupon-row">
                                                    <span class="coupon-tag"><i class="fas fa-tag"></i> {{ $item['coupon'][0]['code'] }}</span>
                                                    <input type="hidden" name="coupon" value="{{ $item['coupon'][0]['code'] }}">
                                                    <button
                                                        role="button"
                                                        product-id="{{ $id }}"
                                                        data-total="{{ $lineTotal }}"
                                                        data-sousTotal="{{ $sousTotal }}"
                                                        data-pourcentage="{{ $item['coupon'][0]['pourcentage_coupon'] }}"
                                                        data-code="{{ $item['coupon'][0]['code'] }}"
                                                        class="btn-coupon-apply applyCouponBtn"
                                                        id="btnApply_{{ $id }}">
                                                        –{{ $item['coupon'][0]['pourcentage_coupon'] }}% Appliquer
                                                    </button>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <a href="{{ route('liste-produit') }}" class="btn-continue">
                        <i class="fas fa-arrow-left"></i> Continuer mes achats
                    </a>
                </div>

            </div>

            {{-- ══════════════ COLONNE DROITE — Récap & Livraison ══════════════ --}}
            <div class="checkout-right">

                {{-- Client --}}
                <div class="checkout-block client-block">
                    <div class="client-avatar"><i class="fas fa-user"></i></div>
                    <div class="client-info">
                        <span class="client-name">{{ Auth::user()->name }}</span>
                        <span class="client-phone"><i class="fas fa-phone-alt"></i> {{ Auth::user()->phone }}</span>
                        @if (Auth::user()->email)
                            <span class="client-email"><i class="fas fa-envelope"></i> {{ Auth::user()->email }}</span>
                        @endif
                    </div>
                </div>

                {{-- Coupon --}}
                <div class="checkout-block">
                    <div class="coupon-toggle">
                        <i class="fas fa-tag"></i>
                        <span>Vous avez un code promo ?</span>
                        <div class="coupon-btns">
                            <button class="ouiCoupon coupon-btn-yes">Oui</button>
                            <button class="nonCoupon coupon-btn-no">Non</button>
                        </div>
                    </div>
                    <div class="divInputCoupon coupon-input-wrap" style="display:none">
                        <input type="text" id="codeCoupon" name="coupon" placeholder="Code promo" class="coupon-input">
                        <button class="applyCoupon coupon-apply-btn">Appliquer</button>
                    </div>
                    <div class="couponMessage" style="display:none"></div>
                </div>

                {{-- Livraison --}}
                <div class="checkout-block">
                    <div class="checkout-block-header">
                        <i class="fas fa-truck"></i>
                        <h2>Livraison</h2>
                    </div>

                    <div class="field-group">
                        <label class="field-label">Mode de livraison</label>
                        <select class="field-select delivery_mode">
                            <option disabled selected value="">Choisir un mode</option>
                            <option value="Livraison Yango Moto" tag="yango">🛵 Livraison Yango Moto</option>
                            <option value="Je passe récupérer" tag="recuperer">🏪 Je passe récupérer</option>
                        </select>
                    </div>

                    <div class="field-group" id="delivery-zone-wrap" style="display:none">
                        <label class="field-label">Zone de livraison</label>
                        <select class="field-select delivery">
                            <option disabled selected value="">Choisir une zone</option>
                            @foreach ($delivery as $item)
                                <option value="{{ $item['id'] }}">
                                    {{ $item['zone'] }} — {{ $item['tarif'] }} FCFA
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field-group" id="address" style="display:none">
                        <label class="field-label">Préciser le lieu exact</label>
                        <input type="text" name="address" class="field-input" placeholder="Ex : Cocody Riviera 3, immeuble…">
                    </div>

                    <div class="field-group" id="address_yango" style="display:none">
                        <label class="field-label">Adresse de destination (rue)</label>
                        <input type="text" name="address_yango" class="field-input" placeholder="Ex : Rue K14, Marcory">
                    </div>

                    <div class="field-group">
                        <label class="field-label">Note pour votre commande <span class="field-optional">(Optionnel)</span></label>
                        <textarea name="note" id="note" class="field-textarea" rows="3" placeholder="Ex : Ne pas mettre de sel…"></textarea>
                    </div>

                    {{-- Type commande --}}
                    <div class="field-group">
                        <label class="field-label">Type de commande</label>
                        <div class="type-cmd-toggle">
                            <label class="type-cmd-option">
                                <input type="radio" name="type_commande" value="cmd_normal" class="typeCmd" checked>
                                <span>
                                    <i class="fas fa-bolt"></i>
                                    <strong>Immédiate</strong>
                                    <small>Livraison dans les plus brefs délais</small>
                                </span>
                            </label>
                            <label class="type-cmd-option">
                                <input type="radio" name="type_commande" value="cmd_precommande" class="typeCmd">
                                <span>
                                    <i class="fas fa-calendar-alt"></i>
                                    <strong>Précommande</strong>
                                    <small>Choisissez une date ultérieure</small>
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="field-group" id="date_precommande_wrap" style="display:none">
                        <label class="field-label">Date et heure souhaitées</label>
                        <input type="text" class="field-input datetimepicker" name="date_precommande" id="date_precommande" placeholder="Choisir une date" readonly>
                    </div>

                    <div class="delivery-date-badge" id="date_livraison_wrap" style="display:none">
                        <i class="fas fa-calendar-check"></i>
                        <span id="date_livraison"></span>
                    </div>
                </div>

                {{-- Récapitulatif prix --}}
                <div class="checkout-block price-summary">
                    <div class="checkout-block-header">
                        <i class="fas fa-receipt"></i>
                        <h2>Récapitulatif</h2>
                    </div>

                    <div class="price-row">
                        <span>Sous-total</span>
                        <span class="sousTotal" data-subTotal="{{ $sousTotal }}">
                            {{ number_format($sousTotal, 0, ',', ' ') }} FCFA
                        </span>
                    </div>

                    <div class="price-row remiseDiv" style="display:none">
                        <span>Remise</span>
                        <span class="remise price-discount">0 FCFA</span>
                    </div>

                    <div class="price-row _delivery_price">
                        <span>Livraison <small class="delivery_name"></small></span>
                        <span class="delivery_price">—</span>
                    </div>

                    <div class="price-row price-total">
                        <span>Total TTC</span>
                        <span class="total_order">—</span>
                    </div>
                </div>

                {{-- CTA --}}
                @auth
                <div class="checkout-cta">
                    <button class="cta-btn confirmOrder">
                        <i class="fas fa-lock"></i>
                        Confirmer ma commande
                    </button>
                    <button class="cta-btn cta-loading btn_loading" disabled style="display:none">
                        <span class="cta-spin"></span>
                        Traitement en cours…
                    </button>
                </div>
                @endauth

            </div>
        </div>

        @else

        {{-- Panier vide --}}
        <div class="empty-cart">
            <div class="empty-cart-icon"><i class="fas fa-shopping-bag"></i></div>
            <h3>Votre panier est vide</h3>
            <p>Parcourez nos plats et ajoutez-en à votre panier.</p>
            <a href="{{ route('liste-produit') }}" class="th-btn">Voir les plats</a>
        </div>

        @endif

    </div>
</section>

{{-- ══════════════════ STYLES ══════════════════ --}}
<style>
/* ── Layout ── */
.checkout-section {
    padding: 50px 0 80px;
    background: #f4f5f7;
    min-height: 70vh;
}
.checkout-grid {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 24px;
    align-items: start;
}
@media (max-width: 992px) {
    .checkout-grid { grid-template-columns: 1fr; }
}

/* ── Blocks ── */
.checkout-block {
    background: #fff;
    border-radius: 14px;
    padding: 22px 24px;
    margin-bottom: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.checkout-block-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
    padding-bottom: 14px;
    border-bottom: 1px solid #f0f0f0;
}
.checkout-block-header i {
    color: var(--theme-color, #e74c3c);
    font-size: 1.1rem;
}
.checkout-block-header h2 {
    font-size: 1rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cart-count {
    background: var(--theme-color, #e74c3c);
    color: #fff;
    font-size: .72rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
}

/* ── Cart items ── */
.cart-items { display: flex; flex-direction: column; gap: 14px; margin-bottom: 20px; }
.cart-item {
    display: flex;
    gap: 14px;
    padding: 14px;
    border: 1px solid #f0f0f0;
    border-radius: 10px;
    background: #fafafa;
    transition: border-color .2s;
}
.cart-item:hover { border-color: #ddd; }
.cart-item-img-wrap { flex-shrink: 0; }
.cart-item-img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
}
.cart-item-body { flex: 1; display: flex; flex-direction: column; gap: 5px; }
.cart-item-name {
    font-weight: 600;
    font-size: .92rem;
    color: #1a1a1a;
    text-decoration: none;
    line-height: 1.3;
}
.cart-item-name:hover { color: var(--theme-color, #e74c3c); }
.cart-item-meta {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: .82rem;
    color: #777;
}
.cart-item-sep { color: #bbb; }
.cart-item-total {
    font-weight: 700;
    font-size: .95rem;
    color: var(--theme-color, #e74c3c);
}
.cart-coupon-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 4px;
    flex-wrap: wrap;
}
.coupon-tag {
    background: #fff3cd;
    color: #856404;
    font-size: .75rem;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 20px;
    border: 1px solid #ffc107;
}
.btn-coupon-apply {
    background: #1a1a1a;
    color: #fff;
    border: none;
    font-size: .75rem;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
    cursor: pointer;
    transition: background .2s;
}
.btn-coupon-apply:hover { background: var(--theme-color, #e74c3c); }
.btn-coupon-apply:disabled { opacity: .5; cursor: not-allowed; }

.btn-continue {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: .85rem;
    font-weight: 600;
    color: #555;
    text-decoration: none;
    padding: 9px 16px;
    border: 1.5px solid #ddd;
    border-radius: 8px;
    transition: all .2s;
}
.btn-continue:hover { border-color: #aaa; color: #1a1a1a; text-decoration: none; }

/* ── Client ── */
.client-block {
    display: flex;
    align-items: center;
    gap: 14px;
}
.client-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--theme-color,#e74c3c), #ff8a65);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.client-info { display: flex; flex-direction: column; gap: 2px; }
.client-name { font-weight: 700; font-size: .95rem; color: #1a1a1a; }
.client-phone, .client-email {
    font-size: .8rem;
    color: #777;
    display: flex;
    align-items: center;
    gap: 5px;
}

/* ── Coupon ── */
.coupon-toggle {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .88rem;
    color: #444;
    flex-wrap: wrap;
}
.coupon-toggle i { color: #f59e0b; }
.coupon-btns { display: flex; gap: 6px; margin-left: auto; }
.coupon-btn-yes, .coupon-btn-no {
    background: none;
    border: 1.5px solid #ddd;
    border-radius: 6px;
    padding: 4px 14px;
    font-size: .8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s;
}
.coupon-btn-yes:hover { border-color: #28a745; color: #28a745; }
.coupon-btn-no:hover { border-color: #aaa; color: #666; }
.coupon-input-wrap {
    display: flex;
    gap: 8px;
    margin-top: 12px;
}
.coupon-input {
    flex: 1;
    border: 1.5px solid #ddd;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: .88rem;
    outline: none;
    transition: border-color .2s;
}
.coupon-input:focus { border-color: var(--theme-color, #e74c3c); }
.coupon-apply-btn {
    background: #1a1a1a;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 10px 16px;
    font-size: .85rem;
    font-weight: 600;
    cursor: pointer;
    white-space: nowrap;
    transition: background .2s;
}
.coupon-apply-btn:hover { background: var(--theme-color, #e74c3c); }
.coupon-apply-btn:disabled { opacity: .5; cursor: not-allowed; }
.couponMessage {
    margin-top: 8px;
    font-size: .82rem;
    font-weight: 600;
    border-radius: 6px;
    padding: 6px 10px;
}

/* ── Form fields ── */
.field-group { margin-bottom: 14px; }
.field-label {
    display: block;
    font-size: .82rem;
    font-weight: 600;
    color: #444;
    margin-bottom: 6px;
}
.field-optional { font-weight: 400; color: #aaa; }
.field-select, .field-input, .field-textarea {
    width: 100%;
    border: 1.5px solid #e0e0e0;
    border-radius: 8px;
    padding: 11px 13px;
    font-size: .88rem;
    color: #1a1a1a;
    background: #fff;
    outline: none;
    transition: border-color .2s;
    font-family: inherit;
}
.field-select:focus, .field-input:focus, .field-textarea:focus {
    border-color: var(--theme-color, #e74c3c);
    box-shadow: 0 0 0 3px rgba(231,76,60,.08);
}
.field-textarea { resize: vertical; min-height: 80px; }

/* ── Type commande toggle ── */
.type-cmd-toggle { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.type-cmd-option { cursor: pointer; }
.type-cmd-option input[type="radio"] { display: none; }
.type-cmd-option span {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    padding: 12px 8px;
    border: 1.5px solid #e0e0e0;
    border-radius: 10px;
    text-align: center;
    transition: all .2s;
}
.type-cmd-option span i {
    font-size: 1.2rem;
    color: #aaa;
    margin-bottom: 2px;
}
.type-cmd-option span strong {
    font-size: .82rem;
    font-weight: 700;
    color: #333;
}
.type-cmd-option span small {
    font-size: .7rem;
    color: #999;
    line-height: 1.3;
}
.type-cmd-option input:checked + span {
    border-color: var(--theme-color, #e74c3c);
    background: #fff5f5;
    box-shadow: 0 0 0 3px rgba(231,76,60,.1);
}
.type-cmd-option input:checked + span i,
.type-cmd-option input:checked + span strong { color: var(--theme-color, #e74c3c); }

/* ── Date livraison badge ── */
.delivery-date-badge {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    background: #f0fff4;
    border: 1px solid #b2f2bb;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: .82rem;
    color: #276749;
    margin-top: 4px;
    min-height: 38px;
}
.delivery-date-badge i { margin-top: 2px; flex-shrink: 0; }

/* ── Prix summary ── */
.price-summary .checkout-block-header { margin-bottom: 14px; }
.price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 9px 0;
    font-size: .88rem;
    color: #555;
    border-bottom: 1px solid #f5f5f5;
}
.price-row:last-child { border-bottom: none; }
.price-row small { display: block; color: #aaa; font-size: .75rem; }
.price-discount { color: #28a745; font-weight: 600; }
.price-total {
    font-size: 1rem;
    font-weight: 700;
    color: #1a1a1a;
    padding-top: 12px;
    margin-top: 4px;
    border-top: 2px solid #f0f0f0;
    border-bottom: none;
}
.price-total span:last-child { color: var(--theme-color, #e74c3c); font-size: 1.1rem; }

/* ── CTA ── */
.checkout-cta { margin-top: 4px; }
.cta-btn {
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all .2s;
    background: var(--theme-color, #e74c3c);
    color: #fff;
    letter-spacing: .3px;
}
.cta-btn:hover { filter: brightness(.92); transform: translateY(-1px); }
.cta-btn:disabled { opacity: .7; cursor: not-allowed; transform: none; filter: none; }
.cta-loading {
    background: #444;
    margin-top: 0;
}
.cta-spin {
    display: inline-block;
    width: 18px;
    height: 18px;
    border: 2px solid rgba(255,255,255,.35);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin .7s linear infinite;
    flex-shrink: 0;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* ── Empty cart ── */
.empty-cart {
    text-align: center;
    padding: 80px 20px;
    background: #fff;
    border-radius: 16px;
    max-width: 480px;
    margin: 0 auto;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.empty-cart-icon {
    font-size: 4rem;
    color: #e0e0e0;
    margin-bottom: 20px;
}
.empty-cart h3 { font-weight: 700; color: #1a1a1a; margin-bottom: 10px; }
.empty-cart p { color: #888; margin-bottom: 24px; }
</style>

{{-- ══════════════════ LIBS ══════════════════ --}}
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

{{-- ══════════════════ JS (logique métier inchangée) ══════════════════ --}}
<script>
    // cacher par defaut le input
    $('.divInputCoupon').hide();
    $('.remiseDiv').hide();

    $('.ouiCoupon').click(function(e) {
        e.preventDefault();
        $('.divInputCoupon').show();
        $('.remiseDiv').show();
    });
    $('.nonCoupon').click(function(e) {
        e.preventDefault();
        $('.divInputCoupon').hide();
        $('.remiseDiv').hide();
    });

    var prix_livraison = 0;
    var lieu_livraison = '';
    var type_cmd = 'cmd_normal';
    var dlp = '';

    var sousTotal = $('.sousTotal').attr('data-subTotal');
    localStorage.setItem('sousTotal', sousTotal);

    $('.applyCoupon').click(function(e) {
        e.preventDefault();
        var code_coupon = $('#codeCoupon').val().trim();
        if (code_coupon.length < 3) {
            $('.remiseDiv').hide();
            $('.couponMessage').text('').hide();
            return;
        }
        var sousTotal = parseFloat(localStorage.getItem('sousTotal'));
        $.ajax({
            type: "GET",
            url: "/check-coupon/" + code_coupon,
            dataType: "json",
            data: { sous_total: sousTotal },
            success: function(response) {
                if (response.coupon != null) {
                    $('.remiseDiv').show();
                    $('.couponMessage').text('✅ Coupon appliqué !').css({'color':'green','font-weight':'bold'}).show();
                }
                var couponId = response.coupon.id;
                var typeRemise = response.coupon.type_remise;
                var valeurRemise = response.coupon.valeur_remise;
                var sousTotal = parseFloat(localStorage.getItem('sousTotal'));
                var remise = typeRemise == 'pourcentage' ? (sousTotal * valeurRemise) / 100 : valeurRemise;
                $('.remise').html(parseInt(remise).toLocaleString() + ' FCFA');
                localStorage.setItem('remiseValue', parseInt(remise).toFixed(0));
                localStorage.setItem('couponId', couponId);
                var newSousTotal = sousTotal - remise;
                localStorage.setItem('sousTotal', newSousTotal);
                $('.total_order').html(parseInt(newSousTotal).toLocaleString() + ' FCFA');
                $('.applyCoupon').attr('disabled', true);
                $('.nonCoupon').hide();
            },
            error: function(response) {
                $('.remiseDiv').hide();
                $('.couponMessage').text('⚠️ ' + response.responseJSON.message).css({'color':'red','font-weight':'bold'}).show();
                localStorage.removeItem('couponId');
                localStorage.removeItem('remiseValue');
            }
        });
    });

    $('.applyCouponBtn').click(function(e) {
        e.preventDefault();
        var code_coupon = $(this).attr('data-code');
        localStorage.setItem('code-promo', code_coupon);
        var isNewSousTotal = localStorage.getItem('sousTotal');
        var productId = $(this).attr('product-id');
        var total = $(this).attr('data-total');
        var pourcentage = $(this).attr('data-pourcentage');
        var montant_reduction = parseFloat(total) * parseFloat(pourcentage / 100);
        var new_total = parseFloat(total) - parseFloat(montant_reduction);
        $('#totalPriceQty' + productId).html(parseInt(new_total).toLocaleString());
        $('#btnApply_' + productId).prop('disabled', true);
        var newSousTotal = isNewSousTotal - montant_reduction;
        localStorage.setItem('sousTotal', newSousTotal);
        $('.sousTotal').html(parseInt(newSousTotal).toLocaleString() + ' FCFA');
        $('.total_order').html(parseInt(newSousTotal).toLocaleString() + ' FCFA');
    });

    $('.delivery').change(function(e) {
        e.preventDefault();
        var deliveryId = $('.delivery option:selected').val();
        var subTotal = localStorage.getItem('sousTotal');
        $('#address').show(300);
        $.ajax({
            type: "GET",
            url: "/refresh-shipping/" + deliveryId,
            data: { sub_total: subTotal },
            dataType: "json",
            success: function(response) {
                $('.delivery_price').html(response.delivery_price + ' FCFA');
                $('.delivery_name').html('(' + response.delivery_name + ')');
                $('.total_order').html(response.total_price + ' FCFA');
                prix_livraison = response.delivery_price;
                lieu_livraison = response.delivery_name;
            }
        });
    });

    $('#address').hide();
    $('#address_yango').hide();
    $('#delivery-zone-wrap').hide();

    $('.delivery_mode').change(function(e) {
        e.preventDefault();
        var mode_livraison = $('.delivery_mode option:selected').attr('tag');
        var subTotal = localStorage.getItem('sousTotal');

        if (mode_livraison == 'domicile') {
            $('#delivery-zone-wrap').show(200);
            $('.delivery').val('');
            $('._delivery_price').show();
        } else {
            $('#delivery-zone-wrap').hide(100);
        }

        if (mode_livraison == 'yango') {
            $('#address_yango').show(200);
            $('.delivery_price').html('Frais à votre charge').css({'color':'#e74c3c','font-weight':'bold'});
            $('.total_order').html(parseInt(subTotal).toLocaleString() + ' FCFA');
            $('.delivery_name').html('');
            $('._delivery_price').show();
            $('#address').hide();
            prix_livraison = 0;
            lieu_livraison = '';
        } else {
            $('#address_yango').hide(100);
            $('.delivery_price').html('—');
        }

        if (mode_livraison == 'recuperer') {
            $('._delivery_price').hide();
            $('#address').hide();
            $('#address_yango').hide();
            $('#delivery-zone-wrap').hide();
            $('.total_order').html(parseInt(subTotal).toLocaleString() + ' FCFA');
            prix_livraison = 0;
            lieu_livraison = '';
        }
    });

    $('#address_yango').prop('required', false);
    $('#address').prop('required', false);

    $("#date_precommande_wrap").hide();

    function showImmediate() {
        var dt = new Date();
        var txt, key;
        if (dt.getHours() >= 17 || dt.getHours() < 8) {
            dt.setDate(dt.getDate() + 1); dt.setHours(10, 30, 0, 0);
            txt = 'Livraison prévue le ' + dt.toLocaleDateString('fr-FR') + ' à 10h30';
            key = dt.toISOString().split('T')[0] + ' 10:30:00';
        } else {
            dt.setMinutes(dt.getMinutes() + 90);
            txt = 'Livraison prévue le ' + dt.toLocaleString('fr-FR');
            key = dt.toISOString().replace('T',' ').split('.')[0];
        }
        $('#date_livraison').html(txt).data('date', key);
        $('#date_livraison_wrap').show(200);
    }

    showImmediate();

    $("input[name=type_commande]").on("click", function() {
        var typeCmd = $('input[name="type_commande"]:checked').val();
        type_cmd = typeCmd;
        if (typeCmd == 'cmd_precommande') {
            $("#date_precommande_wrap").show(200);
            $('#date_livraison_wrap').hide(100);
            $('#date_livraison').html('').removeData('date');
        } else {
            type_cmd = 'cmd_normal';
            $("#date_precommande_wrap").hide(100);
            $("#date_precommande").val('');
            showImmediate();
        }
    });

    $(".datetimepicker").each(function() {
        let dt = new Date();
        $(this).datetimepicker({
            showOtherMonths: true,
            selectOtherMonths: true,
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'yy-mm-dd',
            minDate: dt.setDate(dt.getDate() + 1),
            allowTimes: ['10:30','12:00','13:30','15:00','16:30','17:30'],
        });
    });

    // Le datetimepicker xdan retourne "YYYY/MM/DD HH:MM" (slashes par défaut)
    function onPrecommandeDateChange(val) {
        if (!val) return;
        var parts = val.split(' ');
        var datePart = parts[0] || '';
        var timePart = parts[1] || '';
        // Détecter le séparateur (/ ou -) et inverser en JJ/MM/AAAA
        var sep = datePart.indexOf('/') >= 0 ? '/' : '-';
        var dp = datePart.split(sep);
        var dateReadable = dp.length === 3 ? dp[2] + '/' + dp[1] + '/' + dp[0] : datePart;
        $('#date_livraison').html('Précommande prévue le ' + dateReadable + ' à ' + timePart);
        $('#date_livraison_wrap').show(200);
    }

    $('#date_precommande').on('change', function() {
        onPrecommandeDateChange($(this).val().trim());
    });

    $('.btn_loading').hide();
    $('.confirmOrder').click(function(e) {
        e.preventDefault();
        var deliveryId = $('.delivery').val();
        var address_yango = $('#address_yango').val();
        var address = $('#address').val();
        var delivery_mode = $('.delivery_mode').val();
        var note = $('#note').val();
        var date_precommande = $('#date_precommande').val();

        if (!delivery_mode) {
            Swal.fire({ toast:true, icon:'error', width:'100%', title:'Veuillez choisir un mode de livraison', position:'top', background:'#eb0029', iconColor:'#fff', color:'#fff', showConfirmButton:false, timer:5000, timerProgressBar:true });
        } else if ($('.delivery').is(':visible') && !deliveryId) {
            Swal.fire({ toast:true, icon:'error', width:'100%', title:'Veuillez choisir un lieu de livraison', position:'top', background:'#eb0029', iconColor:'#fff', color:'#fff', showConfirmButton:false, timer:5000, timerProgressBar:true });
        } else if ($('#address').is(':visible') && !address) {
            Swal.fire({ toast:true, icon:'error', width:'100%', title:'Veuillez préciser le lieu exact', position:'top', background:'#eb0029', iconColor:'#fff', color:'#fff', showConfirmButton:false, timer:5000, timerProgressBar:true });
        } else if ($('#address_yango').is(':visible') && !address_yango) {
            Swal.fire({ toast:true, icon:'error', width:'100%', title:'Veuillez préciser la destination', position:'top', background:'#eb0029', iconColor:'#fff', color:'#fff', showConfirmButton:false, timer:5000, timerProgressBar:true });
        } else if (type_cmd === 'cmd_precommande' && !date_precommande) {
            Swal.fire({ toast:true, icon:'error', width:'100%', title:'Veuillez choisir une date de précommande', position:'top', background:'#eb0029', iconColor:'#fff', color:'#fff', showConfirmButton:false, timer:5000, timerProgressBar:true });
        } else {
            $('.btn_loading').show();
            $('.confirmOrder').hide();

            var subTotal = localStorage.getItem('sousTotal') || 0;
            var code_promo = localStorage.getItem('code-promo') || 0;
            var total_order = parseFloat(subTotal) + parseFloat(prix_livraison);
            var type_commande = type_cmd;
            var remise = localStorage.getItem('remiseValue') || 0;
            var coupon_id = localStorage.getItem('couponId');

            function formatDateTime(date) {
                return date.getFullYear() + '-' +
                    String(date.getMonth()+1).padStart(2,'0') + '-' +
                    String(date.getDate()).padStart(2,'0') + ' ' +
                    String(date.getHours()).padStart(2,'0') + ':' +
                    String(date.getMinutes()).padStart(2,'0') + ':' +
                    String(date.getSeconds()).padStart(2,'0');
            }

            var delivery_planned, date_order;
            if (type_commande === 'cmd_precommande') {
                // datetimepicker xdan retourne "YYYY/MM/DD HH:MM"
                var rawDate = $('#date_precommande').val().trim();
                var rdParts = rawDate.split(' ');
                var rdDate  = rdParts[0] || '';   // "2026/07/25"
                var rdTime  = rdParts[1] || '10:30'; // "16:30"
                var sep = rdDate.indexOf('/') >= 0 ? '/' : '-';
                var dp  = rdDate.split(sep);      // ["2026","07","25"]
                // delivery_planned → format Carbon-parseable "YYYY-MM-DD HH:MM:SS"
                delivery_planned = dp[0] + '-' + dp[1] + '-' + dp[2] + ' ' + rdTime + ':00';
                // date_order → format attendu par le contrôleur "DD/MM/YYYY HH:MM:SS"
                date_order = dp[2] + '/' + dp[1] + '/' + dp[0] + ' ' + rdTime + ':00';
            } else {
                var dpNow = new Date();
                if (dpNow.getHours() >= 17 || dpNow.getHours() < 8) {
                    dpNow.setDate(dpNow.getDate() + 1); dpNow.setHours(10, 30, 0, 0);
                } else {
                    dpNow.setMinutes(dpNow.getMinutes() + 90);
                }
                delivery_planned = formatDateTime(dpNow);
                date_order = formatDateTime(new Date());
            }
            var data = { subTotal, address, address_yango, prix_livraison, lieu_livraison, delivery_mode, total_order, note, type_commande, delivery_planned, code_promo, coupon_id, remise, date_order };

            $.ajax({
                type: "GET",
                url: "save-order",
                data: { data },
                dataType: "json",
                success: function(response) {
                    if (response.status === 200) {
                        $('.btn_loading').hide();
                        let timerInterval;
                        Swal.fire({
                            title: 'Commande enregistrée !',
                            html: 'Redirection dans <b></b>s…',
                            timer: 2000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                                const b = Swal.getHtmlContainer().querySelector('b');
                                timerInterval = setInterval(() => { b.textContent = Math.ceil(Swal.getTimerLeft()/1000); }, 100);
                            },
                            willClose: () => clearInterval(timerInterval)
                        }).then(() => { if (response.redirect) window.location.href = response.redirect; });
                    }
                }
            });
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

@endsection
