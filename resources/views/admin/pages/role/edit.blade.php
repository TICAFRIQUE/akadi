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

                                @if ($permissions->count())
                                    <div class="form-group">
                                        <label>Permissions</label>
                                        <div class="row">
                                            @foreach ($permissions as $perm)
                                                <div class="col-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="permissions[]" value="{{ $perm->name }}"
                                                            id="perm_{{ $perm->id }}"
                                                            {{ $role->permissions->contains('name', $perm->name) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="perm_{{ $perm->id }}">
                                                            {{ $perm->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

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
