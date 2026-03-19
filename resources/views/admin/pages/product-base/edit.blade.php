@extends('admin.layouts.app')
@section('title', 'produits de base')
@section('sub-title', 'Modifier un produit de base')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header">
                            <h4>Modifier: {{ $productBase->nom }}</h4>
                        </div>

                        @include('admin.components.validationMessage')

                        <form action="{{ route('product-base.update', $productBase->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nom">Nom du produit <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" 
                                           name="nom" 
                                           value="{{ old('nom', $productBase->nom) }}" 
                                           required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="stock">Stock actuel</label>
                                            <input type="number" 
                                                   class="form-control @error('stock') is-invalid @enderror" 
                                                   id="stock" 
                                                   name="stock" 
                                                   value="{{ old('stock', $productBase->stock) }}" 
                                                   step="0.01" 
                                                   min="0">
                                            @error('stock')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Modifiez avec précaution - Préférez les achats pour ajuster le stock</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="stock_alerte">Stock d'alerte <span class="text-danger">*</span></label>
                                            <input type="number" 
                                                   class="form-control @error('stock_alerte') is-invalid @enderror" 
                                                   id="stock_alerte" 
                                                   name="stock_alerte" 
                                                   value="{{ old('stock_alerte', $productBase->stock_alerte) }}" 
                                                   step="0.01" 
                                                   min="0" 
                                                   required>
                                            @error('stock_alerte')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="unite">Unité de mesure <span class="text-danger">*</span></label>
                                            <select class="form-control @error('unite') is-invalid @enderror" 
                                                    id="unite" 
                                                    name="unite" 
                                                    required>
                                                <option value="">-- Sélectionner --</option>
                                                <option value="unité" {{ old('unite', $productBase->unite) == 'unité' ? 'selected' : '' }}>Unité</option>
                                                <option value="kg" {{ old('unite', $productBase->unite) == 'kg' ? 'selected' : '' }}>Kilogramme (kg)</option>
                                                <option value="litre" {{ old('unite', $productBase->unite) == 'litre' ? 'selected' : '' }}>Litre</option>
                                                <option value="gramme" {{ old('unite', $productBase->unite) == 'gramme' ? 'selected' : '' }}>Gramme</option>
                                                <option value="ml" {{ old('unite', $productBase->unite) == 'ml' ? 'selected' : '' }}>Millilitre (ml)</option>
                                                <option value="sachet" {{ old('unite', $productBase->unite) == 'sachet' ? 'selected' : '' }}>Sachet</option>
                                                <option value="carton" {{ old('unite', $productBase->unite) == 'carton' ? 'selected' : '' }}>Carton</option>
                                            </select>
                                            @error('unite')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="prix_achat_moyen">Prix moyen d'achat (FCFA)</label>
                                            <input type="number" 
                                                   class="form-control @error('prix_achat_moyen') is-invalid @enderror" 
                                                   id="prix_achat_moyen" 
                                                   name="prix_achat_moyen" 
                                                   value="{{ old('prix_achat_moyen', $productBase->prix_achat_moyen) }}" 
                                                   step="1" 
                                                   min="0">
                                            @error('prix_achat_moyen')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Calculé automatiquement selon les achats</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="actif" 
                                               name="actif" 
                                               value="1" 
                                               {{ old('actif', $productBase->actif) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="actif">Produit actif</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description (optionnelle)</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="3">{{ old('description', $productBase->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Informations supplémentaires --}}
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">Informations</h6>
                                    <ul class="mb-0">
                                        <li>Créé le: {{ $productBase->created_at->format('d/m/Y à H:i') }}</li>
                                        <li>Dernière modification: {{ $productBase->updated_at->format('d/m/Y à H:i') }}</li>
                                        <li>Nombre de produits liés: {{ $productBase->products->count() }}</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <a href="{{ route('product-base.index') }}" class="btn btn-secondary">
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
