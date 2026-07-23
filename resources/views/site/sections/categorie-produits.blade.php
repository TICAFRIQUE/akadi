@once
<style>
/* ── Section produits ── */
.ak-products-section { padding: 64px 0; background: #fafafa; }

/* ── Tab filter pills ── */
.ak-filter-tabs {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    justify-content: center;
    margin-bottom: 36px;
}
.ak-filter-tabs button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 18px;
    border-radius: 50px;
    border: 1.5px solid #e8e8e8;
    background: #fff;
    color: #555;
    font-size: .82rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .2s;
    white-space: nowrap;
}
.ak-filter-tabs button img {
    width: 22px;
    height: 22px;
    object-fit: cover;
    border-radius: 50%;
}
.ak-filter-tabs button:hover {
    border-color: var(--ak-orange, #f85d05);
    color: var(--ak-orange, #f85d05);
    background: rgba(248,93,5,.05);
}
.ak-filter-tabs button.active {
    background: var(--ak-orange, #f85d05);
    border-color: var(--ak-orange, #f85d05);
    color: #fff;
    box-shadow: 0 4px 14px rgba(248,93,5,.35);
}
.ak-filter-tabs button.active img { filter: brightness(0) invert(1); }

/* ── Product card redesign ── */
.ak-product-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    transition: transform .25s, box-shadow .25s;
    height: 100%;
    display: flex;
}
.ak-product-card:hover { transform: translateY(-4px); box-shadow: 0 8px 28px rgba(0,0,0,.12); }

.ak-product-img {
    width: 180px;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
    background: #f3f3f3;
}
.ak-product-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .4s;
}
.ak-product-card:hover .ak-product-img img { transform: scale(1.07); }

.ak-product-body {
    flex: 1;
    padding: 16px 18px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.ak-product-cat {
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--ak-orange, #f85d05);
    text-decoration: none;
}
.ak-product-cat:hover { color: var(--ak-red, #eb0029); text-decoration: none; }
.ak-product-name {
    font-size: .95rem;
    font-weight: 700;
    color: #1a1a1a;
    line-height: 1.3;
    text-decoration: none;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.ak-product-name:hover { color: var(--ak-red, #eb0029); text-decoration: none; }
.ak-product-desc {
    font-size: .78rem;
    color: #888;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin: 0;
}
.ak-product-price {
    margin-top: auto;
    padding-top: 6px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.ak-price-current {
    font-size: 1.05rem;
    font-weight: 800;
    color: var(--ak-red, #eb0029);
}
.ak-price-old {
    font-size: .8rem;
    color: #bbb;
    text-decoration: line-through;
}
.ak-product-actions {
    display: flex;
    gap: 8px;
    margin-top: 4px;
}
.ak-btn-detail, .ak-btn-cart {
    flex: 1;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: .78rem;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    border: none;
    transition: all .2s;
    white-space: nowrap;
}
.ak-btn-detail {
    background: rgba(235,0,41,.08);
    color: var(--ak-red, #eb0029);
}
.ak-btn-detail:hover { background: var(--ak-red, #eb0029); color: #fff; text-decoration: none; }
.ak-btn-cart {
    background: var(--ak-orange, #f85d05);
    color: #fff;
    box-shadow: 0 2px 8px rgba(248,93,5,.3);
}
.ak-btn-cart:hover { background: #d44d00; color: #fff; box-shadow: 0 4px 12px rgba(248,93,5,.4); }

/* ── CTA voir tout ── */
.ak-view-all {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    background: var(--ak-orange, #f85d05);
    color: #fff;
    font-size: .88rem;
    font-weight: 700;
    border-radius: 8px;
    text-decoration: none;
    transition: all .2s;
    margin-top: 48px;
    box-shadow: 0 4px 16px rgba(248,93,5,.35);
}
.ak-view-all:hover { background: #d44d00; color: #fff; text-decoration: none; transform: translateY(-2px); }
.ak-view-all i { font-size: .75rem; }

/* Responsive */
@media (max-width: 576px) {
    .ak-product-img { width: 120px; }
    .ak-product-body { padding: 12px 14px; }
    .ak-product-actions { flex-wrap: wrap; }
}
</style>
@endonce

<section class="ak-products-section">
    <div class="container">
        <div class="title-area" style="text-align:center;margin-bottom:32px;">
            <div style="display:flex;align-items:center;justify-content:center;gap:8px;font-size:.78rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#eb0029;margin-bottom:10px;">
                <span style="display:block;width:28px;height:2px;background:#eb0029;border-radius:2px;"></span>
                Découvrir nos menus
                <span style="display:block;width:28px;height:2px;background:#eb0029;border-radius:2px;"></span>
            </div>
            <h2 class="ak-section-title" style="text-align:center;">
                Tu testes, tu restes <span class="accent" style="color:#eb0029;font-style:italic;">toujours</span>
            </h2>
        </div>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="nav-one" role="tabpanel" aria-labelledby="nav-one-tab">

                {{-- Filter tabs --}}
                <div class="ak-filter-tabs filter-menu-active">
                    <button data-filter="*" class="active" type="button">
                        <img src="{{ asset('site/assets/img/icon/menu_1_1.svg') }}" alt="Icon">
                        Tous les menus
                    </button>
                    @foreach ($categories as $item)
                        <button data-filter=".cat{{ $item['id'] }}" type="button">
                            <img src="{{ $item->getFirstMediaUrl('category_image') }}" alt="Icon">
                            {{ $item['name'] }}
                        </button>
                    @endforeach
                </div>

                {{-- Products grid --}}
                <div class="row gy-4 filter-active justify-content-center">
                    @php $waNumero = '2250758838338'; @endphp
                    @foreach ($categories as $categorie)
                        @foreach ($categorie['products'] as $product)
                            @php
                                $detailUrl  = route('detail-produit', $product->slug);
                                $waMessage  = 'Bonjour, je souhaite commander le produit *' . $product->title . "*.\nVoici le lien : " . $detailUrl;
                                $urlWhatsapp = 'https://wa.me/' . $waNumero . '?text=' . urlencode($waMessage);
                                $hasRemise  = $product['montant_remise'] != null && $product['status_remise'] == 'en_cours';
                                $newPrice   = $product['price'] - ($product['montant_remise'] ?? 0);
                            @endphp
                            <div class="col-lg-6 filter-item cat{{ $product['pivot']['category_id'] }}">
                                <div class="ak-product-card">
                                    {{-- Image --}}
                                    <div class="ak-product-img">
                                        <a href="{{ $detailUrl }}" style="display:block;height:100%;">
                                            <img src="{{ $product->getFirstMediaUrl('product_image') }}"
                                                 alt="{{ $product->title }}" loading="lazy">
                                        </a>
                                    </div>
                                    {{-- Contenu --}}
                                    <div class="ak-product-body">
                                        @if ($product['subcategorie'])
                                            <a href="/produit?sous-categorie={{ $product['subcategorie']['id'] }}"
                                               class="ak-product-cat">{{ $product['subcategorie']['name'] }}</a>
                                        @else
                                            <a href="/produit?categorie={{ $product['categories'][0]['id'] }}"
                                               class="ak-product-cat">{{ $product['categories'][0]['name'] }}</a>
                                        @endif

                                        <a href="{{ $detailUrl }}" class="ak-product-name">{{ $product['title'] }}</a>
                                        <p class="ak-product-desc">{{ Str::limit(strip_tags($product->description ?? ''), 60, '...') }}</p>

                                        <div class="ak-product-price">
                                            @if ($hasRemise)
                                                <span class="ak-price-current">{{ number_format($newPrice, 0, ',', ' ') }} FCFA</span>
                                                <span class="ak-price-old">{{ number_format($product['price'], 0, ',', ' ') }} FCFA</span>
                                            @else
                                                <span class="ak-price-current">{{ number_format($product['price'], 0, ',', ' ') }} FCFA</span>
                                            @endif
                                        </div>

                                        <div class="ak-product-actions">
                                            <a href="{{ $detailUrl }}" class="ak-btn-detail">
                                                <i class="far fa-eye"></i> Détails
                                            </a>
                                            <button class="ak-btn-cart addCart" data-id="{{ $product['id'] }}">
                                                <i class="fa fa-cart-plus"></i> Ajouter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>

                <div class="text-center">
                    <a href="{{ route('liste-produit') }}" class="ak-view-all">
                        Voir tous nos plats <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('site.components.ajouter-au-panier')
</section>
