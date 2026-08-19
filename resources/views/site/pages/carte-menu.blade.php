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
.ak-cm-plat-card {
    background: #fff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    height: 100%;
    display: flex;
    flex-direction: column;
}
.ak-cm-plat-icon {
    aspect-ratio: 4/2.2;
    background: linear-gradient(135deg, var(--ak-orange,#f85d05), var(--ak-red,#eb0029));
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.8rem;
}
.ak-cm-plat-body { flex: 1; padding: 14px 16px; display: flex; flex-direction: column; gap: 4px; }
.ak-cm-plat-name { font-size: .92rem; font-weight: 700; color: #1a1a1a; }
.ak-cm-plat-desc { font-size: .78rem; color: #888; flex: 1; }
.ak-cm-plat-price { display: flex; align-items: baseline; gap: 8px; margin-top: 2px; }
.ak-cm-plat-price-normal { font-size: .95rem; font-weight: 800; color: #1a1a1a; }
.ak-cm-plat-price-reduit { font-size: .74rem; color: #11998e; font-weight: 600; }
.ak-cm-empty-day { color: #aaa; font-size: .85rem; font-style: italic; }

.ak-cm-plat-footer {
    padding: 10px 16px;
    border-top: 1px solid #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
}
.ak-cm-qty {
    display: flex;
    align-items: center;
    gap: 12px;
}
.ak-cm-qty-btn {
    width: 32px; height: 32px;
    border-radius: 8px;
    border: 1.5px solid #eee;
    background: #fff;
    color: var(--ak-red, #eb0029);
    font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: all .15s;
}
.ak-cm-qty-btn:hover { background: var(--ak-red, #eb0029); color: #fff; border-color: var(--ak-red, #eb0029); }
.ak-cm-qty-value { font-size: 1rem; font-weight: 800; min-width: 20px; text-align: center; }

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

<section class="ak-cm-section">
    <div class="container">

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
                        <div class="col-xl-3 col-lg-4 col-sm-6">
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
                                </div>
                                <div class="ak-cm-plat-footer">
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

        <div class="text-center mt-4">
            <a href="https://wa.me/+2250758838338?text=Bonjour Akadi, je suis intéressé(e) par le menu de la semaine « {{ $menuSemaine->titre_affiche }} »"
               target="_blank" class="btn"
               style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;background:var(--ak-orange,#f85d05);color:#fff;font-size:.9rem;font-weight:700;border-radius:8px;text-decoration:none;">
                <i class="fab fa-whatsapp"></i> Réserver via WhatsApp
            </a>
        </div>
    </div>
</section>

@php $nbLignesInitial = count($cartSummary['items']); @endphp
<div class="ak-cm-summary-bar {{ $nbLignesInitial > 0 ? 'show' : '' }}" id="ak-cm-summary-bar">
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
                <i class="far fa-shopping-bag"></i> Voir mon panier
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
