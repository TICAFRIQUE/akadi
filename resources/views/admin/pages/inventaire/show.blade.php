@extends('admin.layouts.app')
@section('title', 'Inventaire')
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
                    <strong>Date de l'inventaire :</strong>
                    <b>{{ \Carbon\Carbon::parse($inventaire->date_inventaire)->format('d/m/Y H:i') }}</b>
                    — chaque produit affiche sa propre période ci-dessous
                </div>
                <div class="row mb-2">
                    <div class="col-md-3">
                        <strong>Réalisé par :</strong>
                        {{ $inventaire->user->name ?? '-' }}
                    </div>
                    <div class="col-md-3">
                        <strong>Date de création :</strong>
                        {{ \Carbon\Carbon::parse($inventaire->date_inventaire)->format('d/m/Y H:i') }}
                    </div>
                    <div class="col-md-3">
                        <strong>Nombre de produits :</strong>
                        {{ $inventaire->lignes->count() }}
                    </div>
                    <div class="col-md-3 text-right">
                        <a href="{{ route('inventaire.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
            </div>

            {{-- Résumé global --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Conformes</h4>
                            </div>
                            <div class="card-body">
                                {{ $inventaire->lignes->where('resultat', 'conforme')->count() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-arrow-down"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pertes</h4>
                            </div>
                            <div class="card-body">
                                {{ $inventaire->lignes->where('resultat', 'perte')->count() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Surplus</h4>
                            </div>
                            <div class="card-body">
                                {{ $inventaire->lignes->where('resultat', 'surplus')->count() }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-exclamation"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Ruptures</h4>
                            </div>
                            <div class="card-body">
                                {{ $inventaire->lignes->where('resultat', 'rupture')->count() }}
                            </div>
                        </div>
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
                                    <th>Période</th>
                                    <th>Stock dernier inventaire</th>
                                    <th>Stock ajouté</th>
                                    <th>Stock total</th>
                                    <th>Stock vendu</th>
                                    <th>Stock sorti</th>
                                    <th>Stock restant</th>
                                    <th>Stock physique</th>
                                    <th>Écart</th>
                                    <th>Résultat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventaire->lignes as $ligne)
                                    <tr>
                                        <td>
                                            <strong>{{ $ligne->productBase->nom ?? '-' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $ligne->productBase->unite ?? '' }}</small>
                                        </td>

                                        {{-- 👇 Période par ligne, plus de calcul @php --}}
                                        <td>
                                            <small>
                                                @if ($ligne->date_debut && $ligne->date_fin)
                                                    Du
                                                    <strong>{{ \Carbon\Carbon::parse($ligne->date_debut)->format('d/m/Y') }}</strong>
                                                    <br>
                                                    au
                                                    <strong>{{ \Carbon\Carbon::parse($ligne->date_fin)->format('d/m/Y') }}</strong>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </small>
                                        </td>

                                        <td class="text-center">{{ format_price($ligne->stock_dernier_inventaire) }}</td>
                                        <td class="text-center text-success">+ {{ format_price($ligne->stock_ajoute) }}
                                        </td>
                                        <td class="text-center">{{ format_price($ligne->stock_total) }}</td>
                                        <td class="text-center text-danger">- {{ format_price($ligne->stock_vendu) }}</td>
                                        <td class="text-center text-warning">- {{ format_price($ligne->stock_sortie) }}
                                        </td>
                                        <td class="text-center"><strong>{{ format_price($ligne->stock_restant) }}</strong>
                                        </td>
                                        <td class="text-center">{{ format_price($ligne->stock_physique) }}</td>

                                        {{-- Écart coloré --}}
                                        <td class="text-center">
                                            @if ($ligne->ecart > 0)
                                                <span class="text-info">+ {{ format_price($ligne->ecart) }}</span>
                                            @elseif ($ligne->ecart < 0)
                                                <span class="text-danger">{{ format_price($ligne->ecart) }}</span>
                                            @else
                                                <span class="text-success">0</span>
                                            @endif
                                        </td>

                                        {{-- Résultat --}}
                                        <td class="text-center">
                                            @switch($ligne->resultat)
                                                @case('conforme')
                                                    <span class="badge badge-success">Conforme</span>
                                                @break

                                                @case('perte')
                                                    <span class="badge badge-danger">Perte</span>
                                                @break

                                                @case('rupture')
                                                    <span class="badge badge-warning">Rupture</span>
                                                @break

                                                @case('surplus')
                                                    <span class="badge badge-info">Surplus</span>
                                                @break

                                                @default
                                                    <span class="badge badge-secondary">—</span>
                                            @endswitch
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
                var titre =
                    'Détail de l\'inventaire du {{ \Carbon\Carbon::parse($inventaire->date_inventaire)->format('d-m-Y à H-i') }} par {{ $inventaire->user->name }}';

                $('#tableExport').DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
                    },
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

@endsection
