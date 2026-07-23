@extends('admin.layouts.app')
@section('title', 'clients')
@section('sub-title', 'Ajouter un client')

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
                            <h4>Nouveau client</h4>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate="" method="POST"
                                action="{{ route('client.store') }}">
                                @csrf

                                {{-- ── Honeypot anti-bot (invisible) ── --}}
                                <div style="display:none !important" aria-hidden="true">
                                    <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                                </div>

                                {{-- ── Temps de soumission anti-bot ── --}}
                                <input type="hidden" name="form_time" value="{{ time() }}">

                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="name">Nom & prénoms</label>
                                        <input id="name" type="text" class="form-control" name="name"
                                            value="{{ old('name') }}" autofocus required minlength="3" maxlength="100">
                                        <div class="invalid-feedback">Champs obligatoire (3 caractères minimum)</div>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="phone">Téléphone</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">+225</span>
                                            </div>
                                            <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                name="phone" value="{{ old('phone') }}" placeholder="0701020304"
                                                maxlength="10" minlength="10" required>
                                        </div>
                                        @error('phone')
                                            <div class="text-danger" style="font-size:.85em">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">
                                            <i class="fas fa-info-circle"></i>
                                            Ce numéro sera utilisé comme mot de passe initial.
                                        </small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="email">Email</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
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
                                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}"
                                                        {{ old('jour') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
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
                                                    <option value="{{ str_pad($key + 1, 2, '0', STR_PAD_LEFT) }}"
                                                        {{ old('mois') == str_pad($key + 1, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                        {{ $item }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="motif">Motif du client</label>
                                    <select id="motif" name="motif" class="form-control" required>
                                        <option disabled selected value="">-- Choisir un motif --</option>
                                        @foreach ($motifs as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('motif') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Champs obligatoire</div>
                                </div>

                                {{-- Champ affiché uniquement si "Autres" est sélectionné --}}
                                <div class="form-group" id="motif_autre_wrapper"
                                    style="display: {{ old('motif') == 'autre' ? 'block' : 'none' }};">
                                    <label for="motif_autre">Préciser le motif</label>
                                    <input id="motif_autre" type="text" class="form-control" name="motif_autre"
                                        placeholder="Décrivez le motif..." value="{{ old('motif_autre') }}">
                                    <div class="invalid-feedback">Veuillez préciser le motif</div>
                                </div>

                                <div class="alert alert-info py-2" role="alert">
                                    <i class="fas fa-lock mr-1"></i>
                                    Le <strong>numéro de téléphone</strong> sera automatiquement utilisé comme mot de passe initial.
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

    <script>
        $(document).ready(function() {
            // Afficher/masquer le champ motif_autre
            $('#motif').on('change', function() {
                if ($(this).val() === 'autre') {
                    $('#motif_autre_wrapper').show();
                    $('#motif_autre').attr('required', true);
                } else {
                    $('#motif_autre_wrapper').hide();
                    $('#motif_autre').removeAttr('required').val('');
                }
            });
        });
    </script>
@endsection
