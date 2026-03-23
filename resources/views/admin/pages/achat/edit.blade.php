@extends('admin.layouts.app')
@section('title', 'achats')
@section('sub-title', 'Modifier un achat')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Modifier l'achat: {{ $achat->numero }}</h4>
                        </div>

                        @include('admin.components.validationMessage')

                        <form action="{{ route('achat.update', $achat->id) }}" method="POST" id="formAchat">
                            @csrf
                            @method('PUT')
                            
                            <div class="card-body">
                                {{-- Informations générales --}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="date_achat">Date d'achat <span class="text-danger">*</span></label>
                                            <input type="date" 
                                                   class="form-control @error('date_achat') is-invalid @enderror" 
                                                   id="date_achat" 
                                                   name="date_achat" 
                                                   value="{{ old('date_achat', $achat->date_achat->format('Y-m-d')) }}" 
                                                   required>
                                            @error('date_achat')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fournisseur_id">Fournisseur</label>
                                            <select class="form-control @error('fournisseur_id') is-invalid @enderror" 
                                                    id="fournisseur_id" 
                                                    name="fournisseur_id">
                                                <option value="">-- Sélectionner un fournisseur --</option>
                                                @foreach($fournisseurs ?? [] as $f)
                                                    <option value="{{ $f->id }}" 
                                                        {{ old('fournisseur_id', $achat->fournisseur_id) == $f->id ? 'selected' : '' }}>
                                                        {{ $f->nom }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('fournisseur_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            {{-- <small class="form-text text-muted">Ou saisir manuellement ci-dessous</small> --}}
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fournisseur">Fournisseur (texte libre)</label>
                                            <input type="text" 
                                                   class="form-control @error('fournisseur') is-invalid @enderror" 
                                                   id="fournisseur" 
                                                   name="fournisseur" 
                                                   value="{{ old('fournisseur', $achat->fournisseur) }}" 
                                                   placeholder="Nom du fournisseur">
                                            @error('fournisseur')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div> --}}

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="numero_facture">N° Facture</label>
                                            <input type="text" 
                                                   class="form-control @error('numero_facture') is-invalid @enderror" 
                                                   id="numero_facture" 
                                                   name="numero_facture" 
                                                   value="{{ old('numero_facture', $achat->numero_facture) }}" 
                                                   placeholder="Ex: FACT-2026-001">
                                            @error('numero_facture')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                

                                <hr>

                                {{-- Lignes de produits --}}
                                <h5 class="mb-3">Produits achetés</h5>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="tableLignes">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="40%">Produit de base <span class="text-danger">*</span></th>
                                                <th width="20%">Quantité <span class="text-danger">*</span></th>
                                                <th width="20%">Prix unitaire (FCFA) <span class="text-danger">*</span></th>
                                                <th width="15%">Montant ligne</th>
                                                <th width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="lignesContainer">
                                            @foreach(old('lignes', $achat->lignes) as $index => $ligne)
                                                <tr class="ligne-produit" data-ligne-id="{{ is_object($ligne) ? $ligne->id : '' }}">
                                                    <td>
                                                        <input type="hidden" name="lignes[{{ $index }}][id]" value="{{ is_object($ligne) ? $ligne->id : '' }}">
                                                        <select class="form-control product-select" name="lignes[{{ $index }}][product_base_id]" required>
                                                            <option value="">-- Sélectionner un produit --</option>
                                                            @foreach($productBases as $pb)
                                                                <option value="{{ $pb->id }}" 
                                                                        data-unite="{{ $pb->unite }}"
                                                                        {{ (is_object($ligne) ? $ligne->product_base_id : $ligne['product_base_id'] ?? null) == $pb->id ? 'selected' : '' }}>
                                                                    {{ $pb->nom }} ({{ $pb->unite }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" 
                                                               class="form-control quantite-input" 
                                                               name="lignes[{{ $index }}][quantite]" 
                                                               step="0.01" 
                                                               min="0.01" 
                                                               value="{{ is_object($ligne) ? $ligne->quantite : ($ligne['quantite'] ?? '') }}"
                                                               placeholder="0" 
                                                               required>
                                                    </td>
                                                    <td>
                                                        <input type="number" 
                                                               class="form-control prix-input" 
                                                               name="lignes[{{ $index }}][prix_unitaire]" 
                                                               step="1" 
                                                               min="0" 
                                                               value="{{ is_object($ligne) ? $ligne->prix_unitaire : ($ligne['prix_unitaire'] ?? '') }}"
                                                               placeholder="0" 
                                                               required>
                                                    </td>
                                                    <td>
                                                        <input type="text" 
                                                               class="form-control montant-ligne" 
                                                               readonly 
                                                               value="{{ is_object($ligne) ? $ligne->montant_ligne : ($ligne['montant_ligne'] ?? 0) }}">
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-danger btn-sm btn-remove-ligne">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5">
                                                    <button type="button" class="btn btn-success btn-sm" id="btnAjouterLigne">
                                                        <i class="fas fa-plus"></i> Ajouter un produit
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr class="table-info">
                                                <td colspan="3" class="text-right"><strong>MONTANT TOTAL:</strong></td>
                                                <td colspan="2">
                                                    <strong id="montantTotal">{{ format_price($achat->montant_total) }} FCFA</strong>
                                                    <input type="hidden" name="montant_total" id="montant_total_input" value="{{ $achat->montant_total }}">
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <a href="{{ route('achat.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Mettre à jour
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        console.log('Script achat edit chargé');
        let ligneIndex = {{ count(old('lignes', $achat->lignes)) }};

        // Calculer les montants au chargement
        $('.ligne-produit').each(function() {
            calculerMontantLigne($(this));
        });

        // Fonction pour calculer le montant d'une ligne
        function calculerMontantLigne($ligne) {
            const quantite = parseFloat($ligne.find('.quantite-input').val()) || 0;
            const prixUnitaire = parseFloat($ligne.find('.prix-input').val()) || 0;
            const montant = quantite * prixUnitaire;
            
            $ligne.find('.montant-ligne').val(montant.toFixed(0));
            calculerMontantTotal();
        }

        // Fonction pour calculer le montant total
        function calculerMontantTotal() {
            let total = 0;
            $('.ligne-produit').each(function() {
                const montant = parseFloat($(this).find('.montant-ligne').val()) || 0;
                total += montant;
            });
            
            $('#montantTotal').text(total.toLocaleString('fr-FR') + ' FCFA');
            $('#montant_total_input').val(total);
        }

        // Événement sur changement de quantité ou prix
        $(document).on('input', '.quantite-input, .prix-input', function() {
            console.log('Calcul montant...');
            calculerMontantLigne($(this).closest('.ligne-produit'));
        });

        // Ajouter une ligne
        $('#btnAjouterLigne').on('click', function() {
            console.log('Ajout ligne...');
            const nouvelleLigne = `
                <tr class="ligne-produit">
                    <td>
                        <input type="hidden" name="lignes[${ligneIndex}][id]" value="">
                        <select class="form-control product-select" name="lignes[${ligneIndex}][product_base_id]" required>
                            <option value="">-- Sélectionner un produit --</option>
                            @foreach($productBases as $pb)
                                <option value="{{ $pb->id }}" data-unite="{{ $pb->unite }}">
                                    {{ $pb->nom }} ({{ $pb->unite }})
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" class="form-control quantite-input" name="lignes[${ligneIndex}][quantite]" step="0.01" min="0.01" placeholder="0" required>
                    </td>
                    <td>
                        <input type="number" class="form-control prix-input" name="lignes[${ligneIndex}][prix_unitaire]" step="1" min="0" placeholder="0" required>
                    </td>
                    <td>
                        <input type="text" class="form-control montant-ligne" readonly value="0">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm btn-remove-ligne">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            $('#lignesContainer').append(nouvelleLigne);
            ligneIndex++;
        });

        // Supprimer une ligne
        $(document).on('click', '.btn-remove-ligne', function() {
            console.log('Suppression ligne...');
            if ($('.ligne-produit').length > 1) {
                if (confirm('Êtes-vous sûr de vouloir supprimer cette ligne ?')) {
                    $(this).closest('.ligne-produit').remove();
                    calculerMontantTotal();
                }
            } else {
                alert('Vous devez conserver au moins une ligne de produit.');
            }
        });

        // Validation du formulaire
        $('#formAchat').on('submit', function(e) {
            let valid = true;
            let messages = [];

            // Vérifier qu'il y a au moins une ligne
            if ($('.ligne-produit').length === 0) {
                valid = false;
                messages.push('Vous devez avoir au moins un produit');
            }

            // Vérifier que toutes les lignes sont remplies
            $('.ligne-produit').each(function(index) {
                const productId = $(this).find('.product-select').val();
                const quantite = $(this).find('.quantite-input').val();
                const prix = $(this).find('.prix-input').val();

                if (!productId || !quantite || !prix) {
                    valid = false;
                    messages.push(`Ligne ${index + 1}: Tous les champs sont requis`);
                }
            });

            if (!valid) {
                e.preventDefault();
                alert('Erreurs de validation:\\n' + messages.join('\\n'));
                return false;
            }
        });
        
        console.log('Script achat edit initialisé');
    });
</script>
@endpush
