@extends('site.layouts.app')

@section('title', 'Liste des plats')

@section('content')
@includeWhen(!Auth::check(), 'site.sections.popup-register')

<style>
/* ── Product list section ── */
.ak-list-section { padding: 48px 0 64px; background: #fafafa; }

/* ── Search result info ── */
.ak-search-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
    padding: 14px 18px;
    background: #fff;
    border-radius: 10px;
    border: 1.5px solid #f0f0f0;
    margin-bottom: 28px;
    font-size: .85rem;
    color: #666;
}
.ak-search-keyword { color: var(--ak-orange,#f85d05); font-weight: 700; }
.ak-search-count { font-weight: 700; color: var(--ak-red,#eb0029); }

/* ── Product card (grid view) ── */
.ak-plat-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 14px rgba(0,0,0,.07);
    transition: transform .25s, box-shadow .25s;
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
}
.ak-plat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 32px rgba(0,0,0,.13); }

.ak-plat-img {
    position: relative;
    overflow: hidden;
    aspect-ratio: 4/3;
    background: #f3f3f3;
    flex-shrink: 0;
}
.ak-plat-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .4s;
}
.ak-plat-card:hover .ak-plat-img img { transform: scale(1.06); }
.ak-plat-img-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 50%, rgba(10,0,0,.5) 100%);
    opacity: 0;
    transition: opacity .3s;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 16px;
}
.ak-plat-card:hover .ak-plat-img-overlay { opacity: 1; }
.ak-plat-quick-view {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: #fff;
    color: #1a1a1a;
    font-size: .75rem;
    font-weight: 700;
    border-radius: 50px;
    text-decoration: none;
    transition: background .2s;
}
.ak-plat-quick-view:hover { background: var(--ak-orange, #f85d05); color: #fff; text-decoration: none; }

/* Remise badge */
.ak-plat-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: var(--ak-orange, #f85d05);
    color: #fff;
    font-size: .7rem;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 50px;
    box-shadow: 0 2px 8px rgba(248,93,5,.35);
}

.ak-plat-body {
    flex: 1;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.ak-plat-cat {
    font-size: .7rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--ak-orange,#f85d05);
    text-decoration: none;
}
.ak-plat-cat:hover { color: var(--ak-red,#eb0029); text-decoration: none; }
.ak-plat-name {
    font-size: .92rem;
    font-weight: 700;
    color: #1a1a1a;
    text-decoration: none;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.ak-plat-name:hover { color: var(--ak-red,#eb0029); text-decoration: none; }
.ak-plat-price {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: auto;
    padding-top: 8px;
}
.ak-plat-price-current { font-size: 1rem; font-weight: 800; color: var(--ak-red,#eb0029); }
.ak-plat-price-old { font-size: .78rem; color: #bbb; text-decoration: line-through; }

.ak-plat-footer {
    padding: 12px 16px;
    border-top: 1px solid #f5f5f5;
}
.ak-plat-add {
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
.ak-plat-add:hover { background: #d44d00; transform: translateY(-1px); }

/* ── Pagination ── */
.ak-pagination {
    display: flex;
    justify-content: center;
    margin-top: 48px;
}
.ak-pagination .pagination { gap: 6px; }
.ak-pagination .page-link {
    border-radius: 8px !important;
    border: 1.5px solid #e8e8e8;
    color: #444;
    font-weight: 600;
    font-size: .85rem;
    padding: 8px 14px;
    transition: all .15s;
}
.ak-pagination .page-link:hover {
    background: var(--ak-orange, #f85d05);
    border-color: var(--ak-red,#eb0029);
    color: #fff;
}
.ak-pagination .page-item.active .page-link {
    background: var(--ak-orange, #f85d05);
    border-color: var(--ak-red,#eb0029);
    color: #fff;
}
.ak-pagination .page-item.disabled .page-link { color: #ccc; }

/* ── Empty state ── */
.ak-empty {
    text-align: center;
    padding: 64px 24px;
}
.ak-empty-icon {
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
.ak-empty h3 { font-size: 1.1rem; font-weight: 700; color: #333; margin-bottom: 8px; }
.ak-empty p { font-size: .88rem; color: #888; margin-bottom: 24px; }
.ak-empty-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: var(--ak-orange, #f85d05);
    color: #fff;
    font-size: .85rem;
    font-weight: 700;
    border-radius: 8px;
    text-decoration: none;
    transition: background .2s;
}
.ak-empty-cta:hover { background: #d44d00; color: #fff; text-decoration: none; }
</style>

{{-- ── Breadcrumb ── --}}
<div class="ak-breadcrumb">
    <div class="container">
        <h1 class="ak-breadcrumb-title">
            @if (request('categorie') || request('sous-categorie'))
                {{ $name_category->name ?? 'Catégorie' }}
            @elseif (request('q'))
                Résultats de recherche
            @else
                Tous nos plats
            @endif
        </h1>
        <ul class="ak-breadcrumb-nav">
            <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li><a href="{{ route('liste-produit') }}">Nos plats</a></li>
            @if (request('categorie') || request('sous-categorie'))
                <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
                <li class="active">{{ $name_category->name ?? 'Catégorie' }}</li>
            @endif
        </ul>
    </div>
</div>

{{-- ── Products ── --}}
<section class="ak-list-section">
    <div class="container">

        {{-- Résultat de recherche --}}
        @if (request('q'))
            <div class="ak-search-info">
                <span>Recherche : <span class="ak-search-keyword">« {{ request('q') }} »</span></span>
                <span class="ak-search-count">{{ $product->total() }} plat(s) trouvé(s)</span>
            </div>
        @endif

        {{-- Grille de plats --}}
        @if ($product->count() > 0)
            <div class="row gy-4">
                @foreach ($product as $item)
                    @php
                        $hasRemise = $item['montant_remise'] != null && $item['status_remise'] == 'en_cours';
                        $newPrice  = $item['price'] - ($item['montant_remise'] ?? 0);
                    @endphp
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="ak-plat-card">
                            <div class="ak-plat-img">
                                <a href="{{ route('detail-produit', $item['slug']) }}">
                                    <img src="{{ asset($item->getFirstMediaUrl('product_image')) }}"
                                         alt="{{ $item['title'] }}" loading="lazy">
                                </a>
                                <div class="ak-plat-img-overlay">
                                    <a href="{{ route('detail-produit', $item['slug']) }}" class="ak-plat-quick-view">
                                        <i class="far fa-eye"></i> Voir le plat
                                    </a>
                                </div>
                                @if ($hasRemise)
                                    <span class="ak-plat-badge">- {{ $item['pourcentage_remise'] }}%</span>
                                @endif
                            </div>
                            <div class="ak-plat-body">
                                @if ($item['subcategorie'])
                                    <a href="/produit?sous-categorie={{ $item['subcategorie']['id'] }}" class="ak-plat-cat">
                                        {{ $item['subcategorie']['name'] }}
                                    </a>
                                @else
                                    <a href="/produit?categorie={{ $item['categories'][0]['id'] }}" class="ak-plat-cat">
                                        {{ $item['categories'][0]['name'] }}
                                    </a>
                                @endif
                                <a href="{{ route('detail-produit', $item['slug']) }}" class="ak-plat-name">
                                    {{ $item['title'] }}
                                </a>
                                <div class="ak-plat-price">
                                    @if ($hasRemise)
                                        <span class="ak-plat-price-current">{{ number_format($newPrice, 0, ',', ' ') }} FCFA</span>
                                        <span class="ak-plat-price-old">{{ number_format($item['price'], 0, ',', ' ') }} FCFA</span>
                                    @else
                                        <span class="ak-plat-price-current">{{ number_format($item['price'], 0, ',', ' ') }} FCFA</span>
                                    @endif
                                </div>
                            </div>
                            <div class="ak-plat-footer">
                                <button class="ak-plat-add addCart" data-id="{{ $item['id'] }}">
                                    <i class="fa fa-cart-plus"></i> Ajouter au panier
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="ak-pagination">
                {{ $product->links('pagination::bootstrap-5') }}
            </div>

        @else
            <div class="ak-empty">
                <div class="ak-empty-icon"><i class="fas fa-utensils"></i></div>
                <h3>Aucun plat trouvé</h3>
                <p>Essayez une autre catégorie ou revenez à la liste complète.</p>
                <a href="{{ route('liste-produit') }}" class="ak-empty-cta">
                    <i class="fas fa-arrow-left"></i> Voir tous nos plats
                </a>
            </div>
        @endif

    </div>
</section>

@include('site.components.ajouter-au-panier')
@endsection
