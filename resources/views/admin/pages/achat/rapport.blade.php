@extends('admin.layouts.app')
@section('title', 'achats')
@section('sub-title', 'Rapport des achats')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Rapport des achats</h4>
                            <div class="card-header-action">
                                <a href="{{ route('achat.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Retour
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            {{-- Filtre de dates --}}
                            <form method="GET" action="{{ route('achat.rapport') }}" class="mb-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="date_debut">Date de début</label>
                                            <input type="date" class="form-control" id="date_debut" name="date_debut"
                                                value="{{ $dateDebut }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="date_fin">Date de fin</label>
                                            <input type="date" class="form-control" id="date_fin" name="date_fin"
                                                value="{{ $dateFin }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary w75 mt-4">
                                            <i class="fas fa-filter"></i> Filtrer
                                        </button>
                                        <a href="{{ route('achat.rapport') }}" class="btn btn-secondary mt-4 ">
                                            <i class="fas fa-undo"></i> Réinitialiser
                                        </a>
                                    </div>
                                </div>
                            </form>

                            {{-- Statistiques globales --}}
                            <div class="row mb-4">
                                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-primary">
                                            <i class="fas fa-shopping-cart"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Total Achats</h4>
                                            </div>
                                            <div class="card-body">
                                                {{ $stats['total_achats'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-success">
                                            <i class="fas fa-dollar-sign"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Montant Total</h4>
                                            </div>
                                            <div class="card-body">
                                                {{ format_price($stats['montant_total']) }} FCFA
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-warning">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Montant Moyen</h4>
                                            </div>
                                            <div class="card-body">
                                                {{ format_price($stats['montant_moyen']) }} FCFA
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                    <div class="card card-statistic-1">
                                        <div class="card-icon bg-info">
                                            <i class="fas fa-box"></i>
                                        </div>
                                        <div class="card-wrap">
                                            <div class="card-header">
                                                <h4>Produits Différents</h4>
                                            </div>
                                            <div class="card-body">
                                                {{ $stats['par_produit']->count() }}
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                            </div>

                            {{-- Rapport par produit --}}
                            <h5 class="mb-3">Détails par produit de base</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="tableExport">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Produit</th>
                                            <th>Unité</th>
                                            <th>Quantité totale</th>
                                            <th>Montant total</th>
                                            <th>Prix moyen</th>
                                            <th>Nombre d'achats</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stats['par_produit'] as $stat)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><strong>{{ $stat['produit'] }}</strong></td>
                                                <td>{{ $stat['unite'] }}</td>
                                                <td>{{ format_price($stat['quantite_totale']) }} {{ $stat['unite'] }}</td>
                                                <td>{{ format_price($stat['montant_total']) }} FCFA</td>
                                                <td>{{ $stat['quantite_totale'] > 0 ? format_price($stat['montant_total'] / $stat['quantite_totale']) : 0 }}
                                                    FCFA</td>
                                                <td>{{ $stat['nombre_achats'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-info">
                                        <tr>
                                            <th colspan="4" class="text-right">TOTAL:</th>
                                            <th>{{ format_price($stats['montant_total']) }} FCFA</th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>
                                </table>
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

            var titre =
                'Liste des achats - {{ date('d-m-Y', strtotime($dateDebut)) }} au {{ date('d-m-Y', strtotime($dateFin)) }}';
            $('#tableExport').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
                },
                "order": [
                    [2, "desc"]
                ],

                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copy',
                        title: titre,
                    },
                    {
                        extend: 'csv',
                        title: titre,
                    },
                    {
                        extend: 'excel',
                        title: titre,
                    },
                    {
                        extend: 'pdf',
                        title: titre,

                    },
                    {
                        extend: 'print',
                        title: titre,
                    }
                ]
            });
        });
    </script>
@endpush
