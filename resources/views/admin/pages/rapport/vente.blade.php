@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Rapport des Ventes</h4>
                </div>
                <div class="card-body">
                    <!-- Formulaire de filtres -->
                    <form method="GET" action="{{ route('rapport.vente') }}" class="row mb-4 ">
                        <div class="col-md-4">
                            <label for="date_debut">Date début :</label>
                            <input type="date" name="date_debut" id="date_debut" class="form-control" 
                                   value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="date_fin">Date fin :</label>
                            <input type="date" name="date_fin" id="date_fin" class="form-control" 
                                   value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100 ">Filtrer</button>
                            {{-- <a href="{{ route('rapport.vente') }}" class="btn btn-secondary">Reset</a> --}}
                        </div>
                    </form>

                    <!-- Statistiques générales -->
                    {{-- <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5>{{ $totalCommandes }}</h5>
                                    <p>Total Commandes</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5>{{ number_format($totalVentesNet, 0, ',', ' ') }} FCFA</h5>
                                    <p>Chiffre d'Affaires</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5>{{ number_format($revenuNet, 0, ',', ' ') }} FCFA</h5>
                                    <p>Revenu Net</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h5>{{ number_format($panierMoyen, 0, ',', ' ') }} FCFA</h5>
                                    <p>Panier Moyen</p>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <!-- Produits performance -->
                    {{-- <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>🏆 Produit le Plus Vendu</h5>
                                </div>
                                <div class="card-body">
                                    @if($produitPlusVendu)
                                        <h6>{{ $produitPlusVendu['title'] }}</h6>
                                        <p><strong>Code:</strong> {{ $produitPlusVendu['code'] }}</p>
                                        <p><strong>Quantité vendue:</strong> {{ $produitPlusVendu['total_quantite'] }}</p>
                                        <p><strong>CA généré:</strong> {{ number_format($produitPlusVendu['total_chiffre_affaires'], 0, ',', ' ') }} FCFA</p>
                                    @else
                                        <p>Aucune vente dans cette période</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>📉 Produit le Moins Vendu</h5>
                                </div>
                                <div class="card-body">
                                    @if($produitMoinsVendu)
                                        <h6>{{ $produitMoinsVendu['title'] }}</h6>
                                        <p><strong>Code:</strong> {{ $produitMoinsVendu['code'] }}</p>
                                        <p><strong>Quantité vendue:</strong> {{ $produitMoinsVendu['total_quantite'] }}</p>
                                        <p><strong>CA généré:</strong> {{ number_format($produitMoinsVendu['total_chiffre_affaires'], 0, ',', ' ') }} FCFA</p>
                                    @else
                                        <p>Aucune vente dans cette période</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <!-- Top 10 des produits -->
                    <div class="card">
                        <div class="card-header">
                            <h5>📊 Top 10 des Produits Vendus</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Produit</th>
                                            <th>Code</th>
                                            <th>Prix Unitaire</th>
                                            <th>Quantité Vendue</th>
                                            <th>Chiffre d'Affaires</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($top10ProduitsVendus as $index => $produit)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $produit['title'] }}</td>
                                                <td>{{ $produit['code'] }}</td>
                                                <td>{{ number_format($produit['price'], 0, ',', ' ') }} FCFA</td>
                                                <td>{{ $produit['total_quantite'] }}</td>
                                                <td>{{ number_format($produit['total_chiffre_affaires'], 0, ',', ' ') }} FCFA</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Aucune vente dans cette période</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                      <!-- Liste des produits vendus -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>📋 Liste des Produits Vendus</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Code</th>
                                            <th>Quantité Vendue</th>
                                            <th>Chiffre d'Affaires</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($listeProduitsVendus as $produit)
                                            <tr>
                                                <td>{{ $produit['title'] }}</td>
                                                <td>{{ $produit['code'] }}</td>
                                                <td>{{ $produit['total_quantite'] }}</td>
                                                <td>{{ number_format($produit['total_chiffre_affaires'], 0, ',', ' ') }} FCFA</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Résumé financier -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5>💰 Résumé Financier</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Ventes Brutes:</strong> {{ number_format($totalVentesBrut, 0, ',', ' ') }} FCFA</p>
                                            <p><strong>Remises Accordées:</strong> -{{ number_format($totalRemises, 0, ',', ' ') }} FCFA</p>
                                            <p><strong>Frais de Livraison:</strong> {{ number_format($totalLivraison, 0, ',', ' ') }} FCFA</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Chiffre d'Affaires TTC:</strong> {{ number_format($totalVentesNet, 0, ',', ' ') }} FCFA</p>
                                            <p class="h4 text-success">
                                                <strong>Revenu Net:</strong>
                                                {{ number_format($totalVentesNet - $totalRemises, 0, ',', ' ') }} FCFA
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                  
                </div>
            </div>
        </div>
    </div>
</div>
@endsection