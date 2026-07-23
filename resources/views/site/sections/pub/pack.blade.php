<style>
/* ── Pack section ── */
.ak-pack-section { padding: 64px 0; background: #fff; }
.ak-pack-card {
    border-radius: 18px;
    overflow: hidden;
    position: relative;
    aspect-ratio: 4/3;
    display: block;
    cursor: pointer;
    box-shadow: 0 4px 24px rgba(0,0,0,.1);
    transition: transform .3s, box-shadow .3s;
    text-decoration: none;
    background: #111;
}
.ak-pack-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(0,0,0,.2); }
.ak-pack-card .ak-pack-bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    transition: transform .4s;
}
.ak-pack-card:hover .ak-pack-bg { transform: scale(1.06); }
.ak-pack-card .ak-pack-gradient {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, transparent 30%, rgba(10,0,0,.75) 100%);
    transition: background .3s;
}
.ak-pack-card:hover .ak-pack-gradient {
    background: linear-gradient(180deg, transparent 10%, rgba(235,0,41,.75) 100%);
}
.ak-pack-cta {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: #fff;
    color: var(--ak-red, #eb0029);
    font-size: .8rem;
    font-weight: 700;
    padding: 9px 22px;
    border-radius: 50px;
    white-space: nowrap;
    transition: all .2s;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(0,0,0,.2);
}
.ak-pack-card:hover .ak-pack-cta { background: var(--ak-red, #eb0029); color: #fff; }

.ak-pack-info { text-align: center; margin-top: 14px; padding: 0 4px; }
.ak-pack-info a { font-size: .95rem; font-weight: 700; color: #1a1a1a; text-decoration: none; }
.ak-pack-info a:hover { color: var(--ak-red, #eb0029); }
.ak-pack-price { font-size: 1rem; font-weight: 700; color: var(--ak-orange, #f85d05); margin-top: 4px; }
.ak-pack-price del { color: #bbb; font-weight: 400; font-size: .85rem; margin-left: 6px; }
</style>

@if (count($pack) > 0)
<section class="ak-pack-section">
    <div class="container">
        <div class="title-area text-center">
            <div class="ak-section-label mx-auto" style="width:fit-content;display:inline-flex;align-items:center;gap:8px;font-size:.78rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#eb0029;margin-bottom:10px;">
                <span style="display:block;width:28px;height:2px;background:#eb0029;border-radius:2px;"></span>
                Nos packs
                <span style="display:block;width:28px;height:2px;background:#eb0029;border-radius:2px;"></span>
            </div>
            <h2 class="ak-section-title">
                Découvrir nos <span style="color:#eb0029;font-style:italic;">packs</span>
            </h2>
        </div>

        <div class="row gy-4">
            @foreach ($pack as $item)
                @php
                    $hasRemise = $item['montant_remise'] != null && $item['status_remise'] == 'en_cours';
                    $newPrice  = $item['price'] - ($item['montant_remise'] ?? 0);
                @endphp
                <div class="col-xl-4 col-md-6">
                    <a href="{{ route('detail-produit', $item['slug']) }}" class="ak-pack-card">
                        <div class="ak-pack-bg" style="background-image:url('{{ $item->getFirstMediaUrl('product_image') }}');"></div>
                        <div class="ak-pack-gradient"></div>
                        <span class="ak-pack-cta">Commander</span>
                    </a>
                    <div class="ak-pack-info">
                        <a href="{{ route('detail-produit', $item['slug']) }}">{{ $item['title'] }}</a>
                        <div class="ak-pack-price">
                            @if ($hasRemise)
                                {{ format_price($newPrice) }} FCFA
                                <del>{{ number_format($item['price'], 0, ',', ' ') }} FCFA</del>
                            @else
                                {{ number_format($item['price'], 0, ',', ' ') }} FCFA
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
