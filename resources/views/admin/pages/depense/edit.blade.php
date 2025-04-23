@extends('admin.layouts.app')
@section('title', 'depense')
@section('sub-title', 'Modification')

@section('content')
    <style>
        img {
            max-width: 180px;
        }

        input[type=file] {
            padding: 10px;
            background: #eaeaea;
        }
    </style>

    <section class="section">
        @php
            $msg_validation = 'Champs obligatoire';
        @endphp
        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-8 m-auto">
                    @include('admin.components.validationMessage')
                    <div class="card">
                        <form class="row g-3 needs-validation m-3" method="post"
                            action="{{ route('depense.update', $data_depense['id']) }}" novalidate>
                            @csrf

                            <div class="row">
                                <div class="col-md-8">
                                    <label for="validationCustom01" class="form-label">Categorie</label>
                                    <select name="categorie" class="form-control categorie-select" required>
                                        <option disabled selected value="">Selectionner</option>
                                        @foreach ($categorie_depense as $data)
                                            <!-- Si la catégorie a des libelleDepenses, rendre l'option non cliquable -->
                                            <option style="font-weight: bold;"
                                                {{ $data['id'] == $data_depense['categorie_depense_id'] ? 'selected' : '' }} value="{{ $data['id'] }}"
                                                class="categorie">
                                                {{ strtoupper($data['libelle']) }}
                                            </option>

                                            <!-- Boucle pour les libelleDepenses de cette catégorie -->
                                            @foreach ($data->libelleDepenses as $data_libelle)
                                                <option
                                                    {{ $data_libelle['id'] == $data_depense['libelle_depense_id'] ? 'selected' : '' }}
                                                    value="{{ $data_libelle['id'] }}" class="libelle-depense">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;{{ $data_libelle['libelle'] }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label for="validationCustom01" class="form-label">Montant</label>
                                    <input type="number" name="montant" value="{{ $data_depense['montant'] }}"
                                        class="form-control" id="validationCustom01" required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label class="form-label" for="meta-title-input">Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" id="currentDate" value="{{ $data_depense->date_depense }}"
                                        name="date_depense" class="form-control" required>
                                </div>

                                <div class="col-md-12">
                                    <label for="validationCustom01" class="form-label">Objet</label>
                                    <textarea class="form-control" name="description" id="" cols="30" rows="10">
                                        {{ $data_depense['description'] }}
                                    </textarea>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 m-3 ">Valider</button>
                        </form>
                    </div>

                </div>

            </div>

        </div>


    </section>
@endsection
