@extends('site.layouts.app')

@section('title', 'Panier — ' . $menuSemaine->titre_affiche)

@section('content')

<style>
.ak-ps-section { padding: 40px 0 64px; background: #fafafa; }
.ak-ps-day-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    padding: 18px 20px;
    margin-bottom: 18px;
}
.ak-ps-day-title { font-size: 1rem; font-weight: 800; color: #1a1a1a; margin-bottom: 12px; }
.ak-ps-line {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #f5f5f5;
}
.ak-ps-line:last-child { border-bottom: none; }
.ak-ps-line-name { font-size: .9rem; font-weight: 600; color: #1a1a1a; }
.ak-ps-line-qty { font-size: .8rem; color: #888; }
.ak-ps-line-total { font-size: .92rem; font-weight: 800; color: var(--ak-red,#eb0029); }
.ak-ps-summary {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 14px rgba(0,0,0,.08);
    padding: 24px;
    position: sticky;
    top: 100px;
}
.ak-ps-summary h3 { font-size: 1.05rem; font-weight: 800; margin-bottom: 16px; }
.ak-ps-summary-row { display: flex; justify-content: space-between; font-size: .88rem; color: #666; margin-bottom: 8px; }
.ak-ps-summary-row strong { color: #1a1a1a; }
.ak-ps-summary-total {
    display: flex; justify-content: space-between;
    font-size: 1.15rem; font-weight: 800; color: var(--ak-red,#eb0029);
    padding-top: 12px; margin-top: 8px; border-top: 1px solid #f0f0f0;
}
.ak-ps-tier-badge {
    display: inline-block;
    font-size: .72rem; font-weight: 700;
    padding: 4px 12px; border-radius: 50px;
    margin-bottom: 12px;
}
.ak-ps-tier-badge.reduit { background: rgba(17,153,142,.1); color: #11998e; }
.ak-ps-tier-badge.normal { background: rgba(248,93,5,.1); color: var(--ak-orange,#f85d05); }
.ak-ps-btn-pay {
    width: 100%;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 14px;
    background: var(--ak-orange, #f85d05);
    color: #fff;
    font-size: .92rem; font-weight: 700;
    border: none; border-radius: 8px;
    cursor: pointer;
    margin-top: 16px;
    transition: all .2s;
}
.ak-ps-btn-pay:hover { background: #d44d00; color: #fff; }
</style>

<div class="ak-breadcrumb">
    <div class="container">
        <h1 class="ak-breadcrumb-title">
            <span class="ak-breadcrumb-icon"><i class="far fa-shopping-bag"></i></span>
            Mon panier — {{ $menuSemaine->titre_affiche }}
        </h1>
        <ul class="ak-breadcrumb-nav">
            <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li><a href="{{ route('carte-menu', $menuSemaine->lien_token) }}">Carte menu</a></li>
            <li class="ak-breadcrumb-sep"><i class="fas fa-chevron-right"></i></li>
            <li class="active">Panier</li>
        </ul>
    </div>
</div>

<section class="ak-ps-section">
    <div class="container">
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row gy-4">
            <div class="col-lg-8">
                @foreach ($joursAffiches as $date => $lignes)
                    <div class="ak-ps-day-card">
                        <div class="ak-ps-day-title">
                            {{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('dddd D MMMM') }}
                        </div>
                        @foreach ($lignes as $ligne)
                            <div class="ak-ps-line">
                                <div>
                                    <div class="ak-ps-line-name">{{ $ligne['menu_produit']->nom }}</div>
                                    <div class="ak-ps-line-qty">{{ $ligne['quantity'] }} × {{ number_format($ligne['prix_unitaire'], 0, ',', ' ') }} FCFA</div>
                                </div>
                                <div class="ak-ps-line-total">{{ number_format($ligne['total'], 0, ',', ' ') }} FCFA</div>
                            </div>
                        @endforeach
                    </div>
                @endforeach

                <a href="{{ route('carte-menu', $menuSemaine->lien_token) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Continuer mes choix
                </a>
            </div>

            <div class="col-lg-4">
                <div class="ak-ps-summary">
                    <h3>Récapitulatif</h3>

                    <span class="ak-ps-tier-badge {{ $nombreJours >= $menuSemaine->seuil_jours ? 'reduit' : 'normal' }}">
                        {{ $nombreJours >= $menuSemaine->seuil_jours ? 'Tarif réduit appliqué' : 'Tarif normal' }}
                    </span>

                    <div class="ak-ps-summary-row">
                        <span>Jours sélectionnés</span>
                        <strong>{{ $nombreJours }}</strong>
                    </div>
                    <div class="ak-ps-summary-row">
                        <span>Prix moyen / plat</span>
                        <strong>{{ number_format($prixUnitaire, 0, ',', ' ') }} FCFA</strong>
                    </div>

                    @if ($nombreJours < $menuSemaine->seuil_jours)
                        <div class="ak-ps-summary-row" style="color:#11998e">
                            <span>💡 Ajoutez {{ $menuSemaine->seuil_jours - $nombreJours }} jour(s) de plus pour profiter automatiquement du tarif réduit sur chaque plat</span>
                        </div>
                    @endif

                    <div class="ak-ps-summary-total">
                        <span>Total</span>
                        <span>{{ number_format($montantTotal, 0, ',', ' ') }} FCFA</span>
                    </div>

                    <form action="{{ route('menu-semaine.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="ak-ps-btn-pay">
                            <i class="fas fa-lock"></i> Payer et réserver (Wave)
                        </button>
                    </form>
                    <small class="text-muted d-block mt-2 text-center">Le paiement est requis avant validation de la réservation.</small>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
