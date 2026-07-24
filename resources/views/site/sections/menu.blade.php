{{-- ═══════════════ MENU MOBILE ═══════════════ --}}
<div class="th-menu-wrapper">
    <div class="th-menu-area text-center">
        <button class="th-menu-toggle"><i class="fal fa-times"></i></button>
        <div class="mobile-logo">
            <img src="{{ asset('site/assets/img/custom/AKADI.png') }}" width="60px" alt="logo akadi">
        </div>
        <div class="th-mobile-menu">
            <ul>
                <li class="menu-item-has-children">
                    <a href="{{ route('page-acceuil') }}">Accueil</a>
                </li>
                @foreach ($categories as $item)
                    <li class="menu-item-has-children">
                        <a href="/produit?categorie={{ $item['id'] }}">{{ $item['name'] }}</a>
                        @if (count($item['subcategories']) > 0)
                            <ul class="sub-menu">
                                @foreach ($item['subcategories'] as $sub)
                                    <li><a href="/produit?sous-categorie={{ $sub['id'] }}">{{ $sub['name'] }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

{{-- ═══════════════ HEADER DESKTOP ═══════════════ --}}
<header class="ak-header">

    {{-- ── Barre supérieure ── --}}
    <div class="ak-topbar">
        <div class="container">
            <div class="ak-topbar-inner">
                <div class="ak-topbar-infos">
                    <a href="https://maps.app.goo.gl/sDP5zuFWbu4CLivk8" target="_blank" class="ak-info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Angré, derrière la pharmacie Arcade</span>
                    </a>
                    <span class="ak-info-divider"></span>
                    <a href="tel:+2250758838338" class="ak-info-item">
                        <i class="fas fa-phone-alt"></i>
                        <span>+225 07 58 83 83 38</span>
                    </a>
                    <span class="ak-info-divider"></span>
                    <span class="ak-info-item">
                        <i class="fas fa-clock"></i>
                        <span>10h30 – 18h00</span>
                    </span>
                </div>
                <div class="ak-topbar-social">
                    <a href="https://www.facebook.com/CvraimentDoux" target="_blank" class="ak-social-btn" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://wa.me/+2250758838338?text=Bonjour Akadi, je peux passer ma commande ?"
                       target="_blank" class="ak-social-btn ak-social-wa" title="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Navigation principale ── --}}
    <div class="ak-navbar sticky-wrapper">
        <div class="sticky-active">
            <div class="container">
                <div class="ak-nav-inner">

                    {{-- Hamburger mobile --}}
                    <button type="button" class="th-menu-toggle ak-hamburger d-lg-none" aria-label="Menu">
                        <span></span><span></span><span></span>
                    </button>

                    {{-- Logo --}}
                    <a href="{{ route('page-acceuil') }}" class="ak-logo">
                        <img src="{{ asset('site/assets/img/custom/AKADI.png') }}" width="64" height="64" alt="Akadi">
                    </a>

                    {{-- Nav desktop --}}
                    <nav class="ak-nav d-none d-lg-flex" aria-label="Navigation principale">
                        <a href="{{ route('page-acceuil') }}"
                           class="ak-nav-link {{ request()->routeIs('page-acceuil') ? 'ak-nav-active' : '' }}">
                            Accueil
                        </a>
                        @foreach ($categories as $item)
                            <div class="ak-nav-dropdown">
                                <a href="/produit?categorie={{ $item['id'] }}" class="ak-nav-link">
                                    {{ $item['name'] }}
                                    @if (count($item['subcategories']) > 0)
                                        <i class="fas fa-chevron-down ak-nav-arrow"></i>
                                    @endif
                                </a>
                                @if (count($item['subcategories']) > 0)
                                    <div class="ak-dropdown-menu">
                                        @foreach ($item['subcategories'] as $sub)
                                            <a href="/produit?sous-categorie={{ $sub['id'] }}" class="ak-dropdown-item">
                                                {{ $sub['name'] }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </nav>

                    {{-- Actions droite --}}
                    <div class="ak-actions">

                        {{-- Recherche --}}
                        <button class="ak-action-btn searchBoxToggler d-none d-lg-flex" aria-label="Recherche" title="Rechercher">
                            <i class="far fa-search"></i>
                        </button>

                        {{-- Compte --}}
                        @guest
                            <a href="{{ route('login') }}" class="ak-action-btn d-none d-lg-flex" title="Se connecter">
                                <i class="far fa-user"></i>
                            </a>
                        @endguest

                        @auth
                            <div class="ak-user-dropdown d-none d-lg-flex">
                                <button class="ak-action-btn ak-action-active" id="ak-user-btn" aria-label="Mon compte">
                                    <i class="far fa-user-check"></i>
                                </button>
                                <div class="ak-user-menu" id="ak-user-menu">
                                    <div class="ak-user-header">
                                        <div class="ak-user-avatar"><i class="fas fa-user"></i></div>
                                        <div>
                                            <strong>{{ Auth::user()->name }}</strong>
                                            <small>{{ Auth::user()->phone }}</small>
                                        </div>
                                    </div>
                                    <a href="{{ route('user-profil') }}" class="ak-user-item">
                                        <i class="far fa-user"></i> Mon compte
                                    </a>
                                    <a href="{{ route('user-order') }}" class="ak-user-item">
                                        <i class="far fa-list-alt"></i> Mes commandes
                                    </a>
                                    <div class="ak-user-divider"></div>
                                    <a href="{{ route('logout') }}" class="ak-user-item ak-user-logout">
                                        <i class="far fa-sign-out"></i> Déconnexion
                                    </a>
                                </div>
                            </div>
                        @endauth

                        {{-- Panier --}}
                        <a href="{{ route('panier') }}" class="ak-cart-btn" title="Mon panier">
                            <i class="far fa-shopping-bag"></i>
                            @php $qty = Session::get('totalQuantity') ?? 0; @endphp
                            <span class="ak-cart-badge badge" style="{{ $qty == 0 ? 'display:none' : '' }}">{{ $qty }}</span>
                        </a>

                        {{-- Commander CTA --}}
                        <a href="{{ route('liste-produit') }}" class="ak-cta-btn d-none d-lg-flex">
                            Commander <i class="fas fa-arrow-right"></i>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>

</header>

{{-- ─────────────────── STYLES HEADER ─────────────────── --}}
<style>
/* ── Variables brand ── */
:root {
    --ak-red:    #eb0029;
    --ak-orange: #f85d05;
    --ak-dark:   #1a0000;
    --ak-mid:    #3d0010;
}

/* ══ TOP BAR ══ */
.ak-topbar {
    background: var(--ak-dark);
    border-bottom: 2px solid var(--ak-red);
    padding: 8px 0;
}
.ak-topbar-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}
.ak-topbar-infos {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}
.ak-info-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: .75rem;
    color: rgba(255,255,255,.8);
    text-decoration: none;
    transition: color .2s;
    white-space: nowrap;
}
a.ak-info-item:hover { color: var(--ak-orange); text-decoration: none; }
.ak-info-item i { color: var(--ak-red); font-size: .72rem; }
.ak-info-divider {
    width: 1px;
    height: 14px;
    background: rgba(255,255,255,.15);
    flex-shrink: 0;
}
.ak-topbar-social { display: flex; align-items: center; gap: 6px; }
.ak-social-btn {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.12);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .72rem;
    color: rgba(255,255,255,.7);
    text-decoration: none;
    transition: all .2s;
}
.ak-social-btn:hover { background: var(--ak-red); border-color: var(--ak-red); color: #fff; text-decoration: none; }
.ak-social-wa:hover  { background: #25d366; border-color: #25d366; }
@media (max-width: 768px) {
    .ak-topbar { display: none; }
}

/* ══ NAVBAR ══ */
.ak-navbar {
    background: #fff;
    box-shadow: 0 2px 20px rgba(0,0,0,.08);
    position: relative;
    z-index: 999;
}
.sticky-active { width: 100%; }
.ak-nav-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    height: 72px;
}

/* ── Logo ── */
.ak-logo {
    flex-shrink: 0;
    display: flex;
    align-items: center;
    text-decoration: none;
}
.ak-logo img {
    height: 52px;
    width: auto;
    transition: transform .2s;
}
.ak-logo:hover img { transform: scale(1.04); }

/* ── Nav desktop ── */
.ak-nav {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 4px;
    justify-content: center;
}
.ak-nav-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: .88rem;
    font-weight: 600;
    color: #333;
    text-decoration: none;
    padding: 8px 14px;
    border-radius: 8px;
    position: relative;
    transition: all .2s;
    white-space: nowrap;
}
.ak-nav-link::after {
    content: '';
    position: absolute;
    bottom: 2px;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 2px;
    background: var(--ak-red);
    border-radius: 2px;
    transition: width .25s;
}
.ak-nav-link:hover, .ak-nav-active {
    color: var(--ak-red);
    text-decoration: none;
    background: rgba(235,0,41,.04);
}
.ak-nav-link:hover::after, .ak-nav-active::after { width: calc(100% - 28px); }
.ak-nav-arrow { font-size: .6rem; opacity: .6; }

