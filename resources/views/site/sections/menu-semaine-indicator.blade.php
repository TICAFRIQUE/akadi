@if (($cartMenuSemaineJours ?? 0) > 0)
<style>
.ak-msi-bar {
    position: fixed;
    left: 0; right: 0; bottom: 0;
    background: linear-gradient(90deg, var(--ak-orange,#f85d05), var(--ak-red,#eb0029));
    box-shadow: 0 -6px 24px rgba(0,0,0,.18);
    padding: 10px 0;
    z-index: 1045;
}
/* Sur mobile/tablette, la bottom-nav (60px) occupe déjà le bas de l'écran. */
@media (max-width: 991px) {
    .ak-msi-bar {
        bottom: calc(60px + env(safe-area-inset-bottom, 0px));
        box-shadow: 0 -4px 18px rgba(0,0,0,.2);
    }
}
.ak-msi-inner {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    flex-wrap: wrap;
    text-align: center;
}
.ak-msi-text { color: #fff; font-size: .84rem; font-weight: 600; margin: 0; }
.ak-msi-text strong { font-weight: 800; }
.ak-msi-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff;
    color: var(--ak-red, #eb0029);
    font-size: .78rem;
    font-weight: 800;
    padding: 8px 18px;
    border-radius: 50px;
    text-decoration: none;
    white-space: nowrap;
    transition: all .2s;
}
.ak-msi-btn:hover { background: var(--ak-dark, #1a0000); color: #fff; text-decoration: none; }
@media (max-width: 576px) {
    .ak-msi-text { font-size: .76rem; flex-basis: 100%; }
}
</style>
<div class="ak-msi-bar">
    <div class="container">
        <div class="ak-msi-inner">
            <p class="ak-msi-text">
                <i class="fas fa-calendar-week"></i>
                Vous avez aussi <strong>{{ $cartMenuSemaineJours }} jour(s)</strong> sélectionné(s) sur la carte menu de la semaine, en attente.
            </p>
            <a href="{{ route('menu-semaine.panier') }}" class="ak-msi-btn">
                Voir ce panier <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
@endif
