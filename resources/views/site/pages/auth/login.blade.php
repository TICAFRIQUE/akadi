@extends('site.layouts.app')

@section('title', 'Se connecter')


@section('content')
    <div class="row">
        <div class="breadcumb-wrapper " data-bg-src="">
            <div class="container z-index-common">
                <div class="breadcumb-content">
                    <h1 class="breadcumb-title">Connexion</h1>
                    <ul class="breadcumb-menu">
                        <li><a href="{{ route('liste-produit') }}">Liste des plats</a></li>
                        <li><a href="i{{ route('page-acceuil') }}">Accueil</a></li>
                        <li>Connexion</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 m-auto my-3">
            <form action="{{ route('login') }}" method="post" class="woocommerce-form-login mb-30">
                @csrf
                <div class="form-group">
                    <label>Telephone </label>
                    <input type="number" name="phone" class="form-control" placeholder="Ex: 000000000" required>
                </div>

                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" class="form-control" name="password" placeholder="de********">
                </div>
                <input type="text" name="url_previous" value="{{ url()->previous() }}" hidden>

                <div class="form-group">
                    <button type="submit" class="th-btn rounded-2">Valider</button>
                    <p class="fs-xs mt-2 mb-0"><a class="text-reset" href="{{ route('register-form') }}">Vous n'avez pas de
                            compte?
                            <b class="text-danger">Créer un compte </b>
                        </a></p>
                </div>
            </form>
        </div>

    </div>
@endsection
