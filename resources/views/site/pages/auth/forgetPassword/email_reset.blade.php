@extends('site.layouts.app')

@section('title', 'Recuperation de mot de passe')

@section('content')

    <div class="row">
        <div class="breadcumb-wrapper " data-bg-src="">
            <div class="container z-index-common">
                <div class="breadcumb-content">
                    <h1 class="breadcumb-title">Reinitialisation de mot de passe</h1>
                    <ul class="breadcumb-menu">
                        <li><a href="i{{ route('page-acceuil') }}">Accueil</a></li>
                        <li>Recuperer mon mot de passe</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 m-auto my-3">
            <div class="m-auto">
                <ol>
                    <li>Entrer votre email pour reinitialiser votre mot de passe </li>
                    <li>Verifier votre email et cliquez sur le bouton ci-dessous </li>

                </ol>
            </div>
            @include('admin.components.validationMessage') 
            <form action="{{ route('forget.password.post') }}" method="post" class="woocommerce-form-login mb-30">
                @csrf
                <div class="form-group">
                    <label>Email </label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <button type="submit" class="th-btn rounded-2">Envoyer 
                
                <div class="spinner-grow text-dark" role="status">
                <span class="visually-hidden">Loading...</span>
                </div>
                </button>

            </form>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            $('.spinner-grow').hide();
        
            $('form').submit(function (e) { 

                  var email = $('#email').val()
                if (email) {
                    $('.th-btn').prop("disabled", true);
                    $(".spinner-grow").show();
                }
            });

        });
    </script>

@endsection
