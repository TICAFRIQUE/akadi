@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Rapport des Sorties de Stock</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('sortie-stock.index') }}">Sorties de Stock</a></div>
            <div class="breadcrumb-item">Rapport</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Filtr par période</h4>
                    </div>
                    <div class="card-body">
                        <form method="GET" class="form-inline">
                            <div class="form-group mr-3">
                                <label for="date_debut" class="mr-2">Du:</label>
                                <input type="date" name="date_debut" class="form-control" value="{{ $dateDebut }}">
                            </div>
                            <div class="form-group mr-3">
                                <label for="date_fin" class="mr-2">Au:</label>
                                <input type="date" name="date_fin" class="form-control" value="{{ $dateFin }}">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter"></i> Filtrer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistiques --}}
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Sorties</h4>
                        </div>
                        <div class="card-body">
                            {{ $stats['total_sorties'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Rapport par produit --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Détails par produit</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Produit</th>
                                        <th>Unité</th>
                                        <th>Quantité totale</th>
                                        <th>Perte</th>
                                        <th>Casse</th>
                                        <th>Don</th>
                                        <th>Autre</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['par_produit'] as $stat)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>{{ $stat['produit'] }}</strong></td>
                                            <td>{{ $stat['unite'] }}</td>
                                            <td>{{ number_format($stat['quantite_totale'], 2) }}</td>
                                            <td>{{ number_format($stat['par_motif']['Perte'] ?? 0, 2) }}</td>
                                            <td>{{ number_format($stat['par_motif']['Casse'] ?? 0, 2) }}</td>
                                            <td>{{ number_format($stat['par_motif']['Don'] ?? 0, 2) }}</td>
                                            <td>{{ number_format($stat['par_motif']['Autre'] ?? 0, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Liste des sorties --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Liste des sorties</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Numéro</th>
                                        <th>Produit</th>
                                        <th>Quantité</th>
                                        <th>Motif</th>
                                        <th>Utilisateur</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sorties as $sortie)
                                        <tr>
                                            <td>{{ $sortie->date_sortie->format('d/m/Y') }}</td>
                                            <td>{{ $sortie->numero }}</td>
                                            <td>{{ $sortie->productBase->nom }}</td>
                                            <td>{{ number_format($sortie->quantite, 2) }} {{ $sortie->productBase->unite }}</td>
                                            <td>
                                                <span class="badge badge-{{ $sortie->motif == 'Casse' ? 'danger' : ($sortie->motif == 'Don' ? 'success' : 'warning') }}">
                                                    {{ $sortie->motif }}
                                                </span>
                                            </td>
                                            <td>{{ $sortie->user->name }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Aucune sortie pour cette période</td>
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
