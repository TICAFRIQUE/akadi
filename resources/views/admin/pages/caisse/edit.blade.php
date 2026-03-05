@extends('admin.layouts.app')
@section('title', 'Modifier la caisse')
@section('sub-title', 'Modifier la caisse')

@section('content')
<div class="section-body">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-cash-register mr-2"></i>Modifier : {{ $caisse->nom }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('caisse.update', $caisse->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" required value="{{ old('nom', $caisse->nom) }}">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" class="form-control" value="{{ old('description', $caisse->description) }}">
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="actif" name="actif" value="1" {{ $caisse->actif ? 'checked' : '' }}>
                                <label class="custom-control-label" for="actif">Caisse active</label>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-save mr-1"></i> Enregistrer
                            </button>
                            <a href="{{ route('caisse.index') }}" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
