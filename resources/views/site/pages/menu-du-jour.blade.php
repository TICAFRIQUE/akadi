@extends('site.layouts.app')

@section('title', 'Menu du jour')

@section('content')

<style>
.ak-menu-section {
    padding: 48px 0 64px;
    background: #fafafa;
}
.ak-menu-plat-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 14px rgba(0,0,0,.07);
    transition: transform .25s, box-shadow .25s;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.ak-menu-plat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 32px rgba(0,0,0,.13); }
.ak-menu-plat-icon {
    aspect-ratio: 4/2.2;
    background: linear-gradient(135deg, var(--ak-orange,#f85d05), var(--ak-red,#eb0029));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 2rem;
}
.ak-menu-plat-body { flex: 1; padding: 16px; display: flex; flex-direction: column; gap: 6px; }
.ak-menu-plat-name { font-size: .95rem; font-weight: 700; color: #1a1a1a; }
.ak-menu-plat-desc { font-size: .8rem; color: #888; flex: 1; }
.ak-menu-plat-price { font-size: 1.05rem; font-weight: 800; color: var(--ak-red,#eb0029); margin-top: auto; padding-top: 8px; }
.ak-menu-plat-footer { padding: 12px 16px; border-top: 1px solid #f5f5f5; }
.ak-menu-plat-add {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 10px;
    background: var(--ak-orange, #f85d05);
    color: #fff;
    font-size: .82rem;
    font-weight: 700;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all .2s;
}
.ak-menu-plat-add:hover { background: #d44d00; transform: translateY(-1px); }
.ak-menu-empty {
    text-align: center;
    padding: 64px 24px;
}
.ak-menu-empty-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(235,0,41,.07);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    font-size: 1.8rem;
    color: var(--ak-red,#eb0029);
}
.ak-menu-empty h3 { font-size: 1.1rem; font-weight: 700; color: #333; margin-bottom: 8px; }
.ak-menu-empty p { font-size: .88rem; color: #888; margin-bottom: 24px; }
.ak-menu-welcome {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 10px 0 0;
    font-size: .92rem;
    font-weight: 500;
    color: rgba(255,255,255,.82);
}
.ak-menu-welcome i { color: var(--ak-orange, #f85d05); }
</style>

{{-- ── Breadcrumb ── --}}
@php
    $aujourdhui = \Carbon\Carbon::now()->locale('fr');
@endphp
<div class="ak-breadcrumb">
    <div class="container">
        <h1 class="ak-breadcrumb-title">
            <span class="ak-breadcrumb-icon"><i class="fas fa-utensils"></i></span>
            Menu du jour
            <span class="ak-breadcrumb-badge">{{ ucfirst($aujourdhui->isoFormat('dddd')) }}</span>
        </h1>
        <ul class="ak-breadcrumb-nav">
            <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li class="active">Menu du jour</li>
        </ul>
        <p class="ak-menu-welcome">
            <i class="fas fa-hand-sparkles"></i>
            Bienvenue sur le menu du {{ $aujourdhui->isoFormat('dddd D MMMM') }}
        </p>
    </div>
</div>

<section class="ak-menu-section">
    <div class="container">
        @if ($menuJour && $menuJour->menuProduits->count() > 0)
            <div class="row gy-4">
                @foreach ($menuJour->menuProduits as $plat)
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="ak-menu-plat-card">
                            <div class="ak-menu-plat-icon">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div class="ak-menu-plat-body">
                                <div class="ak-menu-plat-name">{{ $plat->nom }}</div>
                                @if ($plat->description)
                                    <div class="ak-menu-plat-desc">{{ $plat->description }}</div>
                                @endif
                                <div class="ak-menu-plat-price">{{ number_format($plat->prix, 0, ',', ' ') }} FCFA</div>
                            </div>
                            <div class="ak-menu-plat-footer">
                                <button class="ak-menu-plat-add addCartMenu" data-id="{{ $plat->id }}">
                                    <i class="fa fa-cart-plus"></i> Ajouter au panier
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="ak-menu-empty">
                <div class="ak-menu-empty-icon"><i class="fas fa-utensils"></i></div>
                <h3>Aucun menu du jour disponible</h3>
                <p>Revenez plus tard, notre chef prépare le menu du jour.</p>
                <a href="{{ route('liste-produit') }}" class="ak-empty-cta" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:var(--ak-orange,#f85d05);color:#fff;font-size:.85rem;font-weight:700;border-radius:8px;text-decoration:none;">
                    <i class="fas fa-arrow-left"></i> Voir tous nos plats
                </a>
            </div>
        @endif
    </div>
</section>

@include('site.components.ajouter-au-panier')

@endsection
