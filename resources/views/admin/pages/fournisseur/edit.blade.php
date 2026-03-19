@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Modifier Fournisseur</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('fournisseur.index') }}">Fournisseurs</a></div>
            <div class="breadcrumb-item">Modifier</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Informations du fournisseur</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('fournisseur.update', $fournisseur) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Nom <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="nom" class="form-control @error('nom') is-invalid @enderror" 
                                        value="{{ old('nom', $fournisseur->nom) }}" required>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Téléphone</label>
                                <div class="col-sm-9">
                                    <input type="text" name="telephone" class="form-control @error('telephone') is-invalid @enderror" 
                                        value="{{ old('telephone', $fournisseur->telephone) }}">
                                    @error('telephone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                        value="{{ old('email', $fournisseur->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Adresse</label>
                                <div class="col-sm-9">
                                    <input type="text" name="adresse" class="form-control @error('adresse') is-invalid @enderror" 
                                        value="{{ old('adresse', $fournisseur->adresse) }}">
                                    @error('adresse')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Notes</label>
                                <div class="col-sm-9">
                                    <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $fournisseur->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Statut</label>
                                <div class="col-sm-9">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="actif" class="custom-control-input" id="actif" 
                                            {{ old('actif', $fournisseur->actif) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="actif">Actif</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Mettre à jour
                                    </button>
                                    <a href="{{ route('fournisseur.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Annuler
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
