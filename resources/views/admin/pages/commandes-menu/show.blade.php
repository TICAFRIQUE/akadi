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
                            <div class="col-md-3 mb-3">
                                <strong>Client :</strong><br>
                                {{ $reservation->client_name }}<br>
                                <small class="text-muted">{{ $reservation->client_phone }}</small>
                            </div>
                            <div class="col-md-3 mb-3">
                                <strong>Semaine :</strong><br>
                                {{ $reservation->menuSemaine->titre_affiche }}
                            </div>
                            <div class="col-md-2 mb-3">
                                <strong>Jours :</strong><br>
                                {{ $reservation->nombre_jours }} jour(s)
                                <small class="text-muted d-block">{{ number_format($reservation->prix_unitaire_applique, 0, ',', ' ') }} F/plat</small>
                            </div>
                            <div class="col-md-2 mb-3">
                                <strong>Montant total :</strong><br>
                                <span class="font-weight-bold" style="color:#eb0029;">{{ number_format($reservation->montant_total, 0, ',', ' ') }} FCFA</span>
                            </div>
                            <div class="col-md-2 mb-3">
                                <strong>Paiement :</strong><br>
                                @if($reservation->statut === 'payee')
                                    <span class="badge badge-success">Payée</span>
                                @elseif($reservation->statut === 'annulee')
                                    <span class="badge badge-danger">Annulée</span>
                                @else
                                    <span class="badge badge-warning">En attente</span>
                                @endif
                            </div>
                            <div class="col-md-3 mb-3">
                                <strong>Mode de livraison :</strong><br>
                                @if($reservation->mode_livraison === 'yango')
                                    <span class="badge badge-info">Yango</span>
                                @else
                                    <span class="badge badge-secondary">Sur place</span>
                                @endif
                            </div>
                            @if($reservation->address_yango)
                            <div class="col-md-9 mb-3">
                                <strong>Adresse de livraison :</strong><br>
                                <span class="text-muted"><i class="fas fa-map-marker-alt mr-1 text-danger"></i>{{ $reservation->address_yango }}</span>
                            </div>
                            @endif
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
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="badge badge-{{ $order->status_color }}">{{ $order->status_label }}</span>
                                    @if($order->status !== \App\Models\Order::STATUS_LIVREE)
                                        <a href="#"
                                           class="btn btn-sm btn-success ml-2 btn-marquer-livre"
                                           data-href="{{ route('order.changeState', ['cs' => \App\Models\Order::STATUS_LIVREE, 'id' => $order->id]) }}"
                                           data-date="{{ \Carbon\Carbon::parse($date)->locale('fr')->isoFormat('dddd D MMMM') }}"
                                           data-client="{{ $reservation->client_name }}"
                                           data-plats="{{ $lignes->map(fn($l) => $l->menuProduit->nom)->join(', ') }}">
                                            <i class="fas fa-check"></i> Marquer livré
                                        </a>
                                    @endif
                                    <a href="{{ route('commandes-menu.ticket', $order) }}" class="btn btn-sm btn-outline-secondary ml-1" target="_blank">
                                        <i class="fas fa-receipt mr-1"></i> Ticket
                                    </a>
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

                <div class="d-flex align-items-center gap-2 flex-wrap mt-2">
                    <a href="{{ route('commandes-menu.by-menu', $reservation->menuSemaine) }}" class="btn btn-secondary mr-2">
                        <i class="fas fa-arrow-left"></i> Retour aux commandes
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script>
$(document).ready(function () {

    $('.btn-marquer-livre').on('click', function (e) {
        e.preventDefault();

        const href   = $(this).data('href');
        const date   = $(this).data('date');
        const client = $(this).data('client');
        const plats  = $(this).data('plats');

        Swal.fire({
            title: 'Confirmer la livraison ?',
            html: `
                <div style="text-align:left;font-size:14px;line-height:1.7;">
                    <p style="margin-bottom:10px;">
                        Vous êtes sur le point de marquer le repas du
                        <strong>${date}</strong> de <strong>${client}</strong> comme <span style="color:#16a34a;font-weight:700;">livré</span>.
                    </p>
                    <div style="background:#f0fdf4;border-left:3px solid #16a34a;padding:8px 12px;border-radius:4px;margin-bottom:12px;font-size:13px;">
                        🍽️ ${plats}
                    </div>
                    <div style="background:#fff8f0;border-left:3px solid #f85d05;padding:8px 12px;border-radius:4px;font-size:13px;">
                        ⚠️ <strong>Important :</strong> cette commande sera automatiquement déplacée
                        dans <strong>Ventes &rarr; Commandes</strong> en tant que commande ordinaire.
                        Elle ne sera plus modifiable ici.
                    </div>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor:  '#6b7280',
            confirmButtonText: '<i class="fas fa-check mr-1"></i> Oui, marquer livré',
            cancelButtonText:  'Annuler',
            reverseButtons: true,
            focusCancel: true,
        }).then(function (result) {
            if (result.isConfirmed) {
                window.location.href = href;
            }
        });
    });

});
</script>
@endpush
