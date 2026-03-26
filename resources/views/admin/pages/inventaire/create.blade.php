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
                    <strong>Date de l'inventaire :</strong>
                    <b>{{ now()->format('d/m/Y H:i') }}</b>
                    — chaque produit est calculé depuis son dernier inventaire
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
                            <input type="text" id="search-produit" class="form-control"
                                placeholder="Rechercher un produit..." style="max-width:750px;">
                            <button type="button" class="btn btn-primary ml-2" id="export-produit-simple">
                                <i class="fas fa-download"></i> Télécharger la fiche produits
                            </button>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="table-inventaire">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Période</th> {{-- 👈 colonne intervalle par produit --}}
                                        @role('developpeur')
                                            <th>Stock dernier inventaire</th>
                                            <th>Stock ajouté</th>
                                            <th>Stock total</th>
                                            <th>Stock vendu</th>
                                            <th>Stock sorti</th>
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
                                    @foreach ($lignes as $i => $ligne)
                                        <tr class="ligne-produit">

                                            {{-- Nom du produit --}}
                                            <td class="nom-produit">
                                                <strong>{{ $ligne['produit']->nom }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $ligne['produit']->unite ?? '' }}</small>
                                            </td>

                                            {{-- 👇 Intervalle par produit --}}
                                            <td>
                                                <small>
                                                    @if (is_null($ligne['date_dernier_inventaire']))
                                                        <span class="badge badge-info">Premier inventaire</span>
                                                        <br>
                                                        <span class="text-muted">
                                                            Depuis création
                                                            <br>
                                                            au {{ now()->format('d/m/Y') }}
                                                        </span>
                                                    @else
                                                        Du
                                                        <strong>{{ $ligne['date_dernier_inventaire']->format('d/m/Y') }}</strong>
                                                        <br>
                                                        au <strong>{{ now()->format('d/m/Y') }}</strong>
                                                    @endif
                                                </small>
                                            </td>

                                            @role('developpeur')
                                                <td class="text-center">{{ format_price($ligne['stock_dernier_inventaire']) }}
                                                </td>
                                                <td class="text-center text-success">+
                                                    {{ format_price($ligne['stock_ajoute']) }}</td>
                                                <td class="text-center">{{ format_price($ligne['stock_total']) }}</td>
                                                <td class="text-center text-danger">- {{ format_price($ligne['stock_vendu']) }}
                                                </td>
                                                <td class="text-center text-warning">-
                                                    {{ format_price($ligne['stock_sortie']) }}</td>
                                                <td class="text-center">
                                                    <strong>{{ format_price($ligne['stock_restant']) }}</strong>
                                                </td>
                                            @endrole

                                            {{-- Saisie stock physique --}}
                                            <td>
                                                <input type="number" step="0.01" min="0"
                                                    name="lignes[{{ $i }}][stock_physique]"
                                                    class="form-control stock-physique" data-index="{{ $i }}"
                                                    data-stock-restant="{{ $ligne['stock_restant'] }}" placeholder="0"
                                                    required>
                                                <input type="hidden" name="lignes[{{ $i }}][product_base_id]"
                                                    value="{{ $ligne['product_base_id'] }}">
                                                <input type="hidden"
                                                    name="lignes[{{ $i }}][stock_dernier_inventaire]"
                                                    value="{{ $ligne['stock_dernier_inventaire'] }}">
                                                <input type="hidden" name="lignes[{{ $i }}][stock_ajoute]"
                                                    value="{{ $ligne['stock_ajoute'] }}">
                                                <input type="hidden" name="lignes[{{ $i }}][stock_total]"
                                                    value="{{ $ligne['stock_total'] }}">
                                                <input type="hidden" name="lignes[{{ $i }}][stock_vendu]"
                                                    value="{{ $ligne['stock_vendu'] }}">
                                                <input type="hidden" name="lignes[{{ $i }}][stock_sortie]"
                                                    value="{{ $ligne['stock_sortie'] }}">
                                                <input type="hidden" name="lignes[{{ $i }}][stock_restant]"
                                                    value="{{ $ligne['stock_restant'] }}">

                                                {{-- 👇 Ajout des dates de période --}}
                                                <input type="hidden" name="lignes[{{ $i }}][date_debut]"
                                                    value="{{ $ligne['date_debut']->format('Y-m-d H:i:s') }}">
                                                <input type="hidden" name="lignes[{{ $i }}][date_fin]"
                                                    value="{{ $ligne['date_fin']->format('Y-m-d H:i:s') }}">
                                            </td>

                                            @role('developpeur')
                                                <td class="ecart-cell text-center" id="ecart-{{ $i }}">—</td>
                                                <td class="resultat-cell text-center" id="resultat-{{ $i }}"></td>
                                            @endrole
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <a href="{{ route('inventaire.index') }}" class="btn btn-secondary mr-2">
                            <i class="fas fa-times"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Enregistrer l'inventaire
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('js')
    <script>
        $(document).ready(function() {

            // ─── Recherche temps réel ───
            $('#search-produit').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#table-inventaire tbody tr.ligne-produit').filter(function() {
                    $(this).toggle($(this).find('.nom-produit').text().toLowerCase().indexOf(
                        value) > -1);
                });
            });

            // ─── Export CSV fiche produits ───
            $('#export-produit-simple').on('click', function() {
                var user = "{{ Auth::user()->name }}";
                var dateNow = "{{ now()->format('d/m/Y H:i') }}";

                var produits = [];
                $('#table-inventaire tbody tr.ligne-produit').each(function() {
                    var nom = $(this).find('.nom-produit').text().trim();
                    var stock = $(this).find('input[name$="[stock_physique]"]').val() || '';
                    produits.push({
                        nom: nom,
                        stock: stock
                    });
                });

                // Tri alphabétique
                produits.sort((a, b) => a.nom.localeCompare(b.nom));

                var csv = 'Fiche produits\n';
                csv += 'Utilisateur;"' + user + '"\n';
                csv += 'Date;"' + dateNow + '"\n\n';
                csv += 'Nom du produit;Stock physique\n';
                produits.forEach(p => {
                    csv += '"' + p.nom.replace(/"/g, '""') + '";' + p.stock + '\n';
                });

                var blob = new Blob([csv], {
                    type: 'text/csv;charset=utf-8;'
                });
                var link = document.createElement('a');
                link.href = URL.createObjectURL(blob);
                link.download = 'fiche_produits_' + new Date().toISOString().slice(0, 10) + '.csv';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });

            // ─── Calcul écart et résultat ───
            function updateResultat(index) {
                // 👇 Lire le stock restant depuis le data attribute (plus fiable)
                var stockRestant = parseFloat($(".stock-physique[data-index='" + index + "']").data(
                    'stock-restant')) || 0;
                var stockPhysique = parseFloat($(".stock-physique[data-index='" + index + "']").val());
                var ecart = stockPhysique - stockRestant;

                // Afficher l'écart
                $("#ecart-" + index).text(isNaN(ecart) ? '—' : ecart.toFixed(2));

                // Afficher le résultat
                var $cell = $("#resultat-" + index);
                if (isNaN(ecart)) {
                    $cell.html('');
                } else if (stockRestant === 0 && stockPhysique === 0) {
                    $cell.html('<span class="badge badge-warning">Rupture</span>');
                } else if (Math.abs(ecart) < 0.001) {
                    $cell.html('<span class="badge badge-success">Conforme</span>');
                } else if (ecart > 0) {
                    $cell.html('<span class="badge badge-info">Surplus</span>');
                } else {
                    $cell.html('<span class="badge badge-danger">Perte</span>');
                }
            }

            // Événement sur saisie
            $(document).on('input', '.stock-physique', function() {
                updateResultat($(this).data('index'));
            });

            // Initialiser à l'ouverture
            $('.stock-physique').trigger('input');
        });
    </script>
@endpush
