@extends('admin.layouts.app')
@section('title', 'Rapport des Ventes Menu du jour')
@section('sub-title', 'Analyse des performances de vente du menu du jour')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Rapport des Ventes — Menu du jour</h4>
                    </div>

                    <!-- Formulaire de filtres -->
                    <form method="GET" action="{{ route('rapport.venteMenu') }}" class="row m-3 ">
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
                        <div class="col-md-4 d-flex align-items-center gap-2 mt-4">
                            <button type="submit" class="btn btn-primary w-100 ">Filtrer</button>
                            <a href="{{ route('rapport.venteMenu') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                    <div class="card-body divPrint">

                        <!-- Top 10 des plats -->
                        <div class="card">
                            <div class="card-header">
                                <h5>📊 Top 10 des Plats Vendus

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
                                                <th>Plat</th>
                                                <th>Prix Unitaire</th>
                                                <th>Quantité Vendue</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($top10PlatsVendus as $index => $plat)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td><span class="font-weight-bold">{{ $plat['nom'] }}</span></td>
                                                    <td>{{ number_format($plat['prix'], 0, ',', ' ') }} FCFA</td>
                                                    <td>{{ $plat['total_quantite'] }}</td>
                                                    <td>{{ number_format($plat['total_chiffre_affaires'], 0, ',', ' ') }}
                                                        FCFA</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">Aucune vente de menu du jour dans
                                                        cette période</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Liste des plats vendus -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5>📋 Liste des Plats Vendus

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
                                                <th>Plat</th>
                                                <th>Quantité Vendue</th>
                                                <th>Chiffre d'Affaires</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($listePlatsVendus as $plat)
                                                <tr>
                                                    <td><span class="font-weight-bold">{{ $plat['nom'] }}</span></td>
                                                    <td>{{ $plat['total_quantite'] }}</td>
                                                    <td>{{ number_format($plat['total_chiffre_affaires'], 0, ',', ' ') }}
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
                                        <h5>💰 Résumé Financier — Menu du jour</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <p><strong>Nombre de commandes avec menu :</strong>
                                                    {{ $totalCommandesMenu }}</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p><strong>Total ventes menu :</strong>
                                                    {{ number_format($totalVenteMenu, 0, ',', ' ') }} FCFA</p>
                                            </div>
                                            <div class="col-md-4">
                                                <p class="h4 text-success">
                                                    <strong>Panier moyen menu :</strong>
                                                    {{ number_format($panierMoyenMenu, 0, ',', ' ') }} FCFA
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center flex-wrap gap-2 my-3">
                        <button class="btnImprimer btn btn-primary mr-2"><i class="far fa-file-pdf"></i> Imprimer le
                            rapport</button>
                        <button id="btnExportExcel" class="btn btn-success"><i class="far fa-file-excel"></i> Exporter en
                            Excel</button>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- SheetJS (XLSX) doit être chargé AVANT tout script qui l'utilise -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fonction pour imprimer le rapport
            function imprimerRapport() {
                var fenetreImpression = window.open('', '_blank');

                var contenuImprimer = `
                    <html>
                        <head>
                            <title style="text-align: center;">Rapport de vente - Menu du jour</title>
                            <style>
                                body { font-family: Arial, sans-serif; }
                                table { width: 100%; border-collapse: collapse; }
                                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                                th { background-color: #f2f2f2; }
                            </style>
                        </head>
                        <body>
                            <h2 style="text-align: center;">Rapport de vente - Menu du jour</h2>
                            ${$('.divPrint').html()}
                            <footer style="position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 12px; margin-top: 20px;">
                                <p>Imprimé le : ${new Date().toLocaleString()} par {{ Auth::user()->first_name }}</p>
                            </footer>
                        </body>
                    </html>
                `;

                fenetreImpression.document.write(contenuImprimer);
                fenetreImpression.document.close();
                fenetreImpression.print();
            }

            $('.btnImprimer').on('click', imprimerRapport);

            // Export Excel
            $('#btnExportExcel').on('click', function() {
                if (typeof XLSX === 'undefined') {
                    alert('Erreur : la librairie XLSX (SheetJS) n\'est pas chargée.');
                    return;
                }
                var tables = document.querySelectorAll('.divPrint table');
                if (tables.length === 0) {
                    alert('Aucune table à exporter !');
                    return;
                }
                var wb = XLSX.utils.book_new();
                tables.forEach(function(table, idx) {
                    var ws = XLSX.utils.table_to_sheet(table);
                    XLSX.utils.book_append_sheet(wb, ws, 'Tableau' + (idx + 1));
                });
                XLSX.writeFile(wb, 'rapport-vente-menu.xlsx');
            });
        });
    </script>
@endsection
