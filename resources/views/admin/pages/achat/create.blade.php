@extends('admin.layouts.app')
@section('title', 'achats')
@section('sub-title', 'Nouvel achat')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Enregistrer un nouvel achat</h4>
                        </div>

                        @include('admin.components.validationMessage')

                        <form action="{{ route('achat.store') }}" method="POST" id="formAchat">
                            @csrf
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
                                                   value="{{ old('date_achat', date('Y-m-d')) }}" 
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
                                                    <option value="{{ $f->id }}" {{ old('fournisseur_id') == $f->id ? 'selected' : '' }}>
                                                        {{ $f->nom }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('fournisseur_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Ou saisir manuellement ci-dessous</small>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fournisseur">Fournisseur (texte libre)</label>
                                            <input type="text" 
                                                   class="form-control @error('fournisseur') is-invalid @enderror" 
                                                   id="fournisseur" 
                                                   name="fournisseur" 
                                                   value="{{ old('fournisseur') }}" 
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
                                                   value="{{ old('numero_facture') }}" 
                                                   placeholder="Ex: FACT-2026-001">
                                            @error('numero_facture')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" 
                                              name="notes" 
                                              rows="2">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div> --}}

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
                                            {{-- Ligne par défaut --}}
                                            <tr class="ligne-produit">
                                                <td>
                                                    <select class="form-control product-select" name="lignes[0][product_base_id]" required>
                                                        <option value="">-- Sélectionner un produit --</option>
                                                        @foreach($productBases as $pb)
                                                            <option value="{{ $pb->id }}" data-unite="{{ $pb->unite }}">
                                                                {{ $pb->nom }} ({{ $pb->unite }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           class="form-control quantite-input" 
                                                           name="lignes[0][quantite]" 
                                                           step="0.01" 
                                                           min="0.01" 
                                                           placeholder="0" 
                                                           required>
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           class="form-control prix-input" 
                                                           name="lignes[0][prix_unitaire]" 
                                                           step="1" 
                                                           min="0" 
                                                           placeholder="0" 
                                                           required>
                                                </td>
                                                <td>
                                                    <input type="text" 
                                                           class="form-control montant-ligne" 
                                                           readonly 
                                                           value="0">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-sm btn-remove-ligne" disabled>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
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
                                                    <strong id="montantTotal">0 FCFA</strong>
                                                    <input type="hidden" name="montant_total" id="montant_total_input" value="0">
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
                                    <i class="fas fa-save"></i> Enregistrer l'achat
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
        console.log('Script achat chargé');
        let ligneIndex = 1;

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
            
            // Activer le bouton de suppression si plus d'une ligne
            if ($('.ligne-produit').length > 1) {
                $('.btn-remove-ligne').prop('disabled', false);
            }
        });

        // Supprimer une ligne
        $(document).on('click', '.btn-remove-ligne', function() {
            console.log('Suppression ligne...');
            if ($('.ligne-produit').length > 1) {
                $(this).closest('.ligne-produit').remove();
                calculerMontantTotal();
                
                // Désactiver le bouton de suppression s'il ne reste qu'une ligne
                if ($('.ligne-produit').length === 1) {
                    $('.btn-remove-ligne').prop('disabled', true);
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
                messages.push('Vous devez ajouter au moins un produit');
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
        
        console.log('Script achat initialisé');
    });
</script>
@endpush
