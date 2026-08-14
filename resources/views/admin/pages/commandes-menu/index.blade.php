@extends('admin.layouts.app')
@section('title', 'Commandes Menu')
@section('sub-title', 'Réservations menu de la semaine')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Commandes Menu</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Commandes Menu</div>
        </div>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Réservations menu de la semaine</h4>
                        <small class="text-muted">Ces commandes n'apparaissent dans la liste de vente qu'une fois chaque jour livré.</small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Client</th>
                                        <th>Semaine</th>
                                        <th>Jours</th>
                                        <th>Montant total</th>
                                        <th>Paiement</th>
                                        <th>Livraison</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($reservations as $reservation)
                                        @php
                                            $totalJours = $reservation->orders->count();
                                            $joursLivres = $reservation->orders->where('status', \App\Models\Order::STATUS_LIVREE)->count();
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <strong>{{ $reservation->client_name }}</strong><br>
                                                <small class="text-muted">{{ $reservation->client_phone }}</small>
                                            </td>
                                            <td>{{ $reservation->menuSemaine->titre }}</td>
                                            <td>{{ $reservation->nombre_jours }}</td>
                                            <td>{{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA</td>
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
                                                        {{ $joursLivres }} / {{ $totalJours }} jour(s)
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('commandes-menu.show', $reservation) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Voir le détail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">Aucune réservation menu de la semaine pour le moment.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $reservations->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
