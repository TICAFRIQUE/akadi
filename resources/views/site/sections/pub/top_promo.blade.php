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
.ak-promo-dates {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: .85rem;
    color: #888;
    font-weight: 600;
}
.ak-promo-dates i { color: var(--ak-orange, #f85d05); }

.ak-promo-cta {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 16px 34px;
    background: linear-gradient(120deg, var(--ak-red, #eb0029), var(--ak-orange, #f85d05));
    color: #fff;
    font-size: .95rem;
    font-weight: 800;
    letter-spacing: .02em;
    border-radius: 50px;
    text-decoration: none;
    transition: all .2s;
    width: fit-content;
    box-shadow: 0 10px 26px rgba(235,0,41,.35);
    position: relative;
    overflow: hidden;
}
.ak-promo-cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: -60%;
    width: 40%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.35), transparent);
    transform: skewX(-20deg);
    animation: ak-promo-cta-shine 3s ease-in-out infinite;
}
@keyframes ak-promo-cta-shine {
    0%   { left: -60%; }
    35%  { left: 130%; }
    100% { left: 130%; }
}
.ak-promo-cta:hover { color: #fff; text-decoration: none; transform: translateY(-2px); box-shadow: 0 14px 30px rgba(235,0,41,.45); }
.ak-promo-cta i { font-size: .8rem; }

@media (prefers-reduced-motion: reduce) {
    .ak-promo-cta::before { animation: none; }
}

.ak-promo-bientot {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 22px;
    background: rgba(248,93,5,.08);
    color: var(--ak-orange, #f85d05);
    border: 1.5px solid rgba(248,93,5,.3);
    font-size: .88rem;
    font-weight: 700;
    border-radius: 8px;
    width: fit-content;
}
.ak-promo-bientot strong { color: var(--ak-red, #eb0029); }

.ak-promo-detail-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: .85rem;
    font-weight: 600;
    color: #777;
    text-decoration: none;
    margin-top: 4px;
    width: fit-content;
}
.ak-promo-detail-link:hover { color: var(--ak-red, #eb0029); text-decoration: none; }
</style>

@if ($top_promo)
<section class="ak-promo-section">
    <div class="container">

        {{-- Bannière compteur --}}
        @if ($top_promo['status_pub'] == 'bientot')
            <div class="ak-promo-banner">
                <i class="fas fa-clock"></i>
                Commence dans <span id="Promo-Timer-Debut">--</span>
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

                    @if ($top_promo->date_debut_pub && $top_promo->date_fin_pub)
                        <div class="ak-promo-dates">
                            <i class="fas fa-calendar-alt"></i>
                            Promo du {{ $top_promo->date_debut_pub->translatedFormat('d M') }}
                            au {{ $top_promo->date_fin_pub->translatedFormat('d M Y') }}
                        </div>
                    @endif

                    <div class="ak-promo-desc">{!! $top_promo['texte'] !!}</div>

                    @if ($top_promo['status_pub'] == 'en_cours')
                        <a href="{{ $top_promo['url'] }}" class="ak-promo-cta">
                            <i class="fas fa-shopping-cart"></i> {{ $top_promo['button_name'] ?: 'Voir et commander' }}
                        </a>
                    @elseif ($top_promo['status_pub'] == 'bientot')
                        <div class="ak-promo-bientot">
                            <i class="fas fa-clock"></i>
                            Disponible dans <strong id="Promo-Timer-Debut-2">--</strong>
                        </div>
                    @endif

                    <a href="{{ route('promo.show', $top_promo['id']) }}" class="ak-promo-detail-link">
                        Voir le détail de l'offre <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
(function () {
    var topPromo = @json($top_promo);
    if (!topPromo) return;

    function formatCompte(left) {
        var d = Math.floor(left / 86400);
        var h = Math.floor((left % 86400) / 3600);
        var m = Math.floor((left % 3600) / 60);
        var s = left % 60;
        return d + ' j : ' + h + ' h : ' + m + ' m : ' + s + ' s';
    }

    if (topPromo.status_pub === 'en_cours') {
        var timerEl = document.getElementById('Promo-Timer');
        if (!timerEl) return;
        var endTime = new Date(topPromo.date_fin_pub).getTime();
        (function countDown() {
            var left = Math.floor((endTime - Date.now()) / 1000);
            if (left <= 0) { timerEl.textContent = 'Terminé'; return; }
            timerEl.textContent = formatCompte(left);
            setTimeout(countDown, 1000);
        })();
    }

    if (topPromo.status_pub === 'bientot') {
        var timerDebutEls = [
            document.getElementById('Promo-Timer-Debut'),
            document.getElementById('Promo-Timer-Debut-2')
        ].filter(Boolean);
        if (!timerDebutEls.length) return;
        var startTime = new Date(topPromo.date_debut_pub).getTime();
        (function countDown() {
            var left = Math.floor((startTime - Date.now()) / 1000);
            if (left <= 0) {
                // La promo vient de démarrer : on recharge pour afficher l'état "en cours"
                // (bouton de commande, compte à rebours de fin, etc.).
                location.reload();
                return;
            }
            var texte = formatCompte(left);
            timerDebutEls.forEach(function (el) { el.textContent = texte; });
            setTimeout(countDown, 1000);
        })();
    }
})();
</script>
@endif
