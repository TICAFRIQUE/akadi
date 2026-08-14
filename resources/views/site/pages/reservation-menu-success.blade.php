@extends('site.layouts.app')

@section('title', 'Réservation confirmée')

@section('content')

<style>
.ak-rs-section { padding: 56px 0 64px; background: #fafafa; }
.ak-rs-header { text-align: center; margin-bottom: 32px; }
.ak-rs-icon {
    width: 72px; height: 72px; border-radius: 50%;
    background: linear-gradient(135deg, #11998e, #38ef7d);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.8rem;
    margin: 0 auto 16px;
}
.ak-rs-header h1 { font-size: 1.4rem; font-weight: 800; color: #1a1a1a; }
.ak-rs-header p { color: #888; font-size: .9rem; }
.ak-rs-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 14px rgba(0,0,0,.07);
    padding: 24px;
    margin-bottom: 20px;
}
.ak-rs-day-title { font-size: .95rem; font-weight: 800; color: #1a1a1a; margin-bottom: 10px; display: flex; align-items: center; gap: 8px; justify-content: space-between; }
.ak-rs-line { display: flex; justify-content: space-between; font-size: .85rem; color: #666; padding: 4px 0; }
.ak-rs-total { display: flex; justify-content: space-between; font-size: 1.1rem; font-weight: 800; color: var(--ak-red,#eb0029); padding-top: 12px; margin-top: 8px; border-top: 1px solid #f0f0f0; }
</style>

<section class="ak-rs-section">
    <div class="container" style="max-width:720px">
        <div class="ak-rs-header">
            <div class="ak-rs-icon"><i class="fas fa-check"></i></div>
            <h1>Réservation confirmée !</h1>
            <p>Merci {{ $reservation->client_name }}, votre menu de la semaine « {{ $reservation->menuSemaine->titre }} » est réservé.</p>
        </div>

        @foreach ($joursAffiches as $date => $lignes)
            @php $order = $reservation->orders->firstWhere('date_order', $date); @endphp
            <div class="ak-rs-card">
                <div class="ak-rs-day-title">
                    <span>{{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('dddd D MMMM') }}</span>
                    @if ($order)
                        <span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
                    @endif
                </div>
                @foreach ($lignes as $ligne)
                    <div class="ak-rs-line">
                        <span>{{ $ligne->quantity }} × {{ $ligne->menuProduit->nom }}</span>
                        <span>{{ number_format($ligne->quantity * $ligne->prix_unitaire, 0, ',', ' ') }} FCFA</span>
                    </div>
                @endforeach
            </div>
        @endforeach

        <div class="ak-rs-card">
            <div class="ak-rs-total">
                <span>Total payé</span>
                <span>{{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('page-acceuil') }}" class="btn" style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;background:var(--ak-orange,#f85d05);color:#fff;font-size:.9rem;font-weight:700;border-radius:8px;text-decoration:none;">
                <i class="fas fa-home"></i> Retour à l'accueil
            </a>
        </div>
    </div>
</section>

@endsection