/* ── Dropdown ── */
.ak-nav-dropdown {
    position: relative;
}
.ak-nav-dropdown:hover .ak-dropdown-menu { opacity: 1; visibility: visible; transform: translateY(0); }
.ak-dropdown-menu {
    position: absolute;
    top: calc(100% + 8px);
    left: 50%;
    transform: translateX(-50%) translateY(8px);
    min-width: 200px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 8px 32px rgba(0,0,0,.12);
    border: 1px solid #f0f0f0;
    padding: 6px;
    opacity: 0;
    visibility: hidden;
    transition: all .2s;
    z-index: 1000;
}
.ak-dropdown-menu::before {
    content: '';
    position: absolute;
    top: -6px;
    left: 50%;
    transform: translateX(-50%);
    border: 6px solid transparent;
    border-bottom-color: #fff;
    border-top: none;
}
.ak-dropdown-item {
    display: block;
    padding: 9px 14px;
    font-size: .84rem;
    font-weight: 500;
    color: #444;
    text-decoration: none;
    border-radius: 7px;
    transition: all .15s;
}
.ak-dropdown-item:hover {
    background: rgba(235,0,41,.06);
    color: var(--ak-red);
    text-decoration: none;
    padding-left: 18px;
}

/* ── Actions ── */
.ak-actions {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-shrink: 0;
}
.ak-action-btn {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    background: transparent;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .95rem;
    color: #444;
    cursor: pointer;
    transition: all .2s;
    text-decoration: none;
    position: relative;
}
.ak-action-btn:hover, .ak-action-active {
    background: rgba(235,0,41,.06);
    color: var(--ak-red);
    text-decoration: none;
}

