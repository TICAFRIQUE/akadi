<style>
/* ── Testimonials section ── */
.ak-testi-section { padding: 72px 0; background: #fff; position: relative; overflow: hidden; }
.ak-testi-section::after {
    content: '"';
    position: absolute;
    right: 5%;
    top: 20px;
    font-size: 220px;
    font-family: Georgia, serif;
    color: rgba(235,0,41,.05);
    line-height: 1;
    pointer-events: none;
    user-select: none;
}

/* ── Testimonial card ── */
.ak-testi-card {
    background: #fff;
    border: 1.5px solid #f0f0f0;
    border-radius: 20px;
    padding: 28px 24px;
    height: 100%;
    display: flex;
    flex-direction: column;
    gap: 16px;
    transition: border-color .25s, box-shadow .25s, transform .25s;
    position: relative;
}
.ak-testi-card:hover {
    border-color: var(--ak-red, #eb0029);
    box-shadow: 0 8px 32px rgba(235,0,41,.1);
    transform: translateY(-4px);
}
.ak-testi-card::before {
    content: '"';
    position: absolute;
    top: 18px;
    right: 22px;
    font-size: 48px;
    font-family: Georgia, serif;
    color: var(--ak-red, #eb0029);
    opacity: .15;
    line-height: 1;
}

.ak-testi-stars {
    display: flex;
    gap: 3px;
}
.ak-testi-stars i { color: var(--ak-orange, #f85d05); font-size: .85rem; }

.ak-testi-text {
    font-size: .88rem;
    color: #555;
    line-height: 1.75;
    flex: 1;
    font-style: italic;
}

.ak-testi-profile {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-top: 16px;
    border-top: 1px solid #f5f5f5;
}
.ak-testi-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    overflow: hidden;
    flex-shrink: 0;
    border: 2px solid var(--ak-red, #eb0029);
}
.ak-testi-avatar img { width: 100%; height: 100%; object-fit: cover; }
.ak-testi-name {
    font-size: .9rem;
    font-weight: 700;
    color: #1a1a1a;
    margin: 0;
}

/* ── Carousel brand dots ── */
.ak-testi-section .slick-dots li button:before { color: #ddd; font-size: 10px; }
.ak-testi-section .slick-dots li.slick-active button:before { color: var(--ak-red,#eb0029); }
</style>

@if (count($feedback) > 0)
<section class="ak-testi-section">
    <div class="container">
        <div class="title-area text-center">
            <div class="ak-section-label mx-auto" style="width:fit-content;display:inline-flex;align-items:center;gap:8px;font-size:.78rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#eb0029;margin-bottom:10px;">
                <span style="display:block;width:28px;height:2px;background:#eb0029;border-radius:2px;"></span>
                Témoignages
                <span style="display:block;width:28px;height:2px;background:#eb0029;border-radius:2px;"></span>
            </div>
            <h2 class="ak-section-title">
                Ce que disent nos <span style="color:#eb0029;font-style:italic;">clients</span>
            </h2>
        </div>

        <div class="row slider-shadow th-carousel number-dots"
             data-slide-show="3" data-lg-slide-show="2" data-md-slide-show="1"
             data-dots="true" data-xl-dots="true" data-ml-dots="true" data-lg-dots="true">
            @foreach ($feedback as $item)
                <div class="col-xl-4 col-lg-6">
                    <div class="ak-testi-card">
                        <div class="ak-testi-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="ak-testi-text">{!! $item['description'] !!}</p>
                        <div class="ak-testi-profile">
                            <div class="ak-testi-avatar">
                                <img src="{{ asset('site/assets/img/custom/avatar.png') }}" alt="Avatar">
                            </div>
                            <div>
                                <p class="ak-testi-name">{{ $item['nom'] ?? 'Client Akadi' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
