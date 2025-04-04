@extends('admin.layouts.app')
@section('title', 'categorie')
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
                            action="{{ route('libelle-depense.update', $libelle_depense['id']) }}" novalidate>
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="validationCustom01" class="form-label">Categorie</label>
                                    <select name="categorie_depense_id" class="form-control" required>
                                        <option disabled selected value="">Selectionner</option>
                                        @foreach ($categorie_depense as $data)
                                            <option value="{{ $data['id'] }}"
                                                {{ $data['id'] == $libelle_depense['categorie_depense_id'] ? 'selected' : '' }}>
                                                {{ $data['libelle'] }} </option>
                                        @endforeach
                                    </select>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="validationCustom01" class="form-label">Libelle</label>
                                    <input type="text" name="libelle" value="{{ $libelle_depense['libelle'] }}"
                                        class="form-control" id="validationCustom01" required>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="validationCustom01" class="form-label">Description</label>
                                    <textarea class="form-control" name="description" id="" cols="30" rows="10">
                                    {{ $libelle_depense['description'] }}
                                 </textarea>
                                    <div class="valid-feedback">
                                        Looks good!
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3 w-100 ">Valider</button>
                        </form>
                    </div>




                </div>

            </div>

        </div>

        </div>
        </div>
    </section>
@endsection
