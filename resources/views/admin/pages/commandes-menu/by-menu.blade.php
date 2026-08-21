@extends('admin.layouts.app')
@section('title', 'Commandes — ' . $menuSemaine->titre_affiche)
@section('sub-title', $menuSemaine->titre_affiche)

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Commandes — {{ $menuSemaine->titre_affiche }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('commandes-menu.index') }}">Commandes Menu</a></div>
            <div class="breadcrumb-item">{{ $menuSemaine->titre_affiche }}</div>
        </div>
    </div>

    <div class="section-body">

        {{-- ── KPIs ── --}}
        <div class="row mb-4">
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center mb-0" style="border-top:3px solid #6366f1;">
                    <div class="card-body py-3">
                        <div style="font-size:2rem;font-weight:800;color:#6366f1;">{{ $kpis['total'] }}</div>
                        <div class="text-muted" style="font-size:.82rem;">Réservations</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center mb-0" style="border-top:3px solid #22c55e;">
                    <div class="card-body py-3">
                        <div style="font-size:2rem;font-weight:800;color:#22c55e;">{{ $kpis['jours_livres'] }}</div>
                        <div class="text-muted" style="font-size:.82rem;">Jours livrés</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center mb-0" style="border-top:3px solid #f59e0b;">
                    <div class="card-body py-3">
                        <div style="font-size:2rem;font-weight:800;color:#f59e0b;">{{ $kpis['jours_restants'] }}</div>
                        <div class="text-muted" style="font-size:.82rem;">Reste à livrer</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center mb-0" style="border-top:3px solid #1d4ed8;">
                    <div class="card-body py-3">
                        <div style="font-size:1.4rem;font-weight:800;color:#1d4ed8;">{{ number_format($kpis['ca'], 0, ',', ' ') }} F</div>
                        <div class="text-muted" style="font-size:.82rem;">CA encaissé</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Info menu ── --}}
        <div class="card mb-4">
            <div class="card-body py-3">
                <div class="row">
                    <div class="col-md-4">
                        <small class="text-muted d-block">Période</small>
                        <strong>{{ $menuSemaine->date_debut->format('d/m/Y') }} → {{ $menuSemaine->date_fin->format('d/m/Y') }}</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Tarif réduit dès</small>
                        <strong>{{ $menuSemaine->seuil_jours }} jour(s) commandés</strong>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block">Statut</small>
                        @if($menuSemaine->actif)
                            <span class="badge badge-success">Actif</span>
                        @else
                            <span class="badge badge-secondary">Inactif</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Tableau des réservations par client ── --}}
        <div class="card">
            <div class="card-header">
                <h4>Commandes par client</h4>
                <div class="card-header-action">
                    <a href="{{ route('commandes-menu.preparation', $menuSemaine) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-utensils mr-1"></i> Fiche préparation
                    </a>
                    <a href="{{ route('commandes-menu.download-clients', $menuSemaine) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-pdf mr-1"></i> Liste clients (PDF)
                    </a>
                    <a href="{{ route('menu-semaine.pdf', $menuSemaine) }}" class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-file-pdf mr-1"></i> PDF carte menu
                    </a>
                    <a href="{{ route('carte-menu', $menuSemaine->lien_token) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-share-alt mr-1"></i> Carte menu
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Client</th>
                                <th>Jours</th>
                                <th>Montant</th>
                                <th>Mode livraison</th>
                                <th>Paiement</th>
                                <th>Statut</th>
                                <th>Livraison</th>
                                <th>Date</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservations as $reservation)
                                @php
                                    $totalJours  = $reservation->orders->count();
                                    $joursLivres = $reservation->orders->where('status', \App\Models\Order::STATUS_LIVREE)->count();
                                @endphp
                                <tr>
                                    <td class="text-muted">{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $reservation->client_name }}</strong>
                                        <br><small class="text-muted">{{ $reservation->client_phone }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-light">{{ $reservation->nombre_jours }} jour(s)</span>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA</strong>
                                    </td>
                                    <td>
                                        @if($reservation->mode_livraison === 'yango')
                                            <span class="badge badge-info">Yango</span>
                                            @if($reservation->address_yango)
                                                <br><small class="text-muted">{{ Str::limit($reservation->address_yango, 30) }}</small>
                                            @endif
                                        @else
                                            <span class="badge badge-secondary">Sur place</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $reservation->paymentMethod->name ?? '—' }}
                                    </td>
                                    <td>
                                        @if($reservation->statut === 'payee')
                                            <span class="badge badge-success">Payée</span>
                                        @elseif($reservation->statut === 'annulee')
                                            <span class="badge badge-danger">Annulée</span>
                                        @else
                                            <span class="badge badge-warning">En attente</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($totalJours > 0)
                                            <span class="badge badge-{{ $joursLivres === $totalJours ? 'success' : 'secondary' }}">
                                                {{ $joursLivres }}/{{ $totalJours }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $reservation->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('commandes-menu.show', $reservation) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Voir le détail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-5">
                                        Aucune commande pour ce menu.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <a href="{{ route('commandes-menu.index') }}" class="btn btn-secondary mt-3">
            <i class="fas fa-arrow-left mr-1"></i> Retour aux menus
        </a>
    </div>
</section>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
