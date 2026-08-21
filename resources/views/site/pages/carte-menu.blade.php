@extends('site.layouts.app')

@section('title', $menuSemaine->titre_affiche)

@section('content')

<style>
.ak-cm-section { padding: 40px 0 96px; background: #fafafa; }
.ak-cm-pricing {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 14px rgba(0,0,0,.07);
    padding: 20px 24px;
    margin-bottom: 32px;
    display: flex;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
}
.ak-cm-pricing-item { display: flex; align-items: center; gap: 10px; }
.ak-cm-pricing-icon {
    width: 42px; height: 42px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.1rem; flex-shrink: 0;
}
.ak-cm-pricing-icon.normal { background: linear-gradient(135deg, var(--ak-orange,#f85d05), var(--ak-red,#eb0029)); }
.ak-cm-pricing-icon.reduit { background: linear-gradient(135deg, #11998e, #38ef7d); }
.ak-cm-pricing-label { font-size: .78rem; color: #888; margin: 0; }
.ak-cm-pricing-value { font-size: 1.1rem; font-weight: 800; color: #1a1a1a; margin: 0; }
.ak-cm-day-title {
    font-size: 1.15rem;
    font-weight: 800;
    color: #1a1a1a;
    margin: 32px 0 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.ak-cm-day-title .badge-count {
    font-size: .7rem;
    font-weight: 700;
    background: rgba(235,0,41,.08);
    color: var(--ak-red,#eb0029);
    padding: 3px 10px;
    border-radius: 50px;
}
/* Card horizontale : 2 blocs (mini vignette + infos) pour gagner de l'espace */
.ak-cm-plat-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    height: 100%;
    display: flex;
    align-items: stretch;
}
.ak-cm-plat-icon {
    width: 84px;
    flex-shrink: 0;
    background: linear-gradient(135deg, var(--ak-orange,#f85d05), var(--ak-red,#eb0029));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.4rem;
}
.ak-cm-plat-body {
    flex: 1;
    min-width: 0;
    padding: 10px 14px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 3px;
}
.ak-cm-plat-name {
    font-size: .86rem; font-weight: 700; color: #1a1a1a;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.ak-cm-plat-desc {
    font-size: .72rem; color: #888;
    overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.ak-cm-plat-price { display: flex; align-items: baseline; gap: 6px; flex-wrap: wrap; }
.ak-cm-plat-price-normal { font-size: .86rem; font-weight: 800; color: #1a1a1a; }
.ak-cm-plat-price-reduit { font-size: .66rem; color: #11998e; font-weight: 600; }
.ak-cm-empty-day { color: #aaa; font-size: .85rem; font-style: italic; }

.ak-cm-qty {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 4px;
}
.ak-cm-qty-btn {
    width: 26px; height: 26px;
    border-radius: 7px;
    border: 1.5px solid #eee;
    background: #fff;
    color: var(--ak-red, #eb0029);
    font-weight: 800;
    font-size: .78rem;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: all .15s;
}
.ak-cm-qty-btn:hover { background: var(--ak-red, #eb0029); color: #fff; border-color: var(--ak-red, #eb0029); }
.ak-cm-qty-value { font-size: .9rem; font-weight: 800; min-width: 16px; text-align: center; }

.ak-cm-summary-bar {
    position: fixed;
    left: 0; right: 0; bottom: 0;
    background: #fff;
    box-shadow: 0 -8px 32px rgba(0,0,0,.12);
    padding: 12px 0;
    z-index: 1040;
    transform: translateY(100%);
    transition: transform .3s cubic-bezier(0.4, 0, 0.2, 1);
}
.ak-cm-summary-bar.show { transform: translateY(0); }
/* Sur mobile/tablette, une bottom-nav fixe (60px) occupe déjà le bas de l'écran :
   sans ce décalage la barre panier se retrouve masquée derrière elle (quasi invisible). */
@media (max-width: 991px) {
    .ak-cm-summary-bar {
        bottom: calc(60px + env(safe-area-inset-bottom, 0px));
        box-shadow: 0 -4px 20px rgba(0,0,0,.14);
    }
}
.ak-cm-summary-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}
.ak-cm-summary-info { font-size: .82rem; color: #666; }
.ak-cm-summary-info strong { color: #1a1a1a; font-size: 1.05rem; }
.ak-cm-summary-tier {
    font-size: .72rem;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 50px;
    margin-left: 8px;
}
.ak-cm-summary-tier.normal { background: rgba(248,93,5,.1); color: var(--ak-orange,#f85d05); }
.ak-cm-summary-tier.reduit { background: rgba(17,153,142,.1); color: #11998e; }
.ak-cm-summary-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 24px;
    background: var(--ak-orange, #f85d05);
    color: #fff;
    font-size: .88rem;
    font-weight: 700;
    border-radius: 8px;
    text-decoration: none;
    white-space: nowrap;
}
.ak-cm-summary-btn:hover { background: #d44d00; color: #fff; text-decoration: none; }

/* ── Sidebar panier (desktop) : plus visible qu'une barre en bas d'écran ── */
.ak-cm-sidebar {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 14px rgba(0,0,0,.08);
    padding: 24px;
}
/* Épinglée en position fixe (calculée en JS) : ne bouge plus du tout au scroll, contrairement à "sticky" */
.ak-cm-sidebar.is-pinned {
    position: fixed;
    z-index: 20;
}
.ak-cm-sidebar h3 { font-size: 1.05rem; font-weight: 800; margin: 0 0 16px; color: #1a1a1a; }
.ak-cm-sidebar-empty { text-align: center; color: #aaa; padding: 12px 0 4px; }
.ak-cm-sidebar-empty i { font-size: 1.8rem; margin-bottom: 10px; display: block; color: #ddd; }
.ak-cm-sidebar-empty p { font-size: .85rem; margin: 0; }
.ak-cm-sidebar-tier {
    display: inline-block;
    font-size: .72rem; font-weight: 700;
    padding: 4px 12px; border-radius: 50px;
    margin-bottom: 16px;
}
.ak-cm-sidebar-tier.reduit { background: rgba(17,153,142,.1); color: #11998e; }
.ak-cm-sidebar-tier.normal { background: rgba(248,93,5,.1); color: var(--ak-orange,#f85d05); }
.ak-cm-sidebar-row { display: flex; justify-content: space-between; font-size: .88rem; color: #666; margin-bottom: 10px; }
.ak-cm-sidebar-row strong { color: #1a1a1a; }
.ak-cm-sidebar-total {
    display: flex; justify-content: space-between;
    font-size: 1.2rem; font-weight: 800; color: var(--ak-red,#eb0029);
    padding-top: 14px; margin-top: 6px; border-top: 1px solid #f0f0f0;
}
.ak-cm-sidebar-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%;
    padding: 13px;
    background: var(--ak-orange, #f85d05);
    color: #fff;
    font-size: .9rem; font-weight: 700;
    border-radius: 8px;
    text-decoration: none;
    margin-top: 18px;
    transition: all .2s;
}
.ak-cm-sidebar-btn:hover { background: #d44d00; color: #fff; text-decoration: none; }
</style>

{{-- ── Breadcrumb ── --}}
<div class="ak-breadcrumb">
    <div class="container">
        <h1 class="ak-breadcrumb-title">
            <span class="ak-breadcrumb-icon"><i class="fas fa-calendar-week"></i></span>
            {{ $menuSemaine->titre_affiche }}
        </h1>
        <ul class="ak-breadcrumb-nav">
            <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li class="active">Carte menu de la semaine</li>
        </ul>
    </div>
</div>

@php $nbLignesInitial = count($cartSummary['items']); @endphp

<section class="ak-cm-section">
    <div class="container">
    <div class="row gy-4">
    <div class="col-lg-8">

        <div class="ak-cm-pricing">
            <div class="ak-cm-pricing-item">
                <div class="ak-cm-pricing-icon reduit"><i class="fas fa-tags"></i></div>
                <div>
                    <p class="ak-cm-pricing-label">Tarif réduit automatique</p>
                    <p class="ak-cm-pricing-value">Dès {{ $menuSemaine->seuil_jours }} jour(s) commandés</p>
                </div>
            </div>
        </div>

        @forelse ($menuSemaine->menusJour as $menuJour)
            @php $dateStr = \Illuminate\Support\Carbon::parse($menuJour->date)->format('Y-m-d'); @endphp
            <h2 class="ak-cm-day-title">
                {{ \Carbon\Carbon::parse($menuJour->date)->locale('fr')->isoFormat('dddd D MMMM') }}
                <span class="badge-count">{{ $menuJour->menuProduits->count() }} plat(s)</span>
            </h2>

            @if ($menuJour->menuProduits->count() > 0)
                <div class="row gy-4">
                    @foreach ($menuJour->menuProduits as $plat)
                        <div class="col-xl-4 col-lg-6 col-sm-6">
                            <div class="ak-cm-plat-card">
                                <div class="ak-cm-plat-icon"><i class="fas fa-utensils"></i></div>
                                <div class="ak-cm-plat-body">
                                    <div class="ak-cm-plat-name">{{ $plat->nom }}</div>
                                    @if ($plat->description)
                                        <div class="ak-cm-plat-desc">{{ $plat->description }}</div>
                                    @endif
                                    <div class="ak-cm-plat-price">
                                        <span class="ak-cm-plat-price-normal">{{ number_format($plat->prix_normal, 0, ',', ' ') }} FCFA</span>
                                        <span class="ak-cm-plat-price-reduit">{{ number_format($plat->prix_reduit, 0, ',', ' ') }} F dès {{ $menuSemaine->seuil_jours }}j</span>
                                    </div>
                                    <div class="ak-cm-qty" data-date="{{ $dateStr }}" data-menu-produit-id="{{ $plat->id }}">
                                        <button type="button" class="ak-cm-qty-btn ak-cm-qty-minus"><i class="fas fa-minus"></i></button>
                                        <span class="ak-cm-qty-value">{{ $cart[$dateStr][$plat->id] ?? 0 }}</span>
                                        <button type="button" class="ak-cm-qty-btn ak-cm-qty-plus"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="ak-cm-empty-day">Aucun plat prévu ce jour.</p>
            @endif
        @empty
            <p class="text-center text-muted">Aucun jour disponible pour cette semaine.</p>
        @endforelse

    </div>

    {{-- Sidebar panier : visible uniquement sur desktop (la barre du bas prend le relais sur mobile) --}}
    <div class="col-lg-4 d-none d-lg-block" id="ak-cm-sidebar-wrap">
        <div class="ak-cm-sidebar" id="ak-cm-sidebar-box">
            <div class="ak-cm-sidebar-empty" id="ak-cm-sidebar-empty" style="{{ $nbLignesInitial > 0 ? 'display:none' : '' }}">
                <i class="far fa-shopping-bag"></i>
                <p>Sélectionnez des plats pour voir votre récapitulatif ici.</p>
            </div>
            <div class="ak-cm-sidebar-content" id="ak-cm-sidebar-content" style="{{ $nbLignesInitial > 0 ? '' : 'display:none' }}">
                <h3>Mon panier Menu</h3>
                <span class="ak-cm-sidebar-tier {{ $cartSummary['nombreJours'] >= $menuSemaine->seuil_jours ? 'reduit' : 'normal' }}" id="ak-cm-sidebar-tier">
                    {{ $cartSummary['nombreJours'] >= $menuSemaine->seuil_jours ? 'Tarif réduit' : 'Tarif normal' }}
                </span>
                <div class="ak-cm-sidebar-row">
                    <span>Jours sélectionnés</span>
                    <strong id="ak-cm-sidebar-jours">{{ $cartSummary['nombreJours'] }}</strong>
                </div>
                <div class="ak-cm-sidebar-total">
                    <span>Total</span>
                    <span id="ak-cm-sidebar-total">{{ number_format($cartSummary['montantTotal'], 0, ',', ' ') }} FCFA</span>
                </div>
                <a href="{{ route('menu-semaine.panier') }}" class="ak-cm-sidebar-btn">
                    <i class="far fa-shopping-bag"></i> Voir mon panier menu
                </a>
            </div>
        </div>
    </div>

    </div>
    </div>
</section>

<div class="ak-cm-summary-bar d-lg-none {{ $nbLignesInitial > 0 ? 'show' : '' }}" id="ak-cm-summary-bar">
    <div class="container">
        <div class="ak-cm-summary-inner">
            <div class="ak-cm-summary-info">
                <strong id="ak-cm-summary-total">{{ number_format($cartSummary['montantTotal'], 0, ',', ' ') }} FCFA</strong>
                — <span id="ak-cm-summary-jours">{{ $cartSummary['nombreJours'] }}</span> jour(s) sélectionné(s)
                <span class="ak-cm-summary-tier {{ $cartSummary['nombreJours'] >= $menuSemaine->seuil_jours ? 'reduit' : 'normal' }}" id="ak-cm-summary-tier">
                    {{ $cartSummary['nombreJours'] >= $menuSemaine->seuil_jours ? 'Tarif réduit' : 'Tarif normal' }}
                </span>
            </div>
            <a href="{{ route('menu-semaine.panier') }}" class="ak-cm-summary-btn">
                <i class="far fa-shopping-bag"></i> Voir mon panier menu
            </a>
        </div>
    </div>
</div>

<script>
(function () {
    const menuSemaineId = {{ $menuSemaine->id }};
    const seuilJours = {{ $menuSemaine->seuil_jours }};
    const addToCartUrl = "{{ route('menu-semaine.add-to-cart') }}";
    const summaryBar = document.getElementById('ak-cm-summary-bar');
    const summaryTotal = document.getElementById('ak-cm-summary-total');
    const summaryJours = document.getElementById('ak-cm-summary-jours');
    const summaryTier = document.getElementById('ak-cm-summary-tier');

    const sidebarEmpty = document.getElementById('ak-cm-sidebar-empty');
    const sidebarContent = document.getElementById('ak-cm-sidebar-content');
    const sidebarTotal = document.getElementById('ak-cm-sidebar-total');
    const sidebarJours = document.getElementById('ak-cm-sidebar-jours');
    const sidebarTier = document.getElementById('ak-cm-sidebar-tier');

    // ── Sidebar épinglée (position fixe, ne bouge jamais au scroll) ──
    const sidebarWrap = document.getElementById('ak-cm-sidebar-wrap');
    const sidebarBox = document.getElementById('ak-cm-sidebar-box');
    const siteFooter = document.querySelector('.ak-footer');

    const PIN_OFFSET = 110;

    function pinSidebar() {
        if (!sidebarWrap || !sidebarBox) return;
        if (window.innerWidth < 992) {
            sidebarBox.classList.remove('is-pinned');
            sidebarBox.style.top = '';
            sidebarBox.style.left = '';
            sidebarBox.style.width = '';
            return;
        }
        const rect = sidebarWrap.getBoundingClientRect();
        // Reste dans le flux normal tant qu'on n'a pas encore scrollé jusqu'à sa position :
        // évite qu'elle chevauche le bandeau d'en-tête au chargement de la page.
        if (rect.top <= PIN_OFFSET) {
            sidebarBox.classList.add('is-pinned');
            sidebarBox.style.top = PIN_OFFSET + 'px';
            sidebarBox.style.left = rect.left + 'px';
            sidebarBox.style.width = rect.width + 'px';
        } else {
            sidebarBox.classList.remove('is-pinned');
            sidebarBox.style.top = '';
            sidebarBox.style.left = '';
            sidebarBox.style.width = '';
        }
    }

    pinSidebar();
    window.addEventListener('scroll', pinSidebar, { passive: true });
    window.addEventListener('resize', pinSidebar);

    // Évite de recouvrir le pied de page : on masque la sidebar épinglée dès que le footer apparaît à l'écran.
    if (siteFooter && sidebarBox && 'IntersectionObserver' in window) {
        new IntersectionObserver((entries) => {
            sidebarBox.style.visibility = entries[0].isIntersecting ? 'hidden' : 'visible';
        }).observe(siteFooter);
    }

    function formatFcfa(n) {
        return Number(n).toLocaleString('fr-FR').replace(/ /g, ' ') + ' FCFA';
    }

    function updateSummary(data) {
        if (data.nombre_lignes > 0) {
            summaryBar.classList.add('show');
        } else {
            summaryBar.classList.remove('show');
        }
        summaryTotal.textContent = formatFcfa(data.montant_total);
        summaryJours.textContent = data.nombre_jours;
        const isReduit = data.nombre_jours >= seuilJours;
        summaryTier.textContent = isReduit ? 'Tarif réduit' : 'Tarif normal';
        summaryTier.className = 'ak-cm-summary-tier ' + (isReduit ? 'reduit' : 'normal');

        // Sidebar desktop
        if (sidebarEmpty && sidebarContent) {
            sidebarEmpty.style.display = data.nombre_lignes > 0 ? 'none' : '';
            sidebarContent.style.display = data.nombre_lignes > 0 ? '' : 'none';
        }
        if (sidebarTotal) sidebarTotal.textContent = formatFcfa(data.montant_total);
        if (sidebarJours) sidebarJours.textContent = data.nombre_jours;
        if (sidebarTier) {
            sidebarTier.textContent = isReduit ? 'Tarif réduit' : 'Tarif normal';
            sidebarTier.className = 'ak-cm-sidebar-tier ' + (isReduit ? 'reduit' : 'normal');
        }
    }

    function sendQuantity(date, menuProduitId, quantity) {
        const params = new URLSearchParams({
            menu_semaine_id: menuSemaineId,
            date: date,
            menu_produit_id: menuProduitId,
            quantity: quantity,
        });
        fetch(addToCartUrl + '?' + params.toString(), {
            method: 'GET',
            headers: { 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(data => updateSummary(data))
        .catch(() => {});
    }

    document.querySelectorAll('.ak-cm-qty').forEach(el => {
        const date = el.dataset.date;
        const menuProduitId = el.dataset.menuProduitId;
        const valueEl = el.querySelector('.ak-cm-qty-value');

        el.querySelector('.ak-cm-qty-plus').addEventListener('click', function () {
            const newQty = parseInt(valueEl.textContent, 10) + 1;
            valueEl.textContent = newQty;
            sendQuantity(date, menuProduitId, newQty);
        });

        el.querySelector('.ak-cm-qty-minus').addEventListener('click', function () {
            const newQty = Math.max(0, parseInt(valueEl.textContent, 10) - 1);
            valueEl.textContent = newQty;
            sendQuantity(date, menuProduitId, newQty);
        });
    });
})();
</script>

@endsection
