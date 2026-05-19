@extends('site.layouts.app')

@section('title', 'Inscription')


@section('content')
    <div class="row">
        <div class="breadcumb-wrapper " data-bg-src="">
            <div class="container z-index-common">
                <div class="breadcumb-content">
                    <h1 class="breadcumb-title">Inscription</h1>
                    <ul class="breadcumb-menu">
                        <li><a href="{{ route('liste-produit') }}">Liste des plats</a></li>
                        <li><a href="i{{ route('page-acceuil') }}">Accueil</a></li>
                        <li>Inscription</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 m-auto my-3">
            @include('admin.components.validationMessage')
            {{-- <form action="{{ route('register') }}" method="post" class="woocommerce-form-login mb-30">
                @csrf
                <div class="form-group">
                    <label>Nom et prenoms <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="Ex: jhonn " required>
                </div>
                <div class="form-group">
                    <label>Telephone <span class="text-danger">*</span></label>
                    <input type="number" name="phone" class="form-control" placeholder="Ex: 00000000" required>
                </div>
                <div class="form-group">
                    <label>Email <span class="text-danger">(Facultatif mais recommandé pour la reinitialisation du mot de
                            passe)</span></label>
                    <input type="email" name="email" class="form-control" placeholder="Ex: alex@gmail.com">
                </div>
                <div class="form-group">
                    <label>Mot de passe <span class="text-danger">(8 caractères minimum)*</span></label>
                    <input type="password" class="form-control" name="password" placeholder="de********" required>
                </div>

                <input type="text" name="role" value="client" hidden>


                <div class="form-group">
                    <label>Votre date d'anniversaire <strong>(Peut-être qu'une surprise vous atteindra le
                            Jour-J)</strong></label>
                    <div class="row">

                        <div class="col-6">
                            <select name="jour" class="form-control">
                                <option disabled selected>Jour</option>
                                @for ($i = 1; $i < 32; $i++)
                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">
                                        {{ str_pad($i, 2, '0', STR_PAD_LEFT) }} </option>
                                @endfor
                            </select>

                        </div>
                        <div class="col-6">

                            <select name="mois" id="" class="form-control">
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
                                    <option value="{{ str_pad(++$key, 2, '0', STR_PAD_LEFT) }}">{{ $item }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <input type="text" name="url_previous" value="{{ url()->previous() }}" hidden>

                <!-- Honeypot : champ invisible pour bloquer les bots -->
                <div style="display:none !important" aria-hidden="true">
                    <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                </div>

                <div class="form-group">
                    <button type="submit" class="th-btn rounded-2 w-100">Valider

                        <div class="spinner-grow text-white" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>
                    <p class="fs-xs mt-2 mb-0 text-center"><a class="text-reset" href="{{ route('login-form') }}">Vous avez
                            un compte?
                            <b class="text-danger">Connectez vous </b>
                        </a></p>
                </div>
            </form> --}}



            <!-- FORMULAIRE COMPLET -->

            <form action="{{ route('register') }}" method="post" class="woocommerce-form-login mb-30">

                @csrf

                {{-- Temps chargement formulaire --}}
                <input type="hidden" name="form_time" value="{{ time() }}">

                {{-- Honeypot invisible --}}
                <div style="display:none !important" aria-hidden="true">
                    <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                </div>

                {{-- Nom --}}
                <div class="form-group">
                    <label>
                        Nom et prénoms
                        <span class="text-danger">*</span>
                    </label>

                    <input type="text" name="name" class="form-control" placeholder="Ex: Alex Kouamelan"
                        minlength="3" maxlength="100" required>
                </div>

                {{-- Téléphone --}}
                <div class="form-group">
                    <label>
                        Téléphone
                        <span class="text-danger">*</span>
                    </label>

                    <input type="tel" name="phone" class="form-control" placeholder="0707070707" required>
                </div>

                {{-- Email --}}
                <div class="form-group">
                    <label>
                        Email
                        <span class="text-danger">
                            (Facultatif mais recommandé)
                        </span>
                    </label>

                    <input type="email" name="email" class="form-control" placeholder="alex@gmail.com">
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label>
                        Mot de passe
                        <span class="text-danger">
                            (8 caractères minimum)
                        </span>
                    </label>

                    <input type="password" name="password" class="form-control" placeholder="********" minlength="8"
                        required>
                </div>

                {{-- Role --}}
                <input type="hidden" name="role" value="client">

                {{-- Date anniversaire --}}
                <div class="form-group">

                    <label>
                        Votre date d'anniversaire
                        <strong>
                            (Une surprise pourrait arriver 🎁)
                        </strong>
                    </label>

                    <div class="row">

                        {{-- Jour --}}
                        <div class="col-6">

                            <select name="jour" class="form-control">

                                <option value="" disabled selected>
                                    Jour
                                </option>

                                @for ($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}">
                                        {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                    </option>
                                @endfor

                            </select>

                        </div>

                        {{-- Mois --}}
                        <div class="col-6">

                            <select name="mois" class="form-control">

                                <option value="" disabled selected>
                                    Mois
                                </option>

                                @php
                                    $months = [
                                        'Janvier',
                                        'Février',
                                        'Mars',
                                        'Avril',
                                        'Mai',
                                        'Juin',
                                        'Juillet',
                                        'Août',
                                        'Septembre',
                                        'Octobre',
                                        'Novembre',
                                        'Décembre',
                                    ];
                                @endphp

                                @foreach ($months as $key => $month)
                                    <option value="{{ $key + 1 }}">
                                        {{ $month }}
                                    </option>
                                @endforeach

                            </select>

                        </div>

                    </div>

                </div>

                {{-- URL précédente --}}
                <input type="hidden" name="url_previous" value="{{ url()->previous() }}">

                {{-- Bouton --}}
                <div class="form-group">

                    <button type="submit" class="th-btn rounded-2 w-100">

                        Valider

                        <div class="spinner-grow text-white d-none" role="status">

                            <span class="visually-hidden">
                                Loading...
                            </span>

                        </div>

                    </button>

                    <p class="fs-xs mt-2 mb-0 text-center">

                        <a class="text-reset" href="{{ route('login-form') }}">

                            Vous avez un compte ?

                            <b class="text-danger">
                                Connectez-vous
                            </b>

                        </a>

                    </p>

                </div>

            </form>


        </div>

    </div>

    {{-- <script>
        $(document).ready(function() {
            $('.spinner-grow').hide();

            $('form').submit(function(e) {

                var email = $('#email').val();
                var name = $('#name').val();
                var phone = $('#phone').val();
                var password = $('#password').val()


                if (email && name && phone && password) {
                    $('.th-btn').prop("disabled", true);
                    $(".spinner-grow").show();
                }
            });

        });
    </script> --}}


    <script>
    $(document).ready(function () {

        $('form').submit(function () {

            $('.th-btn').prop('disabled', true);

            $('.spinner-grow').removeClass('d-none');
        });

    });
</script>
@endsection
