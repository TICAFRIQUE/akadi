@extends('site.layouts.app')
@section('title', 'Suivi — #{{ $order["code"] }}')

@section('content')

<div class="ak-breadcrumb">
    <div class="container">
        <h1 class="ak-breadcrumb-title">
            <span class="ak-breadcrumb-icon"><i class="fas fa-map-marker-alt"></i></span>
            Suivi de commande
        </h1>
        <ul class="ak-breadcrumb-nav">
            <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li><a href="{{ route('user-order') }}">Mes commandes</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li class="active">#{{ $order['code'] }}</li>
        </ul>
    </div>
</div>

<section class="track-section">
    <div class="container">

        {{-- ── Hero ── --}}
        <div class="track-hero">
            <div class="track-hero-left">
                <span class="track-label">Numéro de commande</span>
                <h1 class="track-code">#{{ $order['code'] }}</h1>
                <span class="track-date">
                    <i class="fas fa-calendar-alt"></i>
                    {{ \Carbon\Carbon::parse($order['created_at'])->isoFormat('dddd D MMMM YYYY [à] HH:mm') }}
                </span>
            </div>
            <div class="track-hero-right">
                @php
                    $statusMap = [
                        'attente'            => ['label' => $order->status_label ?? 'En attente',    'cls' => 'ts-attente'],
                        'precommande'        => ['label' => $order->status_label ?? 'Précommande',   'cls' => 'ts-precommande'],
                        'en_attente_acompte' => ['label' => $order->status_label ?? 'Acompte dû',    'cls' => 'ts-acompte'],
                        'confirmée'          => ['label' => $order->status_label ?? 'Confirmée',     'cls' => 'ts-confirmed'],
                        'en_cuisine'         => ['label' => $order->status_label ?? 'En cuisine',    'cls' => 'ts-cuisine'],
                        'cuisine_terminee'   => ['label' => $order->status_label ?? 'Prête',         'cls' => 'ts-ready'],
                        'en_livraison'       => ['label' => $order->status_label ?? 'En livraison',  'cls' => 'ts-delivery'],
                        'livrée'             => ['label' => $order->status_label ?? 'Livrée',        'cls' => 'ts-done'],
                        'annulée'            => ['label' => $order->status_label ?? 'Annulée',       'cls' => 'ts-cancelled'],
                    ];
                    $st = $statusMap[strtolower($order['status'])] ?? ['label' => $order['status'], 'cls' => 'ts-attente'];
                @endphp
                <span class="track-status-badge {{ $st['cls'] }}">{{ $st['label'] }}</span>
            </div>
        </div>

        <div class="track-grid">

            {{-- ══ COLONNE GAUCHE — Timeline ══ --}}
            <div class="track-left">
                <div class="track-block">
                    <div class="track-block-hd">
                        <i class="fas fa-route"></i>
                        <h2>Progression</h2>
                    </div>

                    @php
                        $s = strtolower($order['status']);
                        $steps = [
                            [
                                'icon'  => 'fa-shopping-bag',
                                'title' => 'Commande reçue',
                                'desc'  => 'Votre commande a été enregistrée',
                                'done'  => true,
                                'active'=> false,
                                'time'  => \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y H:i'),
                            ],
                            [
                                'icon'  => 'fa-check-circle',
                                'title' => 'Confirmée',
                                'desc'  => 'La commande a été validée par notre équipe',
                                'done'  => in_array($s, ['confirmée','en_cuisine','cuisine_terminee','en_livraison','livrée']),
                                'active'=> in_array($s, ['attente','en_attente_acompte','precommande']),
                                'time'  => null,
                            ],
                            [
                                'icon'  => 'fa-utensils',
                                'title' => 'En préparation',
                                'desc'  => 'Notre équipe prépare votre commande',
                                'done'  => in_array($s, ['en_cuisine','cuisine_terminee','en_livraison','livrée']),
                                'active'=> $s === 'confirmée',
                                'time'  => null,
                            ],
                            [
                                'icon'  => 'fa-box-open',
                                'title' => 'Prête',
                                'desc'  => 'Votre commande est emballée et prête',
                                'done'  => in_array($s, ['cuisine_terminee','en_livraison','livrée']),
                                'active'=> $s === 'en_cuisine',
                                'time'  => null,
                            ],
                            [
                                'icon'  => 'fa-shipping-fast',
                                'title' => 'En livraison',
                                'desc'  => 'Votre commande est en route',
                                'done'  => in_array($s, ['en_livraison','livrée']),
                                'active'=> $s === 'cuisine_terminee',
                                'time'  => null,
                            ],
                            [
                                'icon'  => 'fa-smile',
                                'title' => 'Livrée',
                                'desc'  => 'Commande remise, merci pour votre confiance !',
                                'done'  => $s === 'livrée',
                                'active'=> $s === 'en_livraison',
                                'time'  => null,
                            ],
                        ];
                    @endphp

                    <div class="tl-wrap">
                        @if ($s === 'annulée')
                            {{-- Cas annulée --}}
                            <div class="tl-item tl-done">
                                <div class="tl-line-wrap">
                                    <div class="tl-dot tl-dot-done"><i class="fas fa-shopping-bag"></i></div>
                                    <div class="tl-connector tl-connector-done"></div>
                                </div>
                                <div class="tl-content">
                                    <span class="tl-title">Commande reçue</span>
                                    <span class="tl-time">{{ \Carbon\Carbon::parse($order['created_at'])->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                            <div class="tl-item tl-cancelled">
                                <div class="tl-line-wrap">
                                    <div class="tl-dot tl-dot-cancel"><i class="fas fa-times"></i></div>
                                </div>
                                <div class="tl-content">
                                    <span class="tl-title">Commande annulée</span>
                                    @if($order['raison_annulation_cmd'])
                                        <span class="tl-desc cancel-reason">{{ $order['raison_annulation_cmd'] }}</span>
                                    @endif
                                </div>
                            </div>
                        @else
                            @foreach ($steps as $i => $step)
                            <div class="tl-item {{ $step['done'] ? 'tl-done' : ($step['active'] ? 'tl-active' : 'tl-pending') }}">
                                <div class="tl-line-wrap">
                                    <div class="tl-dot {{ $step['done'] ? 'tl-dot-done' : ($step['active'] ? 'tl-dot-active' : 'tl-dot-pending') }}">
                                        @if ($step['done'])
                                            <i class="fas fa-check"></i>
                                        @else
                                            <i class="fas {{ $step['icon'] }}"></i>
                                        @endif
                                    </div>
                                    @if ($i < count($steps) - 1)
                                        <div class="tl-connector {{ $step['done'] ? 'tl-connector-done' : '' }}"></div>
                                    @endif
                                </div>
                                <div class="tl-content">
                                    <span class="tl-title">{{ $step['title'] }}</span>
                                    <span class="tl-desc">{{ $step['desc'] }}</span>
                                    @if ($step['time'])
                                        <span class="tl-time"><i class="fas fa-clock"></i> {{ $step['time'] }}</span>
                                    @elseif ($step['done'])
                                        <span class="tl-time tl-time-done"><i class="fas fa-check-circle"></i> Effectué</span>
                                    @elseif ($step['active'])
                                        <span class="tl-time tl-time-active"><i class="fas fa-circle-notch fa-spin"></i> En cours…</span>
                                    @else
                                        <span class="tl-time tl-time-wait"><i class="fas fa-hourglass"></i> En attente</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Aide --}}
                <div class="track-block track-help">
                    <i class="fas fa-headset track-help-icon"></i>
                    <div>
                        <strong>Besoin d'aide ?</strong>
                        <p>Notre équipe est disponible pour toute question.</p>
                    </div>
                    <a href="tel:+2250758838338" class="track-help-btn">
                        <i class="fas fa-phone-alt"></i> Appeler
                    </a>
                </div>
            </div>

            {{-- ══ COLONNE DROITE — Infos + Articles ══ --}}
            <div class="track-right">

                {{-- KPIs --}}
                <div class="track-block">
                    <div class="track-block-hd">
                        <i class="fas fa-receipt"></i>
                        <h2>Récapitulatif</h2>
                    </div>
                    <div class="track-kpis">
                        <div class="track-kpi">
                            <span class="kpi-label">Articles</span>
                            <span class="kpi-val">{{ count($order['products']) }}</span>
                        </div>
                        <div class="track-kpi">
                            <span class="kpi-label">Livraison</span>
                            <span class="kpi-val">
                                @if($order['delivery_price'] == 0)
                                    <span class="kpi-free">Gratuit</span>
                                @else
                                    {{ number_format($order['delivery_price'], 0, ',', ' ') }} FCFA
                                @endif
                            </span>
                        </div>
                        @if(isset($order['discount']) && $order['discount'] > 0)
                        <div class="track-kpi">
                            <span class="kpi-label">Remise</span>
                            <span class="kpi-val kpi-discount">–{{ number_format($order['discount'], 0, ',', ' ') }} FCFA</span>
                        </div>
                        @endif
                    </div>
                    <div class="track-total-row">
                        <span>Total TTC</span>
                        <span class="track-total-amt">{{ number_format($order['total'], 0, ',', ' ') }} FCFA</span>
                    </div>
                    @if(isset($order['delivery_name']) && $order['delivery_name'])
                    <div class="track-delivery-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>{{ $order['delivery_name'] }}</span>
                    </div>
                    @endif
                </div>

                {{-- Articles --}}
                <div class="track-block">
                    <div class="track-block-hd">
                        <i class="fas fa-shopping-bag"></i>
                        <h2>Articles <span class="track-count-badge">{{ count($order['products']) }}</span></h2>
                    </div>
                    <div class="track-products">
                        @foreach ($order['products'] as $product)
                        <div class="track-product">
                            <a href="{{ route('detail-produit', $product['slug']) }}" class="track-product-img">
                                <img src="{{ $product->getFirstMediaUrl('product_image') }}" alt="{{ $product['title'] }}">
                            </a>
                            <div class="track-product-body">
                                <a href="{{ route('detail-produit', $product['slug']) }}" class="track-product-name">
                                    {{ $product['title'] }}
                                </a>
                                <span class="track-product-meta">
                                    {{ number_format($product['pivot']['unit_price'], 0, ',', ' ') }} FCFA × {{ $product['pivot']['quantity'] }}
                                </span>
                            </div>
                            <span class="track-product-total">
                                {{ number_format($product['pivot']['unit_price'] * $product['pivot']['quantity'], 0, ',', ' ') }} FCFA
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Retour --}}
                <a href="{{ route('user-order') }}" class="track-back-btn">
                    <i class="fas fa-arrow-left"></i> Retour à mes commandes
                </a>

            </div>
        </div>

    </div>
