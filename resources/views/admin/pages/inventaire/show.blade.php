@extends('admin.layouts.app')
@section('title', 'Détail inventaire')
@section('sub-title', 'Détail de l\'inventaire')
@section('content')

<section class="section">
    <div class="section-header">
        <h1>Détail de l'inventaire</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('inventaire.index') }}">Inventaires</a></div>
            <div class="breadcrumb-item">Détail</div>
        </div>
    </div>
    <div class="section-body">
        <div class="mb-3">
            <div class="alert alert-info">
                <strong>Période de l'inventaire :</strong>
                @php
                    $date_debut = null;
                    $date_fin = $inventaire->date_inventaire;
                    foreach($inventaire->lignes as $ligne) {
                        // Chercher le dernier inventaire précédent pour chaque produit
                        $lastLine = \App\Models\InventoryLine::where('product_base_id', $ligne->product_base_id)
                            ->where('created_at', '<', $ligne->created_at)
                            ->orderByDesc('created_at')->first();
                        $lastDate = $lastLine ? $lastLine->created_at : ($ligne->productBase->created_at ?? null);
                        if($lastDate && ($date_debut === null || $lastDate > $date_debut)) {
                            $date_debut = $lastDate;
                        }
                    }
                    if($date_debut === null) {
                        $date_debut = $date_fin;
                    }
                @endphp
                Du <b>{{ \Carbon\Carbon::parse($date_debut)->format('d/m/Y H:i') }}</b>
                au <b>{{ \Carbon\Carbon::parse($date_fin)->format('d/m/Y H:i') }}</b>
            </div>
            <div class="row mb-2">
                <div class="col-md-3">
                    <strong>Réalisé par :</strong> {{ $inventaire->user ? $inventaire->user->name : '-' }}
                </div>
                <div class="col-md-3">
                    <strong>Date de création :</strong> {{ \Carbon\Carbon::parse($inventaire->date_inventaire)->format('d/m/Y H:i') }}
                </div>
                <div class="col-md-3">
                    <strong>Nombre de produits :</strong> {{ $inventaire->lignes->count() }}
                </div>
                
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="tableExport">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Stock dernier inventaire</th>
                                <th>Stock ajouté</th>
                                <th>Stock total</th>
                                <th>Stock vendu</th>
                                <th>Stock sortie</th>
                                <th>Stock restant</th>
                                <th>Stock physique</th>
                                <th>Écart</th>
                                <th>Résultat</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($inventaire->lignes as $ligne)
                            <tr>
                                <td>{{ $ligne->productBase->nom }}</td>
                                <td>{{ format_price($ligne->stock_dernier_inventaire) }}</td>
                                <td>{{ format_price($ligne->stock_ajoute) }}</td>
                                <td>{{ format_price($ligne->stock_total) }}</td>
                                <td>{{ format_price($ligne->stock_vendu) }}</td>
                                <td>{{ format_price($ligne->stock_sortie) }}</td>
                                <td>{{ format_price($ligne->stock_restant) }}</td>
                                <td>{{ format_price($ligne->stock_physique) }}</td>
                                <td>{{ format_price($ligne->ecart) }}</td>
                                <td>
                                    @if($ligne->resultat === 'conforme')
                                        <span class="badge badge-success">Conforme</span>
                                    @elseif($ligne->resultat === 'perte')
                                        <span class="badge badge-danger">Perte</span>
                                    @elseif($ligne->resultat === 'rupture')
                                        <span class="badge badge-warning">Rupture</span>
                                    @elseif($ligne->resultat === 'surplus')
                                        <span class="badge badge-info">Surplus</span>
                                    @else
                                        <span class="badge badge-secondary">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@push('js')
    <script>
        $(document).ready(function() {
            //titre doit prendre intervalle de date de l'inventaire et le user qui a réalisé l'inventaire
            var titre = 'Détail de l\'inventaire du {{ \Carbon\Carbon::parse($inventaire->date_inventaire)->format('d-m-Y à H-i') }} par {{ $inventaire->user->name }}';

            $('#tableExport').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'copy',
                        title: titre
                    },
                    {
                        extend: 'csv',
                        title: titre
                    },
                    {
                        extend: 'excel',
                        title: titre
                    },
                    {
                        extend: 'pdf',
                        title: titre
                    },
                    {
                        extend: 'print',
                        title: titre
                    }
                ]
            });
        });
    </script>
@endpush

@endsection
