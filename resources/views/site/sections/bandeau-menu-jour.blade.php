@php
    $totalPlatsSemaine = $menuSemaineActive
        ? $menuSemaineActive->menusJour->sum(fn ($j) => $j->menuProduits->count())
        : 0;
@endphp
@if ($menuSemaineActive && $totalPlatsSemaine > 0)
<style>
.ak-mdj-banner {
    background: var(--ak-gradient, linear-gradient(90deg, #eb0029, #f85d05));
    padding: 12px 0;
    position: relative;
    overflow: hidden;
    animation: ak-mdj-banner-slide-in .5s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
}
@keyframes ak-mdj-banner-slide-in {
    from { transform: translateY(-100%); opacity: 0; }
    to   { transform: translateY(0); opacity: 1; }
}
/* Reflet lumineux qui balaie le bandeau pour signaler une annonce active */
.ak-mdj-banner::before {
    content: '';
    position: absolute;
    top: 0;
    left: -60%;
    width: 40%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.28), transparent);
    transform: skewX(-20deg);
    animation: ak-mdj-banner-shine 3.2s ease-in-out infinite;
    animation-delay: 1s;
    pointer-events: none;
}
@keyframes ak-mdj-banner-shine {
    0%   { left: -60%; }
    35%  { left: 130%; }
    100% { left: 130%; }
}
.ak-mdj-banner-inner {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    flex-wrap: wrap;
    text-align: center;
    position: relative;
    z-index: 1;
}
.ak-mdj-banner-icon {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: rgba(255,255,255,.18);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1rem;
    flex-shrink: 0;
    position: relative;
    animation: ak-mdj-banner-pulse 2s ease-in-out infinite;
}
@keyframes ak-mdj-banner-pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.12); }
}
/* Petit point "live" clignotant, façon notification, dans le coin de l'icône */
.ak-mdj-banner-icon::after {
    content: '';
    position: absolute;
    top: -1px;
    right: -1px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 0 0 2px var(--ak-red, #eb0029);
    animation: ak-mdj-banner-blink 1.4s ease-in-out infinite;
}
@keyframes ak-mdj-banner-blink {
    0%, 100% { opacity: 1; transform: scale(1); }
    50%      { opacity: .35; transform: scale(.8); }
}
.ak-mdj-banner-text {
    color: #fff;
    font-size: .92rem;
    font-weight: 600;
    margin: 0;
}
.ak-mdj-banner-text strong { font-weight: 800; }
.ak-mdj-banner-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: #fff;
    color: var(--ak-red, #eb0029);
    font-size: .82rem;
    font-weight: 800;
    padding: 8px 18px;
    border-radius: 50px;
    text-decoration: none;
    white-space: nowrap;
    transition: all .2s;
    box-shadow: 0 2px 10px rgba(0,0,0,.15);
}
.ak-mdj-banner-btn:hover {
    background: var(--ak-dark, #1a0000);
    color: #fff;
    text-decoration: none;
    transform: translateY(-1px);
}

@media (max-width: 576px) {
    .ak-mdj-banner { padding: 10px 0; }
    .ak-mdj-banner-inner { gap: 8px; }
    .ak-mdj-banner-icon { width: 28px; height: 28px; font-size: .85rem; }
    .ak-mdj-banner-text { font-size: .78rem; flex-basis: 100%; }
    .ak-mdj-banner-btn { font-size: .74rem; padding: 7px 14px; }
}

@media (prefers-reduced-motion: reduce) {
    .ak-mdj-banner,
    .ak-mdj-banner::before,
    .ak-mdj-banner-icon,
    .ak-mdj-banner-icon::after {
        animation: none;
    }
}
</style>

<div class="ak-mdj-banner">
    <div class="container">
        <div class="ak-mdj-banner-inner">
            <div class="ak-mdj-banner-icon"><i class="fas fa-utensils"></i></div>
            <p class="ak-mdj-banner-text">
                <strong>Carte menu de la semaine disponible !</strong>
                {{ $totalPlatsSemaine }} plat{{ $totalPlatsSemaine > 1 ? 's' : '' }} à découvrir.
            </p>
            <a href="{{ route('carte-menu', $menuSemaineActive->lien_token) }}" class="ak-mdj-banner-btn">
                Voir la carte menu <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
@endif
