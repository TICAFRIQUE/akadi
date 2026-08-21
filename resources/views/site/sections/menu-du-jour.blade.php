@if ($menuSemaineActive)
<style>
/* ── Bannière carte menu de la semaine ── */
.ak-menu-banner-section {
    padding: 48px 0;
    background: #fafafa;
}
.ak-menu-banner {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    background: linear-gradient(120deg, #1a0000 0%, #3d0010 45%, #1a0000 100%);
    min-height: 220px;
    display: flex;
    align-items: center;
    cursor: pointer;
    text-decoration: none;
    transition: transform .3s, box-shadow .3s;
    box-shadow: 0 8px 40px rgba(235,0,41,.22);
}
.ak-menu-banner:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 56px rgba(235,0,41,.3);
    text-decoration: none;
}

/* Motif SVG de fond */
.ak-menu-banner::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80'%3E%3Ccircle cx='40' cy='40' r='28' fill='none' stroke='%23ffffff' stroke-width='1' opacity='.04'/%3E%3Ccircle cx='40' cy='40' r='16' fill='none' stroke='%23ffffff' stroke-width='1' opacity='.03'/%3E%3Ccircle cx='0' cy='0' r='20' fill='none' stroke='%23f85d05' stroke-width='1' opacity='.06'/%3E%3Ccircle cx='80' cy='80' r='20' fill='none' stroke='%23f85d05' stroke-width='1' opacity='.06'/%3E%3C/svg%3E");
    background-repeat: repeat;
}

/* Lueur orange à droite */
.ak-menu-banner::after {
    content: '';
    position: absolute;
    right: -60px;
    top: 50%;
    transform: translateY(-50%);
    width: 360px;
    height: 360px;
    background: radial-gradient(circle, rgba(248,93,5,.35) 0%, transparent 70%);
    pointer-events: none;
}

.ak-menu-banner-body {
    position: relative;
    z-index: 2;
    padding: 40px 48px;
    flex: 1;
}
.ak-menu-banner-tag {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(248,93,5,.18);
    border: 1px solid rgba(248,93,5,.35);
    color: #f85d05;
    font-size: .72rem;
    font-weight: 800;
    letter-spacing: .12em;
    text-transform: uppercase;
    padding: 5px 14px;
    border-radius: 50px;
    margin-bottom: 16px;
}
.ak-menu-banner-title {
    font-size: clamp(1.5rem, 3.5vw, 2.4rem);
    font-weight: 900;
    color: #fff;
    line-height: 1.15;
    margin: 0 0 10px;
}
.ak-menu-banner-title .ak-accent {
    background: linear-gradient(90deg, #f85d05, #eb0029);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.ak-menu-banner-sub {
    font-size: .9rem;
    color: rgba(255,255,255,.65);
    margin: 0 0 28px;
    line-height: 1.5;
    max-width: 420px;
}
.ak-menu-banner-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: linear-gradient(90deg, #f85d05, #eb0029);
    color: #fff;
    font-size: .9rem;
    font-weight: 800;
    padding: 14px 28px;
    border-radius: 10px;
    text-decoration: none;
    box-shadow: 0 6px 20px rgba(248,93,5,.45);
    transition: all .2s;
    border: none;
    cursor: pointer;
}
.ak-menu-banner:hover .ak-menu-banner-btn {
    box-shadow: 0 10px 30px rgba(248,93,5,.6);
    transform: scale(1.03);
}
.ak-menu-banner-btn i { font-size: .8rem; transition: transform .2s; }
.ak-menu-banner:hover .ak-menu-banner-btn i { transform: translateX(4px); }

/* Déco droite (icônes flottants) */
.ak-menu-banner-deco {
    position: relative;
    z-index: 2;
    padding: 40px 56px 40px 0;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-shrink: 0;
}
.ak-menu-banner-icons {
    display: grid;
    grid-template-columns: repeat(2, 70px);
    grid-template-rows: repeat(2, 70px);
    gap: 12px;
}
.ak-menu-banner-icon-item {
    width: 70px;
    height: 70px;
    border-radius: 16px;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    backdrop-filter: blur(4px);
    animation: ak-float 3s ease-in-out infinite;
}
.ak-menu-banner-icon-item:nth-child(2) { animation-delay: .4s; }
.ak-menu-banner-icon-item:nth-child(3) { animation-delay: .8s; }
.ak-menu-banner-icon-item:nth-child(4) { animation-delay: 1.2s; }
@keyframes ak-float {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(-6px); }
}

/* Badge "disponible" */
.ak-menu-banner-available {
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 3;
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,.1);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.15);
    color: #fff;
    font-size: .72rem;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 50px;
}
.ak-menu-banner-dot {
    width: 7px;
    height: 7px;
    background: #22c55e;
    border-radius: 50%;
    animation: ak-pulse-dot 1.5s ease-in-out infinite;
}
@keyframes ak-pulse-dot {
    0%, 100% { box-shadow: 0 0 0 0 rgba(34,197,94,.5); }
    50%       { box-shadow: 0 0 0 5px rgba(34,197,94,0); }
}

@media (max-width: 767px) {
    .ak-menu-banner-deco { display: none; }
    .ak-menu-banner-body { padding: 32px 24px; }
    .ak-menu-banner-title { font-size: 1.5rem; }
    .ak-menu-banner-sub { font-size: .82rem; margin-bottom: 22px; }
    .ak-menu-banner-btn { font-size: .82rem; padding: 12px 22px; }
    .ak-menu-banner::after { width: 200px; height: 200px; }
    .ak-menu-banner-available { top: 14px; right: 14px; font-size: .68rem; }
}
</style>

<section class="ak-menu-banner-section">
    <div class="container">
        <a href="{{ route('carte-menu', $menuSemaineActive->lien_token) }}" class="ak-menu-banner">

            {{-- Badge disponible --}}
            <div class="ak-menu-banner-available">
                <span class="ak-menu-banner-dot"></span>
                Disponible cette semaine
            </div>

            {{-- Contenu principal --}}
            <div class="ak-menu-banner-body">
                <div class="ak-menu-banner-tag">
                    <i class="fas fa-calendar-week"></i>
                    Carte menu de la semaine
                </div>
                <h2 class="ak-menu-banner-title">
                    Découvrez nos plats<br>
                    <span class="ak-accent">préparés cette semaine</span>
                </h2>
                <p class="ak-menu-banner-sub">
                    Poulet fumé braisé, plats locaux revisités… Une sélection fraîche renouvelée chaque semaine, à commander dès maintenant.
                </p>
                <span class="ak-menu-banner-btn">
                    Voir la carte menu <i class="fas fa-arrow-right"></i>
                </span>
            </div>

            {{-- Déco icônes (desktop) --}}
            <div class="ak-menu-banner-deco">
                <div class="ak-menu-banner-icons">
                    <div class="ak-menu-banner-icon-item">🍗</div>
                    <div class="ak-menu-banner-icon-item">🥗</div>
                    <div class="ak-menu-banner-icon-item">🍱</div>
                    <div class="ak-menu-banner-icon-item">🔥</div>
                </div>
            </div>

        </a>
    </div>
</section>
@endif
