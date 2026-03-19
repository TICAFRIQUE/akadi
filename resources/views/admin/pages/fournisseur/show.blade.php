@extends('admin.layouts.app')

@section('title' , 'Fournisseur')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Détails du Fournisseur</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('fournisseur.index') }}">Fournisseurs</a></div>
            <div class="breadcrumb-item">Détails</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Informations</h4>
                        <div class="card-header-action">
                            <a href="{{ route('fournisseur.edit', $fournisseur) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 180px;">Nom:</th>
                                <td><strong>{{ $fournisseur->nom }}</strong></td>
                            </tr>
                            <tr>
                                <th>Téléphone:</th>
                                <td>{{ $fournisseur->telephone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $fournisseur->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Adresse:</th>
                                <td>{{ $fournisseur->adresse ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Statut:</th>
                                <td>
                                    @if($fournisseur->actif)
                                        <span class="badge badge-success">Actif</span>
                                    @else
                                        <span class="badge badge-danger">Inactif</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Notes:</th>
                                <td>{{ $fournisseur->notes ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Statistiques</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 180px;">Nombre d'achats:</th>
                                <td><strong>{{ $fournisseur->achats->count() }}</strong></td>
                            </tr>
                            <tr>
                                <th>Montant total:</th>
                                <td><strong>{{ number_format($fournisseur->achats->sum('montant_total'), 0, ',', ' ') }} FCFA</strong></td>
                            </tr>
                            <tr>
                                <th>Dernier achat:</th>
                                <td>
                                    @if($fournisseur->achats->count() > 0)
                                        {{ $fournisseur->achats->sortByDesc('date_achat')->first()->date_achat->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Historique des achats</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Numéro</th>
                                        <th>Date</th>
                                        <th>Montant</th>
                                        <th>Notes</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($fournisseur->achats as $achat)
                                        <tr>
                                            <td><strong>{{ $achat->numero }}</strong></td>
                                            <td>{{ $achat->date_achat->format('d/m/Y') }}</td>
                                            <td>{{ number_format($achat->montant_total, 0, ',', ' ') }} FCFA</td>
                                            <td>{{ $achat->notes ? Str::limit($achat->notes, 50) : '-' }}</td>
                                            <td>
                                                <a href="{{ route('achat.show', $achat) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Aucun achat enregistré</td>
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
