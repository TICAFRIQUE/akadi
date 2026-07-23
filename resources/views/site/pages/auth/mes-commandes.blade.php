@extends('site.layouts.app')
@section('title', 'Mes commandes')

@section('content')

<div class="ak-breadcrumb">
    <div class="container">
        <h1 class="ak-breadcrumb-title">
            <span class="ak-breadcrumb-icon"><i class="fas fa-list-alt"></i></span>
            Mes commandes
            <span class="ak-breadcrumb-badge">{{ $orders->total() }}</span>
        </h1>
        <ul class="ak-breadcrumb-nav">
            <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li class="active">Historique</li>
        </ul>
    </div>
</div>

<section class="histo-section">
    <div class="container">

        @include('admin.components.validationMessage')

        @if ($orders->total() > 0)

        {{-- ── En-tête ── --}}
        <div class="histo-header">
            <div class="histo-header-left">
                <h1 class="histo-title">Historique</h1>
                <span class="histo-count">{{ $orders->total() }} commande{{ $orders->total() > 1 ? 's' : '' }}</span>
            </div>
            <a href="{{ route('liste-produit') }}" class="histo-new-btn">
                <i class="fas fa-plus"></i> Nouvelle commande
            </a>
        </div>

        {{-- ── Liste ── --}}
        <div class="histo-list">
            @foreach ($orders as $item)
            <div class="histo-card">

                {{-- Header --}}
                <div class="histo-card-hd">
                    <div class="histo-cmd-info">
                        <span class="histo-cmd-num">#{{ $item['code'] }}</span>
                        <span class="histo-cmd-date">
                            <i class="fas fa-calendar-alt"></i>
                            {{ \Carbon\Carbon::parse($item['created_at'])->isoFormat('D MMM YYYY [à] H:mm') }}
                        </span>
                    </div>
                    @php
                        $statusMap = [
                            'attente'             => ['label' => $item->status_label ?? 'En attente',   'cls' => 'st-attente'],
                            'precommande'         => ['label' => $item->status_label ?? 'Précommande',  'cls' => 'st-precommande'],
                            'en_attente_acompte'  => ['label' => $item->status_label ?? 'Acompte',      'cls' => 'st-acompte'],
                            'confirmée'           => ['label' => $item->status_label ?? 'Confirmée',    'cls' => 'st-confirmed'],
                            'en_cuisine'          => ['label' => $item->status_label ?? 'En cuisine',   'cls' => 'st-cuisine'],
                            'cuisine_terminee'    => ['label' => $item->status_label ?? 'Prête',        'cls' => 'st-ready'],
                            'en_livraison'        => ['label' => $item->status_label ?? 'En livraison', 'cls' => 'st-delivery'],
                            'livrée'              => ['label' => $item->status_label ?? 'Livrée',       'cls' => 'st-done'],
                            'annulée'             => ['label' => $item->status_label ?? 'Annulée',      'cls' => 'st-cancelled'],
                        ];
                        $st = $statusMap[strtolower($item['status'])] ?? ['label' => $item['status'], 'cls' => 'st-attente'];
                    @endphp
                    <span class="histo-badge {{ $st['cls'] }}">{{ $st['label'] }}</span>
                </div>

                {{-- Produits --}}
                <div class="histo-products">
                    @foreach ($item['products']->take(3) as $product)
                    <div class="histo-product">
                        <a href="{{ route('detail-produit', $product['slug']) }}" class="histo-product-img">
                            <img src="{{ $product->getFirstMediaUrl('product_image') }}" alt="{{ $product['title'] }}">
                        </a>
                        <div class="histo-product-body">
                            <a href="{{ route('detail-produit', $product['slug']) }}" class="histo-product-name">
                                {{ $product['title'] }}
                            </a>
                            <span class="histo-product-meta">
                                {{ number_format($product['pivot']['unit_price'], 0, ',', ' ') }} FCFA
                                &times; {{ $product['pivot']['quantity'] }}
                            </span>
                        </div>
                        <span class="histo-product-total">
                            {{ number_format($product['pivot']['unit_price'] * $product['pivot']['quantity'], 0, ',', ' ') }} FCFA
                        </span>
                    </div>
                    @endforeach

                    @if ($item['products']->count() > 3)
                    <div class="histo-more">
                        + {{ $item['products']->count() - 3 }} autre{{ $item['products']->count() - 3 > 1 ? 's' : '' }} article{{ $item['products']->count() - 3 > 1 ? 's' : '' }}
                    </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="histo-card-ft">
                    <div class="histo-totals">
                        @if ($item['delivery_price'] > 0)
                        <div class="histo-total-item">
                            <span class="ht-label">Livraison</span>
                            <span class="ht-val">{{ number_format($item['delivery_price'], 0, ',', ' ') }} FCFA</span>
                        </div>
                        @else
                        <div class="histo-total-item">
                            <span class="ht-label">Livraison</span>
                            <span class="ht-free">Gratuit</span>
                        </div>
                        @endif
                        <div class="histo-total-item histo-total-main">
                            <span class="ht-label">Total TTC</span>
                            <span class="ht-val ht-main">{{ number_format($item['total'], 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                    <div class="histo-actions">
                        <a href="{{ route('suivi-commande', $item['code']) }}" class="histo-btn histo-btn-ghost">
                            <i class="fas fa-map-marker-alt"></i> Suivre
                        </a>
                        <a href="{{ route('detail-produit', $item['products'][0]['slug'] ?? '#') }}" class="histo-btn histo-btn-primary">
                            <i class="fas fa-eye"></i> Détails
                        </a>
                    </div>
                </div>

                @include('site.sections.raison_annulation_cmd')
            </div>
            @endforeach
        </div>

        {{-- ── Pagination ── --}}
        @if ($orders->hasPages())
        <div class="histo-pagination">
            {{-- Précédent --}}
            @if ($orders->onFirstPage())
                <span class="hp-btn hp-disabled"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $orders->previousPageUrl() }}" class="hp-btn"><i class="fas fa-chevron-left"></i></a>
            @endif

            {{-- Pages --}}
            @foreach ($orders->getUrlRange(max(1, $orders->currentPage() - 2), min($orders->lastPage(), $orders->currentPage() + 2)) as $page => $url)
                @if ($page == $orders->currentPage())
                    <span class="hp-btn hp-active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="hp-btn">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Suivant --}}
            @if ($orders->hasMorePages())
                <a href="{{ $orders->nextPageUrl() }}" class="hp-btn"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="hp-btn hp-disabled"><i class="fas fa-chevron-right"></i></span>
            @endif

            <span class="hp-info">Page {{ $orders->currentPage() }} / {{ $orders->lastPage() }}</span>
        </div>
        @endif

        @else

        {{-- ── Panier vide ── --}}
        <div class="histo-empty">
            <div class="histo-empty-icon"><i class="fas fa-shopping-bag"></i></div>
            <h3>Aucune commande</h3>
            <p>Vous n'avez pas encore passé de commande.</p>
            <a href="{{ route('liste-produit') }}" class="th-btn rounded-2">
                <i class="fas fa-utensils"></i> Voir les plats
            </a>
        </div>

        @endif

    </div>
</section>

<style>
/* ── Layout ── */
.histo-section {
    padding: 48px 0 90px;
    background: #f4f5f7;
    min-height: 70vh;
}

/* ── Header ── */
.histo-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    gap: 12px;
    flex-wrap: wrap;
}
.histo-header-left { display: flex; align-items: center; gap: 14px; }
.histo-title {
    font-size: 1.6rem;
    font-weight: 800;
    color: #1a1a1a;
    margin: 0;
    letter-spacing: -.3px;
}
.histo-count {
    background: var(--theme-color, #e74c3c);
    color: #fff;
    font-size: .78rem;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
}
.histo-new-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: .85rem;
    font-weight: 700;
    color: var(--theme-color, #e74c3c);
    border: 1.5px solid var(--theme-color, #e74c3c);
    padding: 9px 18px;
    border-radius: 8px;
    text-decoration: none;
    transition: all .2s;
}
.histo-new-btn:hover {
    background: var(--theme-color, #e74c3c);
    color: #fff;
    text-decoration: none;
}

/* ── Cards ── */
.histo-list { display: flex; flex-direction: column; gap: 16px; }
.histo-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    overflow: hidden;
    transition: box-shadow .2s;
}
.histo-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,.1); }

