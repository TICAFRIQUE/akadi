@extends('admin.layouts.app')
@section('title', 'Permissions')
@section('sub-title', 'Modifier la permission')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12 mb-3">
                    <a href="{{ route('permission.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Retour à la liste
                    </a>
                </div>

                <div class="col-12 col-md-8 col-lg-6 mx-auto">
                    @include('admin.components.validationMessage')

                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="fas fa-key mr-2"></i>
                                Modifier : <strong>{{ $permission->name }}</strong>
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('permission.update', $permission->id) }}" method="POST"
                                class="needs-validation" novalidate>
                                @csrf

                                <div class="form-group">
                                    <label for="permName">Nom de la permission <span class="text-danger">*</span></label>
                                    <input type="text" id="permName" name="name"
                                        value="{{ old('name', $permission->name) }}"
                                        class="form-control @error('name') is-invalid @enderror" required>
                                    <small class="form-text text-muted">Ex : <code>voir-commandes</code>, <code>modifie.produits</code>  </small>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="alert alert-info py-2 small">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Les permissions sont attribuées directement aux utilisateurs, pas aux rôles.
                                </div>

                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-save mr-1"></i> Enregistrer les modifications
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
