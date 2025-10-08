@extends('admin.layouts.app')
@section('title', 'Rapport des Ventes')
@section('sub-title', 'Analyse des performances de vente')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Rapport des Ventes</h4>
                        {{-- Bloc période ici --}}
                        <button class="btn btn-primary mb-3 btnImprimer">
                            <i class="far fa-print"></i> Imprimer le rapport
                        </button>

                    </div>

                    <!-- Formulaire de filtres -->
                    <form method="GET" action="{{ route('rapport.vente') }}" class="row m-3 ">
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
                    <div class="card-body divPrint">


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
                                    @if ($produitPlusVendu)
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
                                    @if ($produitMoinsVendu)
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
                                <h5>📊 Top 10 des Produits Vendus


                                    <small class="text-muted ">
                                        @if ($dateDebut && $dateFin)
                                            Du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }}
                                            au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
                                        @elseif($dateDebut)
                                            du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }}
                                        @elseif($dateFin)
                                            Jusqu'au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
                                        @else
                                            Toutes les ventes
                                        @endif
                                    </small>

                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Produit</th>
                                                <th>Categorie</th>
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
                                                    <td>{{ $produit['categorie'] }}</td>
                                                    <td>{{ number_format($produit['price'], 0, ',', ' ') }} FCFA</td>
                                                    <td>{{ $produit['total_quantite'] }}</td>
                                                    <td>{{ number_format($produit['total_chiffre_affaires'], 0, ',', ' ') }}
                                                        FCFA</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">Aucune vente dans cette période
                                                    </td>
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
                                <h5>📋 Liste des Produits Vendus

                                    <small class="text-muted ">
                                        @if ($dateDebut && $dateFin)
                                            Du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }}
                                            au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
                                        @elseif($dateDebut)
                                            du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }}
                                        @elseif($dateFin)
                                            Jusqu'au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}
                                        @else
                                            Toutes les ventes
                                        @endif
                                    </small>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Produit</th>
                                                <th>Categorie</th>
                                                <th>Quantité Vendue</th>
                                                <th>Chiffre d'Affaires</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($listeProduitsVendus as $produit)
                                                <tr>
                                                    <td>{{ $produit['title'] }}</td>
                                                    <td>{{ $produit['categorie'] }}</td>
                                                    <td>{{ $produit['total_quantite'] }}</td>
                                                    <td>{{ number_format($produit['total_chiffre_affaires'], 0, ',', ' ') }}
                                                        FCFA</td>
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
                                                <p><strong>Ventes Brutes:</strong>
                                                    {{ number_format($totalVentesBrut, 0, ',', ' ') }} FCFA</p>
                                                <p><strong>Remises Accordées:</strong>
                                                    -{{ number_format($totalRemises, 0, ',', ' ') }} FCFA</p>
                                                <p><strong>Frais de Livraison:</strong>
                                                    {{ number_format($totalLivraison, 0, ',', ' ') }} FCFA</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Chiffre d'Affaires TTC:</strong>
                                                    {{ number_format($totalVentesNet, 0, ',', ' ') }} FCFA</p>
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

                    <button class="w-100 btnImprimer btn btn-primary"><i class="far fa-print "></i>Imprimer le rapport</button>

                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            // Fonction pour imprimer le rapport
            function imprimerRapport() {
                // Créer une nouvelle fenêtre pour l'impression
                var fenetreImpression = window.open('', '_blank');

                // Contenu à imprimer
                var contenuImprimer = `
                    <html>
                        <head>
                            <title style="text-align: center;">Rapport de vente</title>
                            <style>
                                body { font-family: Arial, sans-serif; }
                                table { width: 100%; border-collapse: collapse; }
                                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                                th { background-color: #f2f2f2; }
                            </style>
                        </head>
                        <body>
                            <h2 style="text-align: center;">Rapport de vente</h2>
                            ${$('.divPrint').html()}
                            <footer style="position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 12px; margin-top: 20px;">
                                <p>Imprimé le : ${new Date().toLocaleString()} par {{ Auth::user()->first_name }}</p>
                            </footer>
                        </body>
                    </html>
                `;

                // Écrire le contenu dans la nouvelle fenêtre
                fenetreImpression.document.write(contenuImprimer);

                // Fermer le document
                fenetreImpression.document.close();

                // Imprimer la fenêtre
                fenetreImpression.print();
            }

            // Ajouter un bouton d'impression
            $('.btnImprimer')
                .on('click', imprimerRapport);
            // .appendTo('.divPrint');
        });
    </script>
@endsection