/* Header card */
.histo-card-hd {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #f0f0f0;
    flex-wrap: wrap;
    gap: 10px;
}
.histo-cmd-info { display: flex; flex-direction: column; gap: 3px; }
.histo-cmd-num {
    font-size: 1rem;
    font-weight: 800;
    color: #1a1a1a;
    letter-spacing: .2px;
}
.histo-cmd-date {
    font-size: .78rem;
    color: #999;
    display: flex;
    align-items: center;
    gap: 5px;
}
.histo-cmd-date i { font-size: .7rem; }

/* Status badges */
.histo-badge {
    font-size: .75rem;
    font-weight: 700;
    padding: 5px 14px;
    border-radius: 20px;
    white-space: nowrap;
}
.st-attente      { background: #e7f3ff; color: #0066cc; }
.st-precommande  { background: #f3e7f9; color: #9333ea; }
.st-acompte      { background: #fff7ed; color: #ea580c; }
.st-confirmed    { background: #e6ffed; color: #00b33c; }
.st-cuisine      { background: #e0f2fe; color: #0284c7; }
.st-ready        { background: #e3f2fd; color: #1976d2; }
.st-delivery     { background: #e2e8f0; color: #475569; }
.st-done         { background: #dcfce7; color: #15803d; }
.st-cancelled    { background: #ffebee; color: #dc3545; }

/* Produits */
.histo-products {
    padding: 12px 20px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.histo-product {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 0;
    border-bottom: 1px solid #f5f5f5;
}
.histo-product:last-child { border-bottom: none; }
.histo-product-img {
    width: 54px;
    height: 54px;
    min-width: 54px;
    border-radius: 8px;
    overflow: hidden;
    display: block;
}
.histo-product-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.histo-product-body { flex: 1; display: flex; flex-direction: column; gap: 2px; }
.histo-product-name {
    font-size: .88rem;
    font-weight: 600;
    color: #1a1a1a;
    text-decoration: none;
    line-height: 1.3;
}
.histo-product-name:hover { color: var(--theme-color, #e74c3c); }
.histo-product-meta { font-size: .78rem; color: #999; }
.histo-product-total {
    font-size: .88rem;
    font-weight: 700;
    color: #1a1a1a;
    white-space: nowrap;
}
.histo-more {
    font-size: .78rem;
    color: #aaa;
    font-style: italic;
    padding: 4px 0 2px;
}

/* Footer card */
.histo-card-ft {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    background: #fafafa;
    border-top: 1px solid #f0f0f0;
    flex-wrap: wrap;
    gap: 12px;
}
.histo-totals {
    display: flex;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
}
.histo-total-item { display: flex; flex-direction: column; gap: 1px; }
.ht-label { font-size: .72rem; font-weight: 600; color: #aaa; text-transform: uppercase; letter-spacing: .5px; }
.ht-val { font-size: .92rem; font-weight: 700; color: #333; }
.ht-main { color: var(--theme-color, #e74c3c); font-size: 1.05rem; }
.ht-free {
    font-size: .82rem;
    font-weight: 700;
    color: #28a745;
    background: #d4edda;
    padding: 2px 8px;
    border-radius: 10px;
    display: inline-block;
    margin-top: 2px;
}
.histo-actions { display: flex; gap: 8px; }
.histo-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: .82rem;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    transition: all .2s;
    white-space: nowrap;
}
.histo-btn-ghost {
    border: 1.5px solid #ddd;
    color: #555;
    background: #fff;
}
.histo-btn-ghost:hover { border-color: #aaa; color: #1a1a1a; text-decoration: none; }
.histo-btn-primary {
    background: var(--theme-color, #e74c3c);
    color: #fff;
    border: 1.5px solid transparent;
}
.histo-btn-primary:hover { filter: brightness(.9); color: #fff; text-decoration: none; }

/* ── Pagination ── */
.histo-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    margin-top: 36px;
    flex-wrap: wrap;
}
.hp-btn {
    width: 40px;
    height: 40px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    font-size: .88rem;
    font-weight: 600;
    border: 1.5px solid #e0e0e0;
    background: #fff;
    color: #444;
    text-decoration: none;
    transition: all .2s;
    cursor: pointer;
}
.hp-btn:hover:not(.hp-disabled):not(.hp-active) {
    border-color: var(--theme-color, #e74c3c);
    color: var(--theme-color, #e74c3c);
    text-decoration: none;
}
.hp-active {
    background: var(--theme-color, #e74c3c);
    border-color: var(--theme-color, #e74c3c);
    color: #fff !important;
    cursor: default;
}
.hp-disabled {
    opacity: .35;
    cursor: not-allowed;
}
.hp-info {
    font-size: .78rem;
    color: #aaa;
    font-weight: 600;
    margin-left: 8px;
    white-space: nowrap;
}

/* ── Empty ── */
.histo-empty {
    text-align: center;
    padding: 80px 20px;
    background: #fff;
    border-radius: 16px;
    max-width: 420px;
    margin: 0 auto;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.histo-empty-icon {
    font-size: 3.5rem;
    color: #e0e0e0;
    margin-bottom: 18px;
}
.histo-empty h3 { font-weight: 800; color: #1a1a1a; margin-bottom: 8px; }
.histo-empty p { color: #888; margin-bottom: 24px; font-size: .93rem; }

/* ── Responsive ── */
@media (max-width: 576px) {
    .histo-card-ft { flex-direction: column; align-items: flex-start; }
    .histo-actions { width: 100%; }
    .histo-btn { flex: 1; justify-content: center; }
    .histo-totals { gap: 16px; }
}
</style>

@endsection
