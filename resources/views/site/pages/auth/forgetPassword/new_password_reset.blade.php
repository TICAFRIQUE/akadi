@extends('site.layouts.app')

{{-- @section('title', 'Nouveau mot de passe') --}}

@section('content')
    @php
        $msg_validation = ' Champs obligatoire';
    @endphp

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

            @include('admin.components.validationMessage')
            <h3 class="">Definir un nouveau mot de passe</h3>

            <form novalidate class="account-form mt-3 needs-validation form-horizontal" method="POST"
                action="{{ route('reset.password.post') }}">
                @csrf
                <div>
                    <div class="form-group">
                        <input id="password" class="form-control" type="password" placeholder="Nouveau mot de passe"
                            name="password" required>
                        <div class="invalid-feedback"> {{ $msg_validation }} </div>
                    </div>
                    <div class="form-group">
                        <input id="confirm_password" class="form-control" type="password"
                            placeholder="Confirmer le nouveau mot de passe" name="confirm_password" required>
                        <div class="invalid-feedback"> {{ $msg_validation }} </div>
                        <p id="Msg_pwd"></p>
                    </div>
                    <input type="text" value="{{ request('token') }}" name="token" hidden>
                </div>

                <button type="submit" class="th-btn rounded-2 btn-register">Valider </button>
            </form>
        </div>

    </div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
            // Example starter JavaScript for disabling form submissions if there are invalid fields
            (function() {
                'use strict'

                // Fetch all the forms we want to apply custom Bootstrap validation styles to
                var forms = document.querySelectorAll('.needs-validation')

                // Loop over them and prevent submission
                Array.prototype.slice.call(forms)
                    .forEach(function(form) {
                        form.addEventListener('submit', function(event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }

                            form.classList.add('was-validated')
                        }, false)
                    })
            })
            ()


            //cacher le btn register
            $('.btn-register').hide();

            //on verifie si les deux mot passe correspondent
            $('#password, #confirm_password').on('keyup', function() {
                var password = $('#password').val()
                var confirm_password = $('#confirm_password').val()

                if (password == confirm_password && password.length >= 8 && confirm_password.length >= 8) {
                    $('#Msg_pwd').html('les mots de passe sont identiques!').css('color', 'green');
                    $('.btn-register').show(200);
                } else if (password != confirm_password) {
                    $('#Msg_pwd').html('les mots de passe ne sont pas identique!').css('color', 'red');
                    $('.btn-register').hide(200);
                } else if (password.length < 8 && confirm_password.length < 8) {
                    $('#Msg_pwd').html("le mot de passe doit etre 8 caractere minimun").css('color', 'red');
                    $('.btn-register').hide(200);
                }

            });

            //on affiche le champs profil autre si  l'utilisateur coche la case "Autre"
        </script>
@endsection
