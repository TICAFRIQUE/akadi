@extends('site.layouts.app')

@section('title', 'Détail - ' . $product['title'])
@section('description', $product['description'])
@section('image', asset($product->getFirstMediaUrl('product_image')))

@section('content')
@includeWhen(!Auth::check(), 'site.sections.popup-register')

<style>
/* ── Detail section ── */
.ak-detail-section { padding: 48px 0 64px; background: #fafafa; }

/* ── Product image area ── */
.ak-detail-imgs {
    position: sticky;
    top: 90px;
}
.product-thumb-area { border-radius: 16px; overflow: hidden; background: #fff; box-shadow: 0 4px 24px rgba(0,0,0,.08); }
.product-thumb-tab { padding: 12px; display: flex; gap: 8px; overflow-x: auto; }
.product-thumb-tab .tab-btn {
    width: 72px;
    height: 72px;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid #f0f0f0;
    flex-shrink: 0;
    cursor: pointer;
    transition: border-color .2s;
}
.product-thumb-tab .tab-btn.active,
.product-thumb-tab .tab-btn:hover { border-color: var(--ak-red,#eb0029); }
.product-thumb-tab .tab-btn img { width: 100%; height: 100%; object-fit: cover; }
.product-big-img { padding: 0 12px 12px; }
.product-big-img .img img {
    width: 100%;
    border-radius: 10px;
    max-height: 400px;
    object-fit: cover;
}

/* ── Product info ── */
.ak-detail-info {
    background: #fff;
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 4px 24px rgba(0,0,0,.06);
}

.ak-detail-cat {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--ak-orange,#f85d05);
    text-decoration: none;
    margin-bottom: 10px;
}
.ak-detail-cat:hover { color: var(--ak-red,#eb0029); text-decoration: none; }

.ak-detail-title {
    font-size: clamp(1.4rem, 3vw, 1.9rem);
    font-weight: 800;
    color: #1a1a1a;
    line-height: 1.25;
    margin-bottom: 16px;
}

.ak-detail-price-area {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    padding: 16px 0;
    border-top: 1px solid #f0f0f0;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 20px;
}
.ak-detail-price-new { font-size: 1.8rem; font-weight: 800; color: var(--ak-red,#eb0029); }
.ak-detail-price-old { font-size: 1rem; color: #bbb; text-decoration: line-through; }
.ak-detail-remise-badge {
    background: var(--ak-orange, #f85d05);
    color: #fff;
    font-size: .72rem;
    font-weight: 800;
    padding: 4px 10px;
    border-radius: 50px;
    margin-left: 4px;
}

.ak-detail-desc {
    font-size: .88rem;
    color: #666;
    line-height: 1.8;
    margin-bottom: 24px;
}
.ak-detail-desc-label {
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: #999;
    margin-bottom: 8px;
}

/* ── CTA buttons ── */
.ak-detail-actions { display: flex; flex-direction: column; gap: 10px; }
.ak-btn-panier {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 14px;
    background: var(--ak-orange, #f85d05);
    color: #fff;
    font-size: .92rem;
    font-weight: 700;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: all .2s;
    text-decoration: none;
    width: 100%;
}
.ak-btn-panier:hover { background: #d44d00; color: #fff; transform: translateY(-2px); }
.ak-btn-wa {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 14px;
    background: #25d366;
    color: #fff;
    font-size: .92rem;
    font-weight: 700;
    border-radius: 10px;
    text-decoration: none;
    transition: all .2s;
}
.ak-btn-wa:hover { background: #1da851; color: #fff; text-decoration: none; transform: translateY(-2px); }

/* ── Comments section ── */
.ak-comments-section {
    margin-top: 48px;
    background: #fff;
    border-radius: 20px;
    padding: 28px;
    box-shadow: 0 4px 24px rgba(0,0,0,.06);
}
.ak-comments-title {
    font-size: 1.1rem;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f0f0f0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.ak-comments-title i { color: var(--ak-red,#eb0029); }

/* ── Related products ── */
.ak-related-section { margin-top: 64px; }
.ak-related-title {
    font-size: 1.3rem;
    font-weight: 800;
    color: #1a1a1a;
    margin-bottom: 28px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.ak-related-title::after {
    content: '';
    flex: 1;
    height: 2px;
    background: #f0f0f0;
    border-radius: 2px;
}
.ak-related-title span { color: var(--ak-red,#eb0029); }

.ak-related-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    transition: transform .25s, box-shadow .25s;
    position: relative;
}
.ak-related-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.12); }
.ak-related-img {
    position: relative;
    aspect-ratio: 4/3;
    overflow: hidden;
    background: #f3f3f3;
}
.ak-related-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .4s; }
.ak-related-card:hover .ak-related-img img { transform: scale(1.06); }
.ak-related-badge {
    position: absolute;
    top: 10px; left: 10px;
    background: var(--ak-orange, #f85d05);
    color: #fff;
    font-size: .65rem;
    font-weight: 800;
    padding: 3px 8px;
    border-radius: 50px;
}
.ak-related-body { padding: 14px; }
.ak-related-name {
    font-size: .88rem;
    font-weight: 700;
    color: #1a1a1a;
    text-decoration: none;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.3;
    margin-bottom: 8px;
}
.ak-related-name:hover { color: var(--ak-red,#eb0029); text-decoration: none; }
.ak-related-price { font-size: .92rem; font-weight: 800; color: var(--ak-red,#eb0029); margin-bottom: 10px; }
.ak-related-price del { font-size: .78rem; color: #bbb; font-weight: 400; margin-left: 6px; }
.ak-related-add {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 9px;
    background: var(--ak-orange, #f85d05);
    color: #fff;
    font-size: .78rem;
    font-weight: 700;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background .2s;
}
.ak-related-add:hover { background: #d44d00; }

@media (max-width: 991px) {
    .ak-detail-imgs { position: static; }
    .ak-detail-info { margin-top: 24px; }
}
</style>

{{-- ── Breadcrumb ── --}}
<div class="ak-breadcrumb">
    <div class="container">
        <h1 class="ak-breadcrumb-title">{{ Str::limit($product['title'], 40, '...') }}</h1>
        <ul class="ak-breadcrumb-nav">
            <li><a href="javascript:history.go(-1)"><i class="fas fa-arrow-left"></i> Retour</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li><a href="{{ route('liste-produit') }}">Nos plats</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li class="active">{{ Str::limit($product['title'], 30, '...') }}</li>
        </ul>
    </div>
</div>

{{-- ── Detail ── --}}
<section class="ak-detail-section">
    <div class="container">
        <div class="row gy-4">

            {{-- Images --}}
            <div class="col-lg-7">
                <div class="ak-detail-imgs">
                    <div class="product-thumb-area">
                        <div class="product-thumb-tab" data-asnavfor=".product-big-img">
                            @foreach ($product->getMedia('product_image') as $item)
                                <div class="tab-btn active">
                                    <img src="{{ $item->getUrl() }}" alt="Miniature" loading="lazy">
                                </div>
                            @endforeach
                        </div>
                        <div class="product-big-img th-carousel" data-slide-show="1" data-md-slide-show="1" data-fade="true">
                            @foreach ($product->getMedia('product_image') as $item)
                                <div class="col-auto">
                                    <div class="img">
                                        <img src="{{ $item->getUrl() }}" alt="{{ $product['title'] }}" loading="lazy">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info --}}
            <div class="col-lg-5">
                <div class="ak-detail-info">
                    {{-- Catégorie --}}
                    <a href="/produit?categorie={{ $product['categories'][0]['id'] }}" class="ak-detail-cat">
                        <i class="fas fa-tag"></i> {{ $product['categories'][0]['name'] }}
                    </a>

                    {{-- Titre --}}
                    <h1 class="ak-detail-title">{{ $product['title'] }}</h1>

                    {{-- Prix --}}
                    <div class="ak-detail-price-area">
                        @if ($product['montant_remise'] != null && $product['status_remise'] == 'en_cours')
                            @php $newPrice = $product['price'] - $product['montant_remise']; @endphp
                            <span class="ak-detail-price-new">{{ number_format($newPrice, 0, ',', ' ') }} FCFA</span>
                            <span class="ak-detail-price-old">{{ number_format($product['price'], 0, ',', ' ') }} FCFA</span>
                            <span class="ak-detail-remise-badge">- {{ $product['pourcentage_remise'] }}%</span>
                        @else
                            <span class="ak-detail-price-new">{{ number_format($product['price'], 0, ',', ' ') }} FCFA</span>
                        @endif
                    </div>

                    {{-- Description --}}
                    @if ($product['description'])
                        <div class="ak-detail-desc-label">Description</div>
                        <div class="ak-detail-desc">{!! $product['description'] !!}</div>
                    @endif

                    {{-- Actions --}}
                    @php
                        $numero     = '2250758838338';
                        $message    = 'Bonjour, je souhaite commander le plat *' . $product->title . "*.\nVoici le lien : " . route('detail-produit', $product->slug);
                        $urlWhatsapp = 'https://wa.me/' . $numero . '?text=' . urlencode($message);
                    @endphp
                    <div class="ak-detail-actions">
                        <button class="ak-btn-panier addCart" data-id="{{ $product['id'] }}">
                            <i class="fa fa-cart-plus"></i> Ajouter au panier
                        </button>
                        <a href="{{ $urlWhatsapp }}" target="_blank" class="ak-btn-wa">
                            <i class="fab fa-whatsapp"></i> Commander via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Commentaires --}}
        <div class="ak-comments-section">
            <h2 class="ak-comments-title">
                <i class="far fa-comment-alt"></i>
                Avis sur ce plat
                @if (count($product['commentaires']) > 0)
                    <span style="font-size:.8rem;color:#999;font-weight:500;">({{ count($product['commentaires']) }} avis)</span>
                @endif
            </h2>
            @include('site.sections.commentaire-produit')
        </div>

        {{-- Produits similaires --}}
        @if (count($product_related) > 0)
            <div class="ak-related-section">
                <h2 class="ak-related-title">Dans la même catégorie <span>&nbsp;</span></h2>
                <div class="row th-carousel" data-slide-show="4" data-lg-slide-show="3" data-md-slide-show="2"
                     data-sm-slide-show="2" data-xs-slide-show="1" data-arrows="true">
                    @foreach ($product_related as $item)
                        @php
                            $hasR = $item['montant_remise'] != null && $item['status_remise'] == 'en_cours';
                            $nP   = $item['price'] - ($item['montant_remise'] ?? 0);
                        @endphp
                        <div class="col-xl-3 col-lg-4 col-sm-6">
                            <div class="ak-related-card">
                                <div class="ak-related-img">
                                    <a href="{{ route('detail-produit', $item['slug']) }}">
                                        <img src="{{ $item->getFirstMediaUrl('product_image') }}"
                                             alt="{{ $item['title'] }}" loading="lazy">
                                    </a>
                                    @if ($hasR)
                                        <span class="ak-related-badge">- {{ $item['pourcentage_remise'] }}%</span>
                                    @endif
                                </div>
                                <div class="ak-related-body">
                                    <a href="{{ route('detail-produit', $item['slug']) }}" class="ak-related-name">
                                        {{ $item['title'] }}
                                    </a>
                                    <div class="ak-related-price">
                                        @if ($hasR)
                                            {{ number_format($nP, 0, ',', ' ') }} FCFA
                                            <del>{{ number_format($item['price'], 0, ',', ' ') }} FCFA</del>
                                        @else
                                            {{ number_format($item['price'], 0, ',', ' ') }} FCFA
                                        @endif
                                    </div>
                                    <button class="ak-related-add addCart" data-id="{{ $item['id'] }}">
                                        <i class="fa fa-cart-plus"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>

@include('site.components.ajouter-au-panier')
@endsection
