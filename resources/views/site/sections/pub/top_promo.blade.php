<style>
/* ── Top Promo section ── */
.ak-promo-section { padding: 64px 0; background: #fafafa; }

.ak-promo-banner {
    background: linear-gradient(90deg, var(--ak-dark,#1a0000), #3d0010, var(--ak-dark,#1a0000));
    color: #fff;
    border-radius: 12px;
    padding: 14px 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-bottom: 36px;
    font-size: .9rem;
    font-weight: 500;
    border: 1px solid rgba(235,0,41,.3);
}
.ak-promo-banner i { color: var(--ak-orange, #f85d05); }
.ak-promo-banner strong { color: var(--ak-orange, #f85d05); }
#Promo-Timer {
    font-weight: 800;
    color: var(--ak-orange, #f85d05);
    font-size: 1rem;
    letter-spacing: .04em;
}

/* ── Promo card layout ── */
.ak-promo-image-wrap {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}
.ak-promo-image-wrap img { max-width: 88%; filter: drop-shadow(0 16px 32px rgba(0,0,0,.2)); }
.ak-promo-discount-badge {
    position: absolute;
    top: 0;
    right: 10px;
    width: 90px;
    height: 90px;
    background: linear-gradient(135deg, var(--ak-red,#eb0029), var(--ak-orange,#f85d05));
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 800;
    box-shadow: 0 8px 24px rgba(235,0,41,.4);
    line-height: 1;
}
.ak-promo-discount-badge .pct { font-size: 1.6rem; }
.ak-promo-discount-badge .pct-off { font-size: .7rem; opacity: .85; }

.ak-promo-content { display: flex; flex-direction: column; justify-content: center; gap: 16px; }
.ak-promo-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(235,0,41,.08);
    color: var(--ak-red, #eb0029);
    font-size: .78rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    padding: 6px 14px;
    border-radius: 50px;
    border: 1px solid rgba(235,0,41,.18);
    width: fit-content;
}
.ak-promo-title {
    font-size: clamp(1.5rem, 3vw, 2rem);
    font-weight: 800;
    color: #1a1a1a;
    line-height: 1.2;
}
.ak-promo-desc { font-size: .92rem; color: #666; line-height: 1.75; }
.ak-promo-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 28px;
    background: var(--ak-red, #eb0029);
    color: #fff;
    font-size: .88rem;
    font-weight: 700;
    border-radius: 8px;
    text-decoration: none;
    transition: all .2s;
    width: fit-content;
}
.ak-promo-cta:hover { background: #c4001f; color: #fff; text-decoration: none; transform: translateY(-2px); }
.ak-promo-cta i { font-size: .75rem; }

.ak-promo-bientot {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 22px;
    background:  "rgba(248,93,5,$($args[0].Groups[1].Value))" ;
    color: var(--ak-orange, #f85d05);
    border: 1.5px solid  "rgba(248,93,5,$($args[0].Groups[1].Value))" ;
    font-size: .88rem;
    font-weight: 700;
    border-radius: 8px;
    width: fit-content;
}
.ak-promo-bientot strong { color: var(--ak-red, #eb0029); }
</style>

@if ($top_promo)
<section class="ak-promo-section">
    <div class="container">

        {{-- Bannière compteur --}}
        @if ($top_promo['status_pub'] == 'bientot')
            <div class="ak-promo-banner">
                <i class="fas fa-clock"></i>
                Promo disponible
                <strong>{{ \Carbon\Carbon::parse($top_promo['date_debut_pub'])->diffForHumans() }}</strong>
                — {{ $top_promo['discount'] }}% de réduction !
            </div>
        @elseif ($top_promo['status_pub'] == 'en_cours')
            <div class="ak-promo-banner">
                <i class="fas fa-fire"></i>
                Se termine dans <span id="Promo-Timer">--</span>
                — Profitez de {{ $top_promo['discount'] }}% de réduction !
            </div>
        @endif

        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="ak-promo-image-wrap">
                    <img src="{{ $top_promo->getFirstmediaUrl('publicite_image') }}" alt="Promo Akadi">
                    <div class="ak-promo-discount-badge">
                        <span class="pct">{{ $top_promo['discount'] }}</span>
                        <span class="pct-off">% Off</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="ak-promo-content">
                    <div class="ak-promo-tag">
                        <i class="fas fa-tag"></i> Top promo Akadi
                    </div>
                    <h2 class="ak-promo-title">{{ $top_promo['discount'] }}% de réduction</h2>
                    <div class="ak-promo-desc">{!! $top_promo['texte'] !!}</div>

                    @if ($top_promo['status_pub'] == 'en_cours')
                        <a href="{{ $top_promo['url'] }}" class="ak-promo-cta">
                            {{ $top_promo['button_name'] }} <i class="fas fa-arrow-right"></i>
                        </a>
                    @elseif ($top_promo['status_pub'] == 'bientot')
                        <div class="ak-promo-bientot">
                            <i class="fas fa-clock"></i>
                            Disponible <strong>{{ \Carbon\Carbon::parse($top_promo['date_debut_pub'])->diffForHumans() }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<script>
(function () {
    var topPromo = @json($top_promo);
    if (!topPromo || topPromo.status_pub !== 'en_cours') return;
    var timerEl = document.getElementById('Promo-Timer');
    if (!timerEl) return;
    var endTime = new Date(topPromo.date_fin_pub).getTime();
    function countDown() {
        var left = Math.floor((endTime - Date.now()) / 1000);
        if (left <= 0) { timerEl.textContent = 'Terminé'; return; }
        var d = Math.floor(left / 86400);
        var h = Math.floor((left % 86400) / 3600);
        var m = Math.floor((left % 3600) / 60);
        var s = left % 60;
        timerEl.textContent = d + ' j : ' + h + ' h : ' + m + ' m : ' + s + ' s';
    }
    countDown();
    setInterval(countDown, 1000);
})();
</script>
@endif
