@extends('admin.layouts.app')

@section('title', 'Suivi de Stock')
@section('sub-title', 'Mouvements et alertes de stock')

@section('content')
    <section class="section">


        <div class="section-body">
            {{-- Filtres --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Filtres</h4>
                        </div>
                        <div class="card-body">
                            <form method="GET" class="form-inline">
                                <div class="form-group mr-3">
                                    <label for="date_debut" class="mr-2">Du:</label>
                                    <input type="date" name="date_debut" class="form-control"
                                        value="{{ $dateDebut }}">
                                </div>
                                <div class="form-group mr-3">
                                    <label for="date_fin" class="mr-2">Au:</label>
                                    <input type="date" name="date_fin" class="form-control" value="{{ $dateFin }}">
                                </div>
                                <div class="form-group mr-3">
                                    <label for="filtre" class="mr-2">Type:</label>
                                    <select name="filtre" class="form-control">
                                        <option value="tous" {{ $filtre == 'tous' ? 'selected' : '' }}>Tous</option>
                                        <option value="ajoute" {{ $filtre == 'ajoute' ? 'selected' : '' }}>Stock ajouté
                                        </option>
                                        <option value="vendu" {{ $filtre == 'vendu' ? 'selected' : '' }}>Stock vendu
                                        </option>
                                        <option value="sortie" {{ $filtre == 'sortie' ? 'selected' : '' }}>Sortie de stock
                                        </option>
                                        <option value="existant" {{ $filtre == 'existant' ? 'selected' : '' }}>Stock
                                            existant</option>
                                        <option value="disponible" {{ $filtre == 'disponible' ? 'selected' : '' }}>Stock
                                            disponible</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filtrer
                                </button>
                                <a href="{{ route('suivi-stock.index') }}" class="btn btn-secondary ml-2">
                                    <i class="fas fa-redo"></i> Réinitialiser
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Produits avec alerte --}}
            @php
                $alertes = collect($suiviStock)->where('alerte', true);
            @endphp
            @if ($alertes->count() > 0)
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning alert-has-icon">
                            <div class="alert-icon"><i class="fas fa-exclamation-triangle"></i></div>
                            <div class="alert-body">
                                <div class="alert-title">Alertes de Stock</div>
                                <strong>{{ $alertes->count() }}</strong> produit(s) en alerte de stock minimum !
                                <ul class="mt-2">
                                    @foreach ($alertes as $alerte)
                                        <li>
                                            <strong>{{ $alerte['produit'] }}</strong>:
                                            Stock actuel {{ format_price($alerte['stock_actuel']) }}
                                            {{ $alerte['unite'] }}
                                            (Minimum: {{ format_price($alerte['stock_min']) }} {{ $alerte['unite'] }})
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Tableau de suivi --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Mouvements de stock - Période: {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} au
                                {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tableExport">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th rowspan="2">#</th>
                                            <th rowspan="2">Produit</th>
                                            <th rowspan="2">Unité</th>
                                            <th colspan="3" class="text-center bg-info">Mouvements période</th>
                                            <th colspan="4" class="text-center bg-success">État actuel</th>
                                            <th rowspan="2">Prix achat moyen</th>
                                            <th rowspan="2">Statut</th>
                                        </tr>
                                        <tr>
                                            <th class="bg-light">Ajouté</th>
                                            <th class="bg-light">Vendu</th>
                                            <th class="bg-light">Sortie</th>
                                            <th class="bg-light">Stock actuel</th>
                                            <th class="bg-light">Stock min</th>
                                            <th class="bg-light">Stock max</th>
                                            <th class="bg-light">Disponible</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($suiviStock as $stock)
                                            <tr class="{{ $stock['alerte'] ? 'table-warning' : '' }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td><strong>{{ $stock['produit'] }}</strong></td>
                                                <td>{{ $stock['unite'] }}</td>
                                                <td class="text-success">+{{ format_price($stock['stock_ajoute']) }}
                                                </td>
                                                <td class="text-danger">-{{ format_price($stock['stock_vendu']) }}</td>
                                                <td class="text-warning">-{{ format_price($stock['stock_sortie']) }}
                                                </td>
                                                <td><strong>{{ format_price($stock['stock_actuel']) }}</strong></td>
                                                <td class="text-muted">{{ format_price($stock['stock_min']) }}</td>
                                                <td class="text-muted">{{ format_price($stock['stock_max']) }}</td>
                                                <td
                                                    class="{{ $stock['stock_disponible'] > 0 ? 'text-success' : 'text-danger' }}">
                                                    <strong>{{ format_price($stock['stock_disponible']) }}</strong>
                                                </td>
                                                <td>{{ format_price($stock['prix_achat_moyen']) }} FCFA</td>
                                                <td>
                                                    @if ($stock['alerte'])
                                                        <span class="badge badge-danger">
                                                            <i class="fas fa-exclamation-triangle"></i> Alerte
                                                        </span>
                                                    @elseif($stock['stock_disponible'] <= 0)
                                                        <span class="badge badge-warning">Critique</span>
                                                    @else
                                                        <span class="badge badge-success">Normal</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="12" class="text-center">Aucun mouvement de stock pour cette
                                                    période</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
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
                'Liste de Suivi de Stock - Période: {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}';

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