</section>

<style>
/* ── Layout ── */
.track-section {
    padding: 48px 0 90px;
    background: #f4f5f7;
    min-height: 70vh;
}
.track-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 24px;
    align-items: start;
}
@media (max-width: 992px) {
    .track-grid { grid-template-columns: 1fr; }
}

/* ── Hero ── */
.track-hero {
    background: #fff;
    border-radius: 14px;
    padding: 24px 28px;
    margin-bottom: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}
.track-hero-left { display: flex; flex-direction: column; gap: 5px; }
.track-label {
    font-size: .72rem;
    font-weight: 700;
    color: #aaa;
    text-transform: uppercase;
    letter-spacing: .8px;
}
.track-code {
    font-size: 1.8rem;
    font-weight: 800;
    color: #1a1a1a;
    margin: 0;
    letter-spacing: -.2px;
}
.track-date {
    font-size: .8rem;
    color: #999;
    display: flex;
    align-items: center;
    gap: 6px;
}
.track-date i { color: var(--theme-color, #e74c3c); }
.track-status-badge {
    font-size: .82rem;
    font-weight: 700;
    padding: 8px 20px;
    border-radius: 20px;
    white-space: nowrap;
}
.ts-attente      { background: #e7f3ff; color: #0066cc; }
.ts-precommande  { background: #f3e7f9; color: #9333ea; }
.ts-acompte      { background: #fff7ed; color: #ea580c; }
.ts-confirmed    { background: #e6ffed; color: #00b33c; }
.ts-cuisine      { background: #e0f2fe; color: #0284c7; }
.ts-ready        { background: #e3f2fd; color: #1976d2; }
.ts-delivery     { background: #e2e8f0; color: #475569; }
.ts-done         { background: #dcfce7; color: #15803d; }
.ts-cancelled    { background: #ffebee; color: #dc3545; }

/* ── Blocks ── */
.track-block {
    background: #fff;
    border-radius: 14px;
    padding: 22px 24px;
    margin-bottom: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.track-block-hd {
    display: flex;
    align-items: center;
    gap: 9px;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f0f0;
}
.track-block-hd i { color: var(--theme-color, #e74c3c); font-size: 1rem; }
.track-block-hd h2 {
    font-size: .95rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.track-count-badge {
    background: var(--theme-color, #e74c3c);
    color: #fff;
    font-size: .68rem;
    padding: 2px 7px;
    border-radius: 20px;
}

/* ── Timeline ── */
.tl-wrap { display: flex; flex-direction: column; }
.tl-item {
    display: flex;
    gap: 16px;
    padding-bottom: 4px;
}
.tl-line-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex-shrink: 0;
}
.tl-dot {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .8rem;
    flex-shrink: 0;
    z-index: 1;
}
.tl-dot-done   { background: var(--theme-color, #e74c3c); color: #fff; }
.tl-dot-active {
    background: #fff;
    border: 2.5px solid var(--theme-color, #e74c3c);
    color: var(--theme-color, #e74c3c);
    box-shadow: 0 0 0 5px rgba(231,76,60,.1);
    animation: tl-pulse 2s infinite;
}
.tl-dot-pending { background: #f0f0f0; border: 2px solid #ddd; color: #ccc; }
.tl-dot-cancel  { background: #dc3545; color: #fff; }
@keyframes tl-pulse {
    0%, 100% { box-shadow: 0 0 0 5px rgba(231,76,60,.1); }
    50%       { box-shadow: 0 0 0 9px rgba(231,76,60,.05); }
}
.tl-connector {
    width: 2px;
    flex: 1;
    min-height: 32px;
    background: #e0e0e0;
    margin: 4px 0;
}
.tl-connector-done { background: var(--theme-color, #e74c3c); }
.tl-content {
    flex: 1;
    padding: 6px 0 22px;
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.tl-title {
    font-size: .9rem;
    font-weight: 700;
    color: #1a1a1a;
}
.tl-done .tl-title   { color: var(--theme-color, #e74c3c); }
.tl-pending .tl-title { color: #bbb; }
.tl-desc {
    font-size: .78rem;
    color: #999;
    line-height: 1.4;
}
.tl-pending .tl-desc { color: #ccc; }
.tl-time {
    font-size: .75rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    color: #aaa;
    margin-top: 2px;
}
.tl-time-done   { color: var(--theme-color, #e74c3c); }
.tl-time-active { color: #f59e0b; }
.tl-time-wait   { color: #ccc; }
.cancel-reason {
    background: #ffebee;
    border: 1px solid #ffcdd2;
    border-radius: 6px;
    padding: 6px 10px;
    font-size: .8rem;
    color: #dc3545;
    margin-top: 4px;
}
.tl-cancelled .tl-title { color: #dc3545; }

/* ── Aide ── */
.track-help {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}
.track-help-icon {
    font-size: 1.6rem;
    color: var(--theme-color, #e74c3c);
    flex-shrink: 0;
}
.track-help > div { flex: 1; }
.track-help strong { font-size: .9rem; color: #1a1a1a; display: block; }
.track-help p { font-size: .8rem; color: #888; margin: 0; }
.track-help-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: var(--theme-color, #e74c3c);
    color: #fff;
    font-size: .82rem;
    font-weight: 700;
    padding: 9px 18px;
    border-radius: 8px;
    text-decoration: none;
    transition: filter .2s;
    white-space: nowrap;
}
.track-help-btn:hover { filter: brightness(.9); color: #fff; text-decoration: none; }

/* ── KPIs ── */
.track-kpis {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(100px,1fr));
    gap: 12px;
    margin-bottom: 14px;
}
.track-kpi {
    background: #f9f9f9;
    border-radius: 10px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.kpi-label {
    font-size: .7rem;
    font-weight: 700;
    color: #aaa;
    text-transform: uppercase;
    letter-spacing: .5px;
}
.kpi-val { font-size: .95rem; font-weight: 800; color: #1a1a1a; }
.kpi-free {
    font-size: .8rem;
    background: #d4edda;
    color: #28a745;
    padding: 2px 8px;
    border-radius: 10px;
    font-weight: 700;
}
.kpi-discount { color: #28a745; }
.track-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0 0;
    border-top: 2px solid #f0f0f0;
    font-size: .9rem;
    font-weight: 700;
    color: #1a1a1a;
}
.track-total-amt {
    color: var(--theme-color, #e74c3c);
    font-size: 1.1rem;
    font-weight: 800;
}
.track-delivery-info {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: .8rem;
    color: #888;
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px solid #f5f5f5;
}
.track-delivery-info i { color: var(--theme-color, #e74c3c); }

/* ── Produits ── */
.track-products { display: flex; flex-direction: column; gap: 10px; }
.track-product {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px;
    background: #fafafa;
    border: 1px solid #f0f0f0;
    border-radius: 10px;
    transition: border-color .2s;
}
.track-product:hover { border-color: #ddd; }
.track-product-img {
    width: 52px;
    height: 52px;
    min-width: 52px;
    border-radius: 8px;
    overflow: hidden;
    display: block;
}
.track-product-img img { width: 100%; height: 100%; object-fit: cover; }
.track-product-body { flex: 1; display: flex; flex-direction: column; gap: 2px; }
.track-product-name {
    font-size: .85rem;
    font-weight: 700;
    color: #1a1a1a;
    text-decoration: none;
    line-height: 1.3;
}
.track-product-name:hover { color: var(--theme-color, #e74c3c); }
.track-product-meta { font-size: .76rem; color: #999; }
.track-product-total {
    font-size: .85rem;
    font-weight: 800;
    color: #1a1a1a;
    white-space: nowrap;
}

/* ── Retour ── */
.track-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: .85rem;
    font-weight: 600;
    color: #555;
    border: 1.5px solid #ddd;
    padding: 11px 20px;
    border-radius: 10px;
    text-decoration: none;
    transition: all .2s;
    background: #fff;
    width: 100%;
    justify-content: center;
}
.track-back-btn:hover { border-color: #aaa; color: #1a1a1a; text-decoration: none; }
</style>

@endsection