/* Panier */
.ak-cart-btn {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    background: rgba(235,0,41,.06);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: .95rem;
    color: var(--ak-red);
    text-decoration: none;
    position: relative;
    transition: all .2s;
}
.ak-cart-btn:hover { background: var(--ak-red); color: #fff; text-decoration: none; }
.ak-cart-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    width: 18px;
    height: 18px;
    background: var(--ak-red);
    color: #fff;
    font-size: .6rem;
    font-weight: 800;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
    line-height: 1;
}
.ak-cart-btn:hover .ak-cart-badge { background: #fff; color: var(--ak-red); border-color: var(--ak-red); }

/* CTA commander */
.ak-cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    background: var(--ak-red);
    color: #fff;
    font-size: .82rem;
    font-weight: 700;
    padding: 10px 18px;
    border-radius: 8px;
    text-decoration: none;
    transition: all .2s;
    white-space: nowrap;
    margin-left: 4px;
}
.ak-cta-btn:hover {
    background: var(--ak-dark);
    color: #fff;
    text-decoration: none;
    transform: translateY(-1px);
}
.ak-cta-btn i { font-size: .7rem; transition: transform .2s; }
.ak-cta-btn:hover i { transform: translateX(3px); }

/* ── Hamburger mobile ── */
.ak-hamburger {
    background: none;
    border: 1.5px solid #e0e0e0;
    border-radius: 8px;
    width: 40px;
    height: 40px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 5px;
    cursor: pointer;
    padding: 8px;
    flex-shrink: 0;
}
.ak-hamburger span {
    display: block;
    width: 20px;
    height: 2px;
    background: #333;
    border-radius: 2px;
    transition: all .2s;
}
.ak-hamburger:hover { border-color: var(--ak-red); }
.ak-hamburger:hover span { background: var(--ak-red); }

