@extends('admin.layouts.app')

@section('title', 'auth')
@section('sub-title', 'Creer un utilisateur')

@section('css')
    {{-- <link rel="stylesheet" href="{{ asset('admin/assets/bundles/jquery-selectric/selectric.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/bundles/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="container mt-1">
            <div class="row">
                <a class="btn btn-primary fas fa-arrow-left mb-2" href="{{ route('user.list') }}"> Retour à la liste des
                    utilisateurs</a>
                <div
                    class="col-12 col-sm-10 offset-sm-1 col-md-10 offset-md-2 col-lg-10 offset-lg-2 col-xl-10 offset-xl-2 m-auto">
                   

                    <div class="card card-primary">
                        @include('admin.components.validationMessage')
                        <div>

                        </div>
                        <div class="card-header">
                            <h4>Nouvel utilisateur</h4>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate="" method="POST"
                                action="{{ route('user.register') }}">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="frist_name">Nom & prenoms</label>
                                        <input id="frist_name" type="text" class="form-control" name="name" autofocus
                                            required>
                                        <div class="invalid-feedback">
                                            Champs obligatoire
                                        </div>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="last_name">Telephone</label>
                                        <input id="last_name" type="number" class="form-control" name="phone" required>
                                        <div class="invalid-feedback">
                                            Champs obligatoire
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- <div class="form-group col-6">
                                        <label for="password" class="d-block">Mot de password</label>
                                        <input id="password" type="password" class="form-control pwstrength"
                                            data-indicator="pwindicator" name="password">
                                        <div id="pwindicator" class="pwindicator">
                                            <div class="bar"></div>
                                            <div class="label"></div>
                                        </div>
                                    </div> --}}
                                    <div class="form-group col-6">
                                        <label for="email">Email</label>
                                        <input id="email" type="email" class="form-control" name="email" required>
                                        <div class="invalid-feedback">
                                            Champs obligatoire
                                        </div>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="password2" class="d-block">Role</label>
                                        <select name="role" class="form-control select2" required>
                                            <option disabled selected value>Choisir un role</option>
                                            @foreach ($roles as $item)
                                                <option value="{{ $item['name'] }}"> {{ $item['name'] }} </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Champs obligatoire
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Date d'anniversaire <strong>(Peut-être qu'une surprise vous atteindra le
                                            Jour-J)</strong></label>
                                    <div class="row">
                                        <div class="col-6">
                                            <select name="jour" class="form-control">
                                                <option disabled selected>Jour</option>
                                                @for ($i = 1; $i < 32; $i++)
                                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">
                                                        {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select name="mois" class="form-control">
                                                @php
                                                    $month = [
                                                        'janvier',
                                                        'février',
                                                        'mars',
                                                        'avril',
                                                        'mai',
                                                        'juin',
                                                        'juillet',
                                                        'août',
                                                        'septembre',
                                                        'octobre',
                                                        'novembre',
                                                        'décembre',
                                                    ];
                                                @endphp
                                                <option disabled selected>Mois</option>
                                                @foreach ($month as $key => $item)
                                                    <option value="{{ str_pad(++$key, 2, '0', STR_PAD_LEFT) }}">
                                                        {{ $item }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-8">
                                        <label for="password" class="d-block">Mot de passe </label>
                                        <input id="password" type="password" class="form-control" name="password"
                                            aria-autocomplete="none" autocomplete="off">
                                    </div>
                                    <div class="form-group col-4 my-auto">
                                        @include('admin.components.hideshowpwd')
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        Enregistrer
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

@section('script')
    <script src="{{ asset('admin/assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
    {{-- <script src="{{ asset('admin/assets/bundles/jquery-selectric/jquery.selectric.min.js') }}"></script> --}}
@endsection
