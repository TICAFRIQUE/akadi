<style>
/* ── Section catégories ── */
.ak-cats-section { padding: 64px 0; background: #fff; }
.ak-section-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    color: var(--ak-red, #eb0029);
    margin-bottom: 10px;
}
.ak-section-label::before, .ak-section-label::after {
    content: '';
    display: block;
    width: 28px;
    height: 2px;
    background: var(--ak-red, #eb0029);
    border-radius: 2px;
}
.ak-section-title {
    font-size: clamp(1.6rem, 3vw, 2.2rem);
    font-weight: 800;
    color: #1a1a1a;
    margin: 0 0 40px;
    line-height: 1.2;
}
.ak-section-title .accent { color: var(--ak-red, #eb0029); }

/* ── Category card ── */
.ak-cat-card {
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    cursor: pointer;
    box-shadow: 0 4px 20px rgba(0,0,0,.08);
    transition: transform .3s, box-shadow .3s;
    background: #f5f5f5;
    aspect-ratio: 3/4;
    display: block;
    text-decoration: none;
}
.ak-cat-card:hover { transform: translateY(-6px); box-shadow: 0 12px 32px rgba(235,0,41,.18); text-decoration: none; }
.ak-cat-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .4s;
}
.ak-cat-card:hover img { transform: scale(1.06); }
.ak-cat-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 40%, rgba(26,0,0,.82) 100%);
    display: flex;
    align-items: flex-end;
    padding: 20px;
    transition: background .3s;
}
.ak-cat-card:hover .ak-cat-overlay {
    background: linear-gradient(180deg, transparent 20%, rgba(235,0,41,.88) 100%);
}
.ak-cat-name {
    font-size: .95rem;
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
    text-transform: capitalize;
    display: flex;
    align-items: center;
    gap: 6px;
}
.ak-cat-name i { font-size: .7rem; transition: transform .3s; }
.ak-cat-card:hover .ak-cat-name i { transform: translateX(4px); }

/* ── Carousel dots brand ── */
.ak-cats-section .slick-dots li button:before { color: #ccc; }
.ak-cats-section .slick-dots li.slick-active button:before { color: var(--ak-red, #eb0029); }
.ak-cats-section .slick-prev:before, .ak-cats-section .slick-next:before { color: var(--ak-red, #eb0029); }
</style>

<section class="ak-cats-section">
    <div class="container">
        <div class="title-area text-center">
            <div class="ak-section-label mx-auto" style="width:fit-content">Menu</div>
            <h2 class="ak-section-title">
                Parcourir nos catégories <span class="accent">Akadi</span>
            </h2>
            <p class="d-md-none" style="font-size:.82rem;color:#888;margin-top:-24px;margin-bottom:24px;">
                <i class="fas fa-hand-point-left"></i> Glissez pour voir plus <i class="fas fa-hand-point-right"></i>
            </p>
        </div>

        <div class="row th-carousel category-carousel" id="categoryCarousel"
             data-slide-show="4" data-ml-slide-show="4" data-lg-slide-show="4"
             data-md-slide-show="3" data-sm-slide-show="2" data-xs-slide-show="1.5"
             data-arrows="true" data-dots="true" style="visibility:hidden;">
            @foreach ($categories as $item)
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6 category-item">
                    <a href="/produit?categorie={{ $item['id'] }}" class="ak-cat-card">
                        <img src="{{ $item->getFirstMediaUrl('category_image') }}"
                             alt="{{ $item['name'] }}" loading="lazy">
                        <div class="ak-cat-overlay">
                            <span class="ak-cat-name">
                                {{ $item['name'] }}
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
