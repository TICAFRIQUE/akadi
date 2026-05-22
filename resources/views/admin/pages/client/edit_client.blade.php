@extends('admin.layouts.app')
@section('title', 'clients')
@section('sub-title', 'Modifier un client')

@section('css')
    <link rel="stylesheet" href="{{ asset('admin/assets/bundles/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
    <section class="section">
        <div class="container mt-1">
            <div class="row">
                <a class="btn btn-primary fas fa-arrow-left mb-2" href="{{ route('client.list') }}">
                    Retour à la liste des clients
                </a>
                <div
                    class="col-12 col-sm-10 offset-sm-1 col-md-10 offset-md-2 col-lg-10 offset-lg-2 col-xl-10 offset-xl-2 m-auto">

                    <div class="card card-primary">
                        @include('admin.components.validationMessage')
                        <div class="card-header">
                            <h4>Modification client — {{ $user['name'] }}</h4>
                        </div>
                        <div class="card-body">
                            {{-- <form class="needs-validation" novalidate="" method="POST"
                                action="{{ route('client.update', $user['id']) }}">
                                @csrf
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="name">Nom & prénoms</label>
                                        <input id="name" value="{{ $user['name'] }}" type="text"
                                            class="form-control" name="name" autofocus required>
                                        <div class="invalid-feedback">Champs obligatoire</div>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="phone">Téléphone</label>
                                        <input id="phone" value="{{ $user['phone'] }}" type="number"
                                            class="form-control" name="phone" required>
                                        <div class="invalid-feedback">Champs obligatoire</div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="email">Email</label>
                                        <input id="email" value="{{ $user['email'] }}" type="email"
                                            class="form-control" name="email">
                                    </div>

                                </div>



                                <div class="form-group">
                                    <label>Date d'anniversaire <strong>(Peut-être qu'une surprise vous atteindra le
                                            Jour-J)</strong></label>
                                    <div class="row">
                                        @php
                                            $Y = date('Y');
                                            $nex_date = $user['date_anniversaire'] . '-' . $Y;
                                            $date = \Carbon\Carbon::parse($nex_date)->locale('fr_FR');
                                            $day = $date->day;
                                            $date_month = $date->monthName;
                                        @endphp
                                        <div class="col-6">
                                            <select name="jour" class="form-control">
                                                <option disabled selected>Jour</option>
                                                @for ($i = 1; $i < 32; $i++)
                                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                        {{ $day == $i ? 'selected' : '' }}>
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
                                                    <option value="{{ str_pad(++$key, 2, '0', STR_PAD_LEFT) }}"
                                                        {{ $date_month == $item ? 'selected' : '' }}>
                                                        {{ $item }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="motif">Motif de contact</label>
                                    <select id="motif" name="motif" class="form-control" required>
                                        <option disabled value="">-- Choisir un motif --</option>
                                        @foreach (\App\Models\User::MOTIFS as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('motif', $user->motif) == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Champs obligatoire</div>
                                </div>

                                <div class="form-group" id="motif_autre_wrapper" style="display: none;">
                                    <label for="motif_autre">Préciser le motif</label>
                                    <input id="motif_autre" type="text" class="form-control" name="motif_autre"
                                        placeholder="Décrivez le motif..."
                                        value="{{ old('motif_autre', $user->motif_autre) }}">
                                    <div class="invalid-feedback">Veuillez préciser le motif</div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-8">
                                        <label for="password" class="d-block">Mot de passe
                                            (<small class="text-danger">Laisser vide pour ne pas le modifier</small>)
                                        </label>
                                        <input id="password" type="password" class="form-control" name="password">
                                    </div>
                                    <div class="form-group col-4 my-auto">
                                        @include('admin.components.hideshowpwd')
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                                        Modifier
                                    </button>
                                </div>
                            </form> --}}

                            <form class="needs-validation" novalidate method="POST"
                                action="{{ route('client.update', $user['id']) }}">

                                @csrf

                                <div class="row">

                                    {{-- Nom --}}
                                    <div class="form-group col-6">

                                        <label for="name">
                                            Nom & prénoms
                                        </label>

                                        <input id="name" type="text"
                                            class="form-control @error('name') is-invalid @enderror" name="name"
                                            value="{{ old('name', $user['name']) }}" minlength="3" maxlength="100"
                                            required>

                                        @error('name')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @else
                                            <div class="invalid-feedback">
                                                Nom invalide
                                            </div>
                                        @enderror

                                    </div>

                                    {{-- Téléphone --}}
                                    <div class="form-group col-6">

                                        <label for="phone">
                                            Téléphone
                                        </label>

                                        <input id="phone" type="tel"
                                            class="form-control @error('phone') is-invalid @enderror" name="phone"
                                            value="{{ old('phone', $user['phone']) }}" minlength="8" maxlength="15"
                                            required>

                                        @error('phone')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @else
                                            <div class="invalid-feedback">
                                                Téléphone invalide
                                            </div>
                                        @enderror

                                    </div>

                                </div>

                                {{-- Email --}}
                                <div class="row">

                                    <div class="form-group col-12">

                                        <label for="email">
                                            Email
                                        </label>

                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email', $user['email']) }}">

                                        @error('email')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                </div>

                                {{-- Date anniversaire --}}
                                <div class="form-group">

                                    <label>
                                        Date d'anniversaire
                                        <strong>
                                            (Peut-être qu'une surprise vous atteindra le Jour-J 🎁)
                                        </strong>
                                    </label>

                                    @php

                                        $day = null;
                                        $monthNumber = null;

                                        if (
                                            !empty($user['date_anniversaire']) &&
                                            str_contains($user['date_anniversaire'], '-')
                                        ) {
                                            [$day, $monthNumber] = explode('-', $user['date_anniversaire']);
                                        }

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

                                    <div class="row">

                                        {{-- Jour --}}
                                        <div class="col-6">

                                            <select name="jour" class="form-control @error('jour') is-invalid @enderror">

                                                <option value="">
                                                    Jour
                                                </option>

                                                @for ($i = 1; $i <= 31; $i++)
                                                    @php
                                                        $value = str_pad($i, 2, '0', STR_PAD_LEFT);
                                                    @endphp

                                                    <option value="{{ $value }}"
                                                        {{ old('jour', $day) == $value ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endfor

                                            </select>

                                            @error('jour')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>

                                        {{-- Mois --}}
                                        <div class="col-6">

                                            <select name="mois" class="form-control @error('mois') is-invalid @enderror">

                                                <option value="">
                                                    Mois
                                                </option>

                                                @foreach ($months as $key => $month)
                                                    @php
                                                        $value = str_pad($key + 1, 2, '0', STR_PAD_LEFT);
                                                    @endphp

                                                    <option value="{{ $value }}"
                                                        {{ old('mois', $monthNumber) == $value ? 'selected' : '' }}>
                                                        {{ $month }}
                                                    </option>
                                                @endforeach

                                            </select>

                                            @error('mois')
                                                <div class="invalid-feedback d-block">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                        </div>

                                    </div>

                                </div>

                                {{-- Motif --}}
                                <div class="form-group">

                                    <label for="motif">
                                        Motif de contact
                                    </label>

                                    <select id="motif" name="motif"
                                        class="form-control @error('motif') is-invalid @enderror">

                                        <option value="">
                                            -- Choisir un motif --
                                        </option>

                                        @foreach (\App\Models\User::MOTIFS as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('motif', $user->motif) == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach

                                    </select>

                                    @error('motif')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- Motif autre --}}
                                <div class="form-group" id="motif_autre_wrapper"
                                    style="{{ old('motif', $user->motif) == 'autre' ? '' : 'display:none;' }}">

                                    <label for="motif_autre">
                                        Préciser le motif
                                    </label>

                                    <input id="motif_autre" type="text"
                                        class="form-control @error('motif_autre') is-invalid @enderror" name="motif_autre"
                                        placeholder="Décrivez le motif..."
                                        value="{{ old('motif_autre', $user->motif_autre) }}">

                                    @error('motif_autre')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror

                                </div>

                                {{-- Mot de passe --}}
                                <div class="row">

                                    <div class="form-group col-8">

                                        <label for="password" class="d-block">

                                            Mot de passe

                                            <small class="text-danger">
                                                (Laisser vide pour ne pas modifier)
                                            </small>

                                        </label>

                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            minlength="8">

                                        @error('password')
                                            <div class="invalid-feedback d-block">
                                                {{ $message }}
                                            </div>
                                        @enderror

                                    </div>

                                    <div class="form-group col-4 my-auto">
                                        @include('admin.components.hideshowpwd')
                                    </div>

                                </div>

                                {{-- Bouton --}}
                                <div class="form-group">

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

@section('script')
    <script src="{{ asset('admin/assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
    {{-- <script>
        const motifSelect = document.getElementById('motif');
        const autreWrapper = document.getElementById('motif_autre_wrapper');
        const autreInput = document.getElementById('motif_autre');

        function toggleAutre() {
            const isAutre = motifSelect.value === 'autre';
            autreWrapper.style.display = isAutre ? 'block' : 'none';
            autreInput.required = isAutre;
        }

        // Au chargement (pour old('motif') après erreur de validation)
        toggleAutre();

        motifSelect.addEventListener('change', toggleAutre);
    </script> --}}
    {{-- JS Motif autre --}}
    <script>
        $(document).ready(function() {

            function toggleMotifAutre() {

                let motif = $('#motif').val();

                if (motif === 'autre') {

                    $('#motif_autre_wrapper').show();

                } else {

                    $('#motif_autre_wrapper').hide();

                    $('#motif_autre').val('');
                }
            }

            toggleMotifAutre();

            $('#motif').on('change', function() {
                toggleMotifAutre();
            });

        });
    </script>
@endsection
