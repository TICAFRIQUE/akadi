@extends('admin.layouts.app')
@section('title', 'roles')
@section('sub-title', 'Modifier un rôle')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <a class="btn btn-primary fas fa-arrow-left mb-2 ml-3"
                    href="{{ route('role.index') }}"> Retour à la liste des rôles</a>

                <div class="col-12 col-md-8 col-lg-7 m-auto">
                    @include('admin.components.validationMessage')
                    <div class="card">
                        <div class="card-header">
                            <h4>Modification du rôle : <strong>{{ $role->name }}</strong></h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('role.update', $role->id) }}" method="POST"
                                class="needs-validation" novalidate="">
                                @csrf
                                <div class="form-group">
                                    <label for="roleName">Nom du rôle</label>
                                    <input type="text" id="roleName" name="name"
                                        value="{{ $role->name }}" class="form-control" required>
                                    <div class="invalid-feedback">Champs obligatoire</div>
                                </div>

                                <div class="alert alert-info py-2 small">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Les permissions sont gérées directement sur chaque utilisateur.
                                </div>

                                <div class="form-group mt-3">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        Modifier
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
