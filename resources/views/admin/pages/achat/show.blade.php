@extends('admin.layouts.app')
@section('title', 'achats')
@section('sub-title', 'Détails de l\'achat')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Détails de l'achat: {{ $achat->numero }}</h4>
                            <div class="card-header-action">
                                <a href="{{ route('achat.edit', $achat->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Modifier
                                </a>
                                <a href="{{ route('achat.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Retour
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            {{-- Informations générales --}}
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="40%">Numéro d'achat:</th>
                                            <td><strong>{{ $achat->numero }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Date d'achat:</th>
                                            <td>{{ $achat->date_achat->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Fournisseur:</th>
                                            <td>{{ $achat->fournisseur ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>N° Facture:</th>
                                            <td>{{ $achat->numero_facture ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="40%">Utilisateur:</th>
                                            <td>{{ $achat->user->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Créé le:</th>
                                            <td>{{ $achat->created_at->format('d/m/Y à H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Modifié le:</th>
                                            <td>{{ $achat->updated_at->format('d/m/Y à H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Montant total:</th>
                                            <td><strong class="text-dark">{{ number_format($achat->montant_total, 0, ',', ' ') }} FCFA</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($achat->notes)
                                <div class="alert alert-info">
                                    <strong>Notes:</strong><br>
                                    {{ $achat->notes }}
                                </div>
                            @endif

                            {{-- Détails des produits --}}
                            <h5 class="mb-3">Produits achetés</h5>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Produit de base</th>
                                            <th>Quantité</th>
                                            <th>Prix unitaire</th>
                                            <th>Montant ligne</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($achat->lignes as $ligne)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $ligne->productBase->nom }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        Stock avant: {{ number_format($ligne->productBase->stock - $ligne->quantite, 2) }} {{ $ligne->productBase->unite }}
                                                        → Stock après: {{ number_format($ligne->productBase->stock, 2) }} {{ $ligne->productBase->unite }}
                                                    </small>
                                                </td>
                                                <td>{{ number_format($ligne->quantite, 2) }} {{ $ligne->productBase->unite }}</td>
                                                <td>{{ number_format($ligne->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                                                <td><strong>{{ number_format($ligne->montant_ligne, 0, ',', ' ') }} FCFA</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-info">
                                        <tr>
                                            <th colspan="4" class="text-right">TOTAL:</th>
                                            <th>{{ number_format($achat->montant_total, 0, ',', ' ') }} FCFA</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-right">
                            <form action="{{ route('achat.destroy', $achat->id) }}" 
                                  method="POST" 
                                  style="display: inline-block;"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet achat ? Le stock sera décrémenté pour chaque produit.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Supprimer cet achat
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
