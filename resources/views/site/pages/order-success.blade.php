@extends('site.layouts.app')
@section('title', 'Commande confirmée')

@section('content')

<div class="ak-breadcrumb">
    <div class="container">
        <h1 class="ak-breadcrumb-title">
            <span class="ak-breadcrumb-icon"><i class="fas fa-check-circle"></i></span>
            Commande confirmée
        </h1>
        <ul class="ak-breadcrumb-nav">
            <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li class="active">Confirmation</li>
        </ul>
    </div>
</div>

<section class="confirm-section">
    <div class="container">
        <div class="confirm-wrap">

            {{-- ── Hero succès ── --}}
            <div class="confirm-hero">
                <div class="confirm-check">
                    <svg viewBox="0 0 52 52" class="check-svg">
                        <circle cx="26" cy="26" r="25" fill="none" class="check-circle"/>
                        <path d="M14 26 l8 8 l16-16" fill="none" class="check-tick"/>
                    </svg>
                </div>
                <h1 class="confirm-title">Commande confirmée !</h1>
                <p class="confirm-sub">Merci pour votre confiance. Votre commande est bien enregistrée.</p>

                <div class="confirm-order-num">
                    <span class="num-label">Numéro de commande</span>
                    <span class="num-value">#{{ $order->id }}</span>
                </div>

                @if($order->payment_status === 'completed')
                    <span class="confirm-status status-ok">
                        <i class="fas fa-check-circle"></i> Paiement confirmé
                    </span>
                @elseif($order->payment_status === 'pending')
                    <span class="confirm-status status-pending">
                        <i class="fas fa-clock"></i> Paiement en vérification
                    </span>
                @endif
            </div>

            {{-- ── Suivi étapes ── --}}
            <div class="confirm-steps">
                <div class="cs-item cs-done">
                    <div class="cs-dot"><i class="fas fa-check"></i></div>
                    <div class="cs-info">
                        <strong>Commande passée</strong>
                        <small>{{ $order->created_at->format('d/m/Y à H:i') }}</small>
                    </div>
                </div>
                <div class="cs-line {{ $order->payment_status === 'completed' ? 'cs-done-line' : '' }}"></div>
                <div class="cs-item {{ $order->payment_status === 'completed' ? 'cs-done' : 'cs-pending' }}">
                    <div class="cs-dot">
                        @if($order->payment_status === 'completed')
                            <i class="fas fa-check"></i>
                        @else
                            <i class="fas fa-clock"></i>
                        @endif
                    </div>
                    <div class="cs-info">
                        <strong>Paiement</strong>
                        <small>{{ $order->payment_status === 'completed' ? 'Confirmé' : 'En attente' }}</small>
                    </div>
                </div>
                <div class="cs-line"></div>
                <div class="cs-item">
                    <div class="cs-dot"><i class="fas fa-utensils"></i></div>
                    <div class="cs-info">
                        <strong>Préparation</strong>
                        <small>Bientôt</small>
                    </div>
                </div>
                <div class="cs-line"></div>
                <div class="cs-item">
                    <div class="cs-dot"><i class="fas fa-shipping-fast"></i></div>
                    <div class="cs-info">
                        <strong>Livraison</strong>
                        <small>
                            @php
                                try {
                                    echo preg_match('/^\d{4}-\d{2}-\d{2}/', $order->delivery_planned)
                                        ? \Carbon\Carbon::parse($order->delivery_planned)->format('d/m/Y')
                                        : ($order->delivery_planned ?? 'À définir');
                                } catch (\Exception $e) {
                                    echo $order->delivery_planned ?? 'À définir';
                                }
                            @endphp
                        </small>
                    </div>
                </div>
            </div>

            {{-- ── Infos & Résumé ── --}}
            <div class="confirm-grid">

                {{-- Détails livraison --}}
                <div class="confirm-block">
                    <div class="confirm-block-hd">
                        <i class="fas fa-truck"></i>
                        <h2>Livraison</h2>
                    </div>
                    <div class="confirm-rows">
                        <div class="confirm-row">
                            <span class="cr-label"><i class="fas fa-calendar-alt"></i> Date commande</span>
                            <span class="cr-val">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="confirm-row">
                            <span class="cr-label"><i class="fas fa-clock"></i> Livraison prévue</span>
                            <span class="cr-val">
                                @php
                                    try {
                                        echo preg_match('/^\d{4}-\d{2}-\d{2}/', $order->delivery_planned)
                                            ? \Carbon\Carbon::parse($order->delivery_planned)->format('d/m/Y H:i')
                                            : ($order->delivery_planned ?? '—');
                                    } catch (\Exception $e) {
                                        echo $order->delivery_planned ?? '—';
                                    }
                                @endphp
                            </span>
                        </div>
                        @if($order->delivery_name)
                        <div class="confirm-row">
                            <span class="cr-label"><i class="fas fa-map-marker-alt"></i> Zone</span>
                            <span class="cr-val">{{ $order->delivery_name }}</span>
                        </div>
                        @endif
                        <div class="confirm-row">
                            <span class="cr-label"><i class="fas fa-wallet"></i> Paiement</span>
                            <span class="cr-val">{{ $order->paymentMethod->nom ?? 'N/A' }}</span>
                        </div>
                        @if($order->note)
                        <div class="confirm-row">
                            <span class="cr-label"><i class="fas fa-sticky-note"></i> Note</span>
                            <span class="cr-val">{{ $order->note }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Résumé financier --}}
                <div class="confirm-block">
                    <div class="confirm-block-hd">
                        <i class="fas fa-receipt"></i>
                        <h2>Récapitulatif</h2>
                    </div>
                    <div class="confirm-rows">
                        <div class="confirm-row">
                            <span class="cr-label">Sous-total</span>
                            <span class="cr-val">{{ number_format($order->subtotal, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="confirm-row">
                            <span class="cr-label">Livraison</span>
                            <span class="cr-val">
                                @if($order->delivery_price == 0)
                                    <span class="tag-free">Gratuit</span>
                                @else
                                    {{ number_format($order->delivery_price, 0, ',', ' ') }} FCFA
                                @endif
                            </span>
                        </div>
                        @if($order->discount > 0)
                        <div class="confirm-row">
                            <span class="cr-label">Remise</span>
                            <span class="cr-val cr-discount">–{{ number_format($order->discount, 0, ',', ' ') }} FCFA</span>
                        </div>
                        @endif
                        <div class="confirm-divider"></div>
                        <div class="confirm-row confirm-total">
                            <span>Total</span>
                            <span class="cr-total-amt">{{ number_format($order->total, 0, ',', ' ') }} FCFA</span>
                        </div>
                        @if($order->acompte > 0)
                        <div class="confirm-row">
                            <span class="cr-label cr-paid"><i class="fas fa-check-circle"></i> Payé</span>
                            <span class="cr-val cr-paid">{{ number_format($order->acompte, 0, ',', ' ') }} FCFA</span>
                        </div>
                        @endif
                        @if($order->solde_restant > 0)
                        <div class="confirm-row">
                            <span class="cr-label cr-remain">Restant dû</span>
                            <span class="cr-val cr-remain">{{ number_format($order->solde_restant, 0, ',', ' ') }} FCFA</span>
                        </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ── Produits commandés ── --}}
            <div class="confirm-block">
                <div class="confirm-block-hd">
                    <i class="fas fa-shopping-bag"></i>
                    <h2>Articles commandés <span class="items-count">{{ $order->products->count() }}</span></h2>
                </div>
                <div class="confirm-products">
                    @foreach ($order->products as $product)
                    <div class="cp-item">
                        <div class="cp-img">
                            @if($product->media->isNotEmpty())
                                <img src="{{ $product->media[0]->getUrl() }}" alt="{{ $product->title }}">
                            @else
                                <div class="cp-img-placeholder"><i class="fas fa-utensils"></i></div>
                            @endif
                        </div>
                        <div class="cp-body">
                            <span class="cp-name">{{ $product->title }}</span>
                            <span class="cp-meta">{{ number_format($product->pivot->unit_price, 0, ',', ' ') }} FCFA × {{ $product->pivot->quantity }}</span>
                        </div>
                        <span class="cp-total">{{ number_format($product->pivot->total, 0, ',', ' ') }} FCFA</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ── Actions ── --}}
            <div class="confirm-actions">
                <a href="{{ route('user-order') }}" class="cta-primary">
                    <i class="fas fa-list-alt"></i> Mes commandes
                </a>
                <a href="{{ route('page-acceuil') }}" class="cta-ghost">
                    <i class="fas fa-home"></i> Accueil
                </a>
            </div>

        </div>
    </div>
