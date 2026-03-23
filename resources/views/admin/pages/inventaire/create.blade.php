@extends('admin.layouts.app')
@section('title', 'Nouvel inventaire')
@section('sub-title', 'Saisie de l\'inventaire')
@section('content')
<section class="section">
    <div class="section-header">
        <h1>Nouvel inventaire</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('inventaire.index') }}">Inventaires</a></div>
            <div class="breadcrumb-item">Nouveau</div>
        </div>
    </div>
    <div class="section-body">
        <div class="mb-3">
            <div class="alert alert-info">
                <strong>Période de l'inventaire :</strong>
                @php
                    $date_debut = $lignes[0]['produit']->created_at;
                    $date_fin = now();
                    foreach($lignes as $ligne) {
                        if(isset($ligne['produit']->created_at) && $ligne['produit']->created_at < $date_debut) {
                            $date_debut = $ligne['produit']->created_at;
                        }
                    }
                @endphp
                Du <b>{{ \Carbon\Carbon::parse($date_debut)->format('d/m/Y H:i') }}</b>
                au <b>{{ \Carbon\Carbon::parse($date_fin)->format('d/m/Y H:i') }}</b>
            </div>
            <div class="row mb-2">
                <div class="col-md-4">
                    <strong>Réalisé par :</strong> {{ Auth::user()->name }}
                </div>
                <div class="col-md-4">
                    <strong>Date de création :</strong> {{ now()->format('d/m/Y H:i') }}
                </div>
                <div class="col-md-4">
                    <strong>Nombre de produits :</strong> {{ count($lignes) }}
                </div>
            </div>
        </div>
        <form action="{{ route('inventaire.store') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <input type="text" id="search-produit" class="form-control" placeholder="Rechercher un produit..." style="max-width:750px;">
                        <button type="button" class="btn btn-primary ml-2" id="export-produit-simple"><i class="fas fa-download"></i> Télécharger la fiche produits</button>
                    </div>
                    <table class="table table-bordered" id="table-inventaire">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                @role('developpeur')
                                    <th>Stock dernier inventaire</th>
                                    <th>Stock ajouté</th>
                                    <th>Stock total</th>
                                    <th>Stock vendu</th>
                                    <th>Stock sortie</th>
                                    <th>Stock restant</th>
                                @endrole
                                <th>Stock physique</th>
                                @role('developpeur')
                                    <th>Écart</th>
                                    <th>Résultat</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($lignes as $i => $ligne)
                            <tr class="ligne-produit">
                                <td class="nom-produit">{{ $ligne['produit']->nom }}</td>
                                @role('developpeur')
                                    <td>{{ format_price($ligne['stock_dernier_inventaire']) }}</td>
                                    <td>{{ format_price($ligne['stock_ajoute']) }}</td>
                                    <td>{{ format_price($ligne['stock_total']) }}</td>
                                    <td>{{ format_price($ligne['stock_vendu']) }}</td>
                                    <td>{{ format_price($ligne['stock_sortie']) }}</td>
                                    <td>{{ format_price($ligne['stock_restant']) }}</td>
                                @endrole
                                <td>
                                    <input type="number" step="0.01" name="lignes[{{ $i }}][stock_physique]" class="form-control stock-physique" data-index="{{ $i }}" required>
                                    <input type="hidden" name="lignes[{{ $i }}][product_base_id]" value="{{ $ligne['product_base_id'] }}">
                                    <input type="hidden" name="lignes[{{ $i }}][stock_dernier_inventaire]" value="{{ $ligne['stock_dernier_inventaire'] }}">
                                    <input type="hidden" name="lignes[{{ $i }}][stock_ajoute]" value="{{ $ligne['stock_ajoute'] }}">
                                    <input type="hidden" name="lignes[{{ $i }}][stock_total]" value="{{ $ligne['stock_total'] }}">
                                    <input type="hidden" name="lignes[{{ $i }}][stock_vendu]" value="{{ $ligne['stock_vendu'] }}">
                                    <input type="hidden" name="lignes[{{ $i }}][stock_sortie]" value="{{ $ligne['stock_sortie'] }}">
                                    <input type="hidden" name="lignes[{{ $i }}][stock_restant]" value="{{ $ligne['stock_restant'] }}">
                                </td>
                                @role('developpeur')
                                    <td class="ecart-cell" id="ecart-{{ $i }}"></td>
                                    <td class="resultat-cell" id="resultat-{{ $i }}"></td>
                                @endrole
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Enregistrer l'inventaire</button>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Recherche temps réel produit
        $('#search-produit').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            $('#table-inventaire tbody tr.ligne-produit').filter(function() {
                $(this).toggle($(this).find('.nom-produit').text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Export fiche produits simple (nom, stock physique, entête user/date)
        $('#export-produit-simple').on('click', function() {
            // Récupérer les infos d'entête
            var user = "{{ Auth::user()->name }}";
            var dateDebut = "{{ \Carbon\Carbon::parse($date_debut)->format('d/m/Y H:i') }}";
            var dateFin = "{{ \Carbon\Carbon::parse($date_fin)->format('d/m/Y H:i') }}";
            // Récupérer les lignes produits (nom, stock physique)
            var produits = [];
            $('#table-inventaire tbody tr.ligne-produit').each(function() {
                var nom = $(this).find('.nom-produit').text().trim();
                var stock = $(this).find('input[name$="[stock_physique]"]').val() || '';
                produits.push({ nom: nom, stock: stock });
            });
            // Tri alphabétique
            produits.sort(function(a, b) {
                return a.nom.localeCompare(b.nom);
            });
            // Générer le contenu CSV
            var csv = 'Fiche produits\n';
            csv += 'Utilisateur;"' + user + '"\n';
            csv += 'Période;"' + dateDebut + ' - ' + dateFin + '"\n';
            csv += '\n';
            csv += 'Nom du produit;Stock physique\n';
            produits.forEach(function(p) {
                csv += '"' + p.nom.replace(/"/g, '""') + '";' + p.stock + '\n';
            });
            // Télécharger le fichier
            var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'fiche_produits_' + (new Date().toISOString().slice(0,10)) + '.csv';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

        function updateResultat(index) {
            var stockRestant = parseFloat($("input[name='lignes["+index+"][stock_restant]']").val());
            var stockPhysique = parseFloat($("input[name='lignes["+index+"][stock_physique]']").val());
            var ecart = stockPhysique - stockRestant;
            var cell = $("#resultat-"+index);
            if (isNaN(ecart)) {
                cell.html("");
            } else if (stockRestant === 0 && stockPhysique === 0) {
                cell.html('<span class="badge badge-warning">Rupture</span>');
            } else if (stockPhysique > stockRestant) {
                cell.html('<span class="badge badge-info">Surplus</span>');
            } else if (Math.abs(ecart) < 0.00001) {
                cell.html('<span class="badge badge-success">Conforme</span>');
            } else if (ecart < 0) {
                cell.html('<span class="badge badge-danger">Perte</span>');
            } else if (ecart > 0) {
                cell.html('<span class="badge badge-warning">Rupture</span>');
            }
        }
        $(".stock-physique").on('input', function() {
            var index = $(this).data('index');
            var stockRestant = parseFloat($(this).closest('tr').find('input[name=\"lignes['+index+'][stock_restant]\"]').val());
            var stockPhysique = parseFloat($(this).val());
            var ecart = (stockPhysique - stockRestant);
            $("#ecart-"+index).text(isNaN(ecart) ? '' : ecart);
            updateResultat(index);
        });
        $(".stock-physique").trigger('input'); // Initialiser à l'ouverture
    });
</script>
@endpush
