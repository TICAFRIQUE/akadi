@extends('admin.layouts.app')
@section('title', 'Commandes Menu')
@section('sub-title', $reservation->menuSemaine->titre_affiche)

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Réservation de {{ $reservation->client_name }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('commandes-menu.index') }}">Commandes Menu</a></div>
            <div class="breadcrumb-item">{{ $reservation->client_name }}</div>
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
                        <h4>Informations</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Client :</strong><br>
                                {{ $reservation->client_name }}<br>
                                <small class="text-muted">{{ $reservation->client_phone }}</small>
                            </div>
                            <div class="col-md-3">
                                <strong>Semaine :</strong><br>
                                {{ $reservation->menuSemaine->titre_affiche }}
                            </div>
                            <div class="col-md-2">
                                <strong>Jours :</strong><br>
                                {{ $reservation->nombre_jours }} jour(s)
                                <span class="text-muted">(moy. {{ number_format($reservation->prix_unitaire_applique, 0, ',', ' ') }} F/plat)</span>
                            </div>
                            <div class="col-md-2">
                                <strong>Montant total :</strong><br>
                                {{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA
                            </div>
                            <div class="col-md-2">
                                <strong>Paiement :</strong><br>
                                @if($reservation->statut === 'payee')
                                    <span class="badge badge-success">Payée</span>
                                @elseif($reservation->statut === 'annulee')
                                    <span class="badge badge-danger">Annulée</span>
                                @else
                                    <span class="badge badge-warning">En attente</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @foreach ($joursAffiches as $date => $lignes)
                    @php
                        $order = $reservation->orders->firstWhere('date_order', $date);
                        $plats = $platsDisponiblesParJour->get($date)?->menuProduits ?? collect();
                    @endphp
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <h4 class="mb-0">{{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('dddd D MMMM') }}</h4>
                            @if($order)
                                <div>
                                    <span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
                                    @if($order->status !== \App\Models\Order::STATUS_LIVREE)
                                        <a href="{{ route('order.changeState', ['cs' => \App\Models\Order::STATUS_LIVREE, 'id' => $order->id]) }}"
                                           class="btn btn-sm btn-success ml-2"
                                           onclick="return confirm('Marquer ce jour comme livré ?')">
                                            <i class="fas fa-check"></i> Marquer livré
                                        </a>
                                    @endif
                                </div>
                            @else
                                <span class="badge badge-secondary">Commande pas encore générée (paiement en attente)</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>Plat</th>
                                            <th>Qté</th>
                                            <th>Prix</th>
                                            @if($order && $order->status !== \App\Models\Order::STATUS_LIVREE)
                                                <th>Changer le plat</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lignes as $ligne)
                                            <tr>
                                                <td>{{ $ligne->menuProduit->nom }}</td>
                                                <td>{{ $ligne->quantity }}</td>
                                                <td>{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                                                @if($order && $order->status !== \App\Models\Order::STATUS_LIVREE)
                                                    <td>
                                                        <form action="{{ route('commandes-menu.modifier-plat', $order) }}" method="POST" class="form-inline">
                                                            @csrf
                                                            <input type="hidden" name="ancien_menu_produit_id" value="{{ $ligne->menu_produit_id }}">
                                                            <select name="nouveau_menu_produit_id" class="form-control form-control-sm mr-2">
                                                                @foreach ($plats as $plat)
                                                                    <option value="{{ $plat->id }}" {{ $plat->id == $ligne->menu_produit_id ? 'selected' : '' }}>
                                                                        {{ $plat->nom }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-exchange-alt"></i> Changer
                                                            </button>
                                                        </form>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach

                <a href="{{ route('commandes-menu.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