</section>

<style>
/* ── Layout ── */
.confirm-section {
    padding: 50px 0 90px;
    background: #f4f5f7;
}
.confirm-wrap {
    max-width: 780px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* ── Hero ── */
.confirm-hero {
    background: #fff;
    border-radius: 16px;
    padding: 44px 32px 36px;
    text-align: center;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.confirm-check {
    width: 90px;
    height: 90px;
    margin: 0 auto 22px;
}
.check-svg { width: 90px; height: 90px; }
.check-circle {
    stroke: var(--theme-color, #e74c3c);
    stroke-width: 2.5;
    stroke-dasharray: 166;
    stroke-dashoffset: 166;
    animation: draw-circle .6s cubic-bezier(.65,0,.45,1) forwards;
}
.check-tick {
    stroke: var(--theme-color, #e74c3c);
    stroke-width: 3.5;
    stroke-linecap: round;
    stroke-linejoin: round;
    stroke-dasharray: 48;
    stroke-dashoffset: 48;
    animation: draw-tick .4s cubic-bezier(.65,0,.45,1) .6s forwards;
}
@keyframes draw-circle { to { stroke-dashoffset: 0; } }
@keyframes draw-tick   { to { stroke-dashoffset: 0; } }

.confirm-title {
    font-size: 2rem;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 8px;
    letter-spacing: -.3px;
}
.confirm-sub {
    color: #777;
    font-size: .97rem;
    margin-bottom: 24px;
}
.confirm-order-num {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    background: #fdf2f2;
    border: 1.5px solid rgba(231,76,60,.2);
    border-radius: 12px;
    padding: 12px 36px;
    margin-bottom: 16px;
}
.num-label {
    font-size: .75rem;
    font-weight: 600;
    color: #aaa;
    text-transform: uppercase;
    letter-spacing: .8px;
}
.num-value {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--theme-color, #e74c3c);
    letter-spacing: .5px;
}
.confirm-status {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: .85rem;
    font-weight: 700;
    padding: 7px 18px;
    border-radius: 20px;
}
.status-ok { background: #d4edda; color: #28a745; }
.status-pending { background: #fff3cd; color: #856404; }

/* ── Étapes ── */
.confirm-steps {
    display: flex;
    align-items: flex-start;
    gap: 0;
    background: #fff;
    border-radius: 14px;
    padding: 24px 20px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    overflow-x: auto;
}
.cs-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    flex: 1;
    min-width: 72px;
    text-align: center;
}
.cs-dot {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    border: 2px solid #ddd;
    background: #f9f9f9;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .8rem;
    color: #bbb;
    flex-shrink: 0;
}
.cs-item.cs-done .cs-dot {
    background: var(--theme-color, #e74c3c);
    border-color: var(--theme-color, #e74c3c);
    color: #fff;
}
.cs-item.cs-pending .cs-dot {
    border-color: #ffc107;
    color: #ffc107;
    background: #fff;
}
.cs-info strong {
    display: block;
    font-size: .8rem;
    font-weight: 700;
    color: #333;
}
.cs-item.cs-done .cs-info strong { color: var(--theme-color, #e74c3c); }
.cs-info small { font-size: .72rem; color: #999; }
.cs-line {
    flex: 1;
    height: 2px;
    background: #e0e0e0;
    margin: 19px 4px 0;
    flex-shrink: 0;
    min-width: 20px;
}
.cs-done-line { background: var(--theme-color, #e74c3c); }

/* ── Grid infos ── */
.confirm-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
@media (max-width: 640px) {
    .confirm-grid { grid-template-columns: 1fr; }
}

/* ── Blocks ── */
.confirm-block {
    background: #fff;
    border-radius: 14px;
    padding: 22px 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.confirm-block-hd {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f0f0;
}
.confirm-block-hd i {
    color: var(--theme-color, #e74c3c);
    font-size: 1rem;
}
.confirm-block-hd h2 {
    font-size: .95rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.items-count {
    background: var(--theme-color, #e74c3c);
    color: #fff;
    font-size: .7rem;
    padding: 2px 7px;
    border-radius: 20px;
}

/* ── Rows ── */
.confirm-rows { display: flex; flex-direction: column; }
.confirm-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    padding: 9px 0;
    border-bottom: 1px solid #f5f5f5;
    font-size: .87rem;
}
.confirm-row:last-child { border-bottom: none; }
.cr-label {
    color: #777;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 0;
}
.cr-label i { color: var(--theme-color, #e74c3c); font-size: .8rem; }
.cr-val { font-weight: 600; color: #1a1a1a; text-align: right; }
.cr-discount { color: #28a745; }
.cr-paid { color: #28a745; }
.cr-remain { color: #f59e0b; }
.confirm-divider {
    height: 2px;
    background: linear-gradient(to right, var(--theme-color,#e74c3c), #ff8a65);
    border-radius: 2px;
    margin: 6px 0;
}
.confirm-total {
    font-weight: 700;
    font-size: .95rem;
    color: #1a1a1a;
    border-bottom: none !important;
    padding-top: 12px;
}
.cr-total-amt {
    color: var(--theme-color, #e74c3c);
    font-size: 1.1rem;
    font-weight: 800;
}
.tag-free {
    background: #d4edda;
    color: #28a745;
    font-size: .72rem;
    font-weight: 700;
    padding: 2px 9px;
    border-radius: 20px;
}

/* ── Produits ── */
.confirm-products { display: flex; flex-direction: column; gap: 10px; }
.cp-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px;
    background: #fafafa;
    border: 1px solid #f0f0f0;
    border-radius: 10px;
    transition: border-color .2s;
}
.cp-item:hover { border-color: #ddd; }
.cp-img {
    width: 62px;
    height: 62px;
    min-width: 62px;
    border-radius: 8px;
    overflow: hidden;
}
.cp-img img { width: 100%; height: 100%; object-fit: cover; }
.cp-img-placeholder {
    width: 100%;
    height: 100%;
    background: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #bbb;
    font-size: 1.3rem;
}
.cp-body { flex: 1; display: flex; flex-direction: column; gap: 4px; }
.cp-name { font-weight: 700; font-size: .9rem; color: #1a1a1a; }
.cp-meta { font-size: .8rem; color: #888; }
.cp-total { font-weight: 800; font-size: .95rem; color: var(--theme-color, #e74c3c); white-space: nowrap; }

/* ── Actions ── */
.confirm-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}
.cta-primary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--theme-color, #e74c3c);
    color: #fff;
    font-weight: 700;
    font-size: .95rem;
    padding: 14px 28px;
    border-radius: 10px;
    text-decoration: none;
    transition: all .2s;
    letter-spacing: .2px;
}
.cta-primary:hover { filter: brightness(.9); transform: translateY(-1px); color: #fff; text-decoration: none; }
.cta-ghost {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    color: #555;
    font-weight: 600;
    font-size: .95rem;
    padding: 14px 28px;
    border-radius: 10px;
    border: 1.5px solid #ddd;
    text-decoration: none;
    transition: all .2s;
}
.cta-ghost:hover { border-color: #aaa; color: #1a1a1a; text-decoration: none; }
@media (max-width: 480px) {
    .confirm-actions { flex-direction: column; }
    .cta-primary, .cta-ghost { justify-content: center; }
    .confirm-title { font-size: 1.5rem; }
}
</style>

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        localStorage.removeItem('sousTotal');
        localStorage.removeItem('code-promo');
        localStorage.removeItem('remiseValue');
        localStorage.removeItem('couponId');
    });
</script>
@endif

@endsection