/* ── Dropdown utilisateur ── */
.ak-user-dropdown { position: relative; }
.ak-user-menu {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    width: 240px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,.14);
    border: 1px solid #f0f0f0;
    padding: 8px;
    display: none;
    z-index: 1001;
}
.ak-user-menu.show { display: block; }
.ak-user-header {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 10px 12px;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 6px;
}
.ak-user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--ak-red), var(--ak-orange));
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: .9rem;
    flex-shrink: 0;
}
.ak-user-header strong { display: block; font-size: .85rem; color: #1a1a1a; line-height: 1.2; }
.ak-user-header small  { font-size: .75rem; color: #aaa; }
.ak-user-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 12px;
    font-size: .84rem;
    font-weight: 500;
    color: #444;
    text-decoration: none;
    border-radius: 8px;
    transition: all .15s;
}
.ak-user-item:hover { background: #f9f9f9; color: var(--ak-red); text-decoration: none; }
.ak-user-item i { width: 16px; text-align: center; color: #aaa; font-size: .85rem; }
.ak-user-item:hover i { color: var(--ak-red); }
.ak-user-divider { height: 1px; background: #f0f0f0; margin: 4px 0; }
.ak-user-logout { color: #dc3545 !important; }
.ak-user-logout i { color: #dc3545 !important; }
.ak-user-logout:hover { background: #fff5f5 !important; }

/* ── Sticky scroll effect ── */
.ak-navbar.is-sticky {
    box-shadow: 0 4px 24px rgba(0,0,0,.12);
}

/* ── Responsive mobile ── */
@media (max-width: 991px) {
    .ak-nav-inner { height: 60px; }
    .ak-logo img { height: 44px; }
    .ak-topbar-infos { display: none; }
    body { padding-bottom: 68px; }
}

/* ── Slider mobile : hauteur réduite + suppression espace blanc ── */
@media (max-width: 767px) {
    .sticky-wrapper { height: auto !important; }
    #slider.ls-wp-container {
        height: 160px !important;
        min-height: 160px !important;
        margin-top: 0 !important;
        margin-bottom: 0 !important;
    }
    #slider .ls-slide { height: 160px !important; }
    /* Supprimer l'espace au-dessus de la section produits */
    .ak-products-section { padding-top: 24px !important; }
    /* Supprimer tout gap résiduel après le slider */
    #slider + * { margin-top: 0 !important; }
}

/* ══ PANEL COMPTE MOBILE ══ */
.ak-bn-account-panel {
    position: fixed;
    bottom: 60px;
    left: 0; right: 0;
    background: #fff;
    border-radius: 18px 18px 0 0;
    box-shadow: 0 -8px 32px rgba(0,0,0,.15);
    z-index: 1049;
    transform: translateY(100%);
    transition: transform .28s cubic-bezier(0.4, 0, 0.2, 1);
    padding: 6px 8px 12px;
    padding-bottom: calc(12px + env(safe-area-inset-bottom, 0px));
}
.ak-bn-account-panel.open {
    transform: translateY(0);
}
.ak-bn-account-inner {
    max-width: 420px;
    margin: 0 auto;
}
/* Poignée en haut */
.ak-bn-account-inner::before {
    content: '';
    display: block;
    width: 36px;
    height: 4px;
    background: #e0e0e0;
    border-radius: 2px;
    margin: 6px auto 14px;
}
.ak-bn-account-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.35);
    z-index: 1048;
}
.ak-bn-account-backdrop.open { display: block; }

/* ══ BOTTOM NAV MOBILE ══ */
.ak-bottom-nav {
    position: fixed;
    bottom: 0; left: 0; right: 0;
    height: 60px;
    background: #fff;
    border-top: 1px solid #efefef;
    box-shadow: 0 -4px 20px rgba(0,0,0,.09);
    display: flex;
    align-items: stretch;
    z-index: 1050;
    padding-bottom: env(safe-area-inset-bottom, 0px);
}
.ak-bottom-nav-item {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 3px;
    color: #aaa;
    text-decoration: none;
    font-size: .6rem;
    font-weight: 700;
    letter-spacing: .02em;
    text-transform: uppercase;
    transition: color .15s;
    position: relative;
}
.ak-bottom-nav-item i {
    font-size: 1.15rem;
    line-height: 1;
}
/* Reset bouton natif */
button.ak-bottom-nav-item {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
    outline: none;
}
.ak-bottom-nav-item:hover,
.ak-bottom-nav-item.active {
    color: var(--ak-orange, #f85d05);
    text-decoration: none;
}
.ak-bottom-nav-item.active::before {
    content: '';
    position: absolute;
    top: 0; left: 20%; right: 20%;
    height: 2.5px;
    background: var(--ak-orange, #f85d05);
    border-radius: 0 0 3px 3px;
}
/* Bouton central panier mis en avant */
.ak-bottom-nav-item.ak-bn-cart {
    color: #fff;
}
.ak-bottom-nav-item.ak-bn-cart .ak-bn-cart-bubble {
    width: 44px;
    height: 44px;
    background: var(--ak-orange, #f85d05);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 14px rgba(248,93,5,.4);
    position: relative;
    margin-bottom: 2px;
    transition: transform .15s;
}
.ak-bottom-nav-item.ak-bn-cart:hover .ak-bn-cart-bubble { transform: scale(1.08); }
.ak-bottom-nav-item.ak-bn-cart i { font-size: 1.1rem; }
.ak-bn-badge {
    position: absolute;
    top: -3px; right: -3px;
    width: 17px; height: 17px;
    background: var(--ak-red, #eb0029);
    color: #fff;
    font-size: .55rem;
    font-weight: 800;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
    line-height: 1;
}
</style>

{{-- ═══════════════ BOTTOM NAV MOBILE ═══════════════ --}}
@php $qty = Session::get('totalQuantity') ?? 0; @endphp
<nav class="ak-bottom-nav d-lg-none">

    <a href="{{ route('page-acceuil') }}"
       class="ak-bottom-nav-item {{ request()->routeIs('page-acceuil') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
        <span>Accueil</span>
    </a>

    <a href="{{ route('liste-produit') }}"
       class="ak-bottom-nav-item {{ request()->routeIs('liste-produit') ? 'active' : '' }}">
        <i class="fas fa-utensils"></i>
        <span>Nos plats</span>
    </a>

    <a href="{{ route('panier') }}" class="ak-bottom-nav-item ak-bn-cart">
        <div class="ak-bn-cart-bubble">
            <i class="far fa-shopping-bag"></i>
            @if($qty > 0)
                <span class="ak-bn-badge badge">{{ $qty }}</span>
            @endif
        </div>
        <span style="color:#555;font-size:.6rem;font-weight:700;letter-spacing:.02em;">Panier</span>
    </a>

    <button type="button" class="ak-bottom-nav-item searchBoxToggler">
        <i class="far fa-search"></i>
        <span>Chercher</span>
    </button>

    @auth
        <button type="button" class="ak-bottom-nav-item {{ request()->routeIs('user-profil','user-order') ? 'active' : '' }}"
                id="ak-bn-account-btn">
            <i class="far fa-user-check"></i>
            <span>Compte</span>
        </button>
    @else
        <a href="{{ route('login') }}"
           class="ak-bottom-nav-item {{ request()->routeIs('login') ? 'active' : '' }}">
            <i class="far fa-user"></i>
            <span>Connexion</span>
        </a>
    @endauth

</nav>

{{-- ── Panel compte (mobile) ── --}}
@auth
<div class="ak-bn-account-panel" id="ak-bn-account-panel" aria-hidden="true">
    <div class="ak-bn-account-inner">
        <div class="ak-user-header">
            <div class="ak-user-avatar"><i class="fas fa-user"></i></div>
            <div>
                <strong>{{ Auth::user()->name }}</strong>
                <small>{{ Auth::user()->phone }}</small>
            </div>
        </div>
        <a href="{{ route('user-profil') }}" class="ak-user-item">
            <i class="far fa-user"></i> Mon compte
        </a>
        <a href="{{ route('user-order') }}" class="ak-user-item">
            <i class="far fa-list-alt"></i> Mes commandes
        </a>
        <div class="ak-user-divider"></div>
        <a href="{{ route('logout') }}" class="ak-user-item ak-user-logout">
            <i class="far fa-sign-out"></i> Déconnexion
        </a>
    </div>
</div>
<div class="ak-bn-account-backdrop" id="ak-bn-account-backdrop"></div>
@endauth

{{-- ─────────────────── SCRIPT HEADER ─────────────────── --}}
<script>
(function () {
    // Dropdown utilisateur (desktop)
    var btn = document.getElementById('ak-user-btn');
    var menu = document.getElementById('ak-user-menu');
    if (btn && menu) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            menu.classList.toggle('show');
        });
        document.addEventListener('click', function () {
            menu.classList.remove('show');
        });
    }

    // Panel compte (mobile bottom nav)
    var bnBtn      = document.getElementById('ak-bn-account-btn');
    var bnPanel    = document.getElementById('ak-bn-account-panel');
    var bnBackdrop = document.getElementById('ak-bn-account-backdrop');
    function openBnPanel() {
        if (bnPanel)    { bnPanel.classList.add('open'); bnPanel.setAttribute('aria-hidden','false'); }
        if (bnBackdrop) bnBackdrop.classList.add('open');
    }
    function closeBnPanel() {
        if (bnPanel)    { bnPanel.classList.remove('open'); bnPanel.setAttribute('aria-hidden','true'); }
        if (bnBackdrop) bnBackdrop.classList.remove('open');
    }
    if (bnBtn) {
        bnBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            bnPanel && bnPanel.classList.contains('open') ? closeBnPanel() : openBnPanel();
        });
    }
    if (bnBackdrop) bnBackdrop.addEventListener('click', closeBnPanel);
    document.addEventListener('keydown', function(e){ if(e.key === 'Escape') closeBnPanel(); });

    // Sticky shadow
    var navbar = document.querySelector('.ak-navbar');
    if (navbar) {
        window.addEventListener('scroll', function () {
            navbar.classList.toggle('is-sticky', window.scrollY > 60);
        });
    }
})();
</script>
