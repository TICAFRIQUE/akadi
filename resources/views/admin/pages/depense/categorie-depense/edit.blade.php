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
                            action="{{ route('categorie-depense.update', $categorie_depense['id']) }}" novalidate>
                            @csrf
                            <div class="col-md-6">
                                <label for="validationCustom01" class="form-label">Nom de la categorie</label>
                                <input type="text" name="libelle" value="{{ $categorie_depense['libelle'] }}"
                                    class="form-control" id="validationCustom01" required>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="validationCustom01" class="form-label">Statut</label>
                                <select name="statut" class="form-control">
                                    <option value="active" {{ $categorie_depense['statut'] == 'active' ? 'selected' : '' }}>
                                        Activé
                                    </option>
                                    <option value="desactive"
                                        {{ $categorie_depense['statut'] == 'desactive' ? 'selected' : '' }}>
                                        Desactivé
                                    </option>
                                </select>
                                <div class="valid-feedback">
                                    Looks good!
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4 w-100 ">Modifier</button>
                        </form>

                    </div>

                </div>

            </div>

        </div>
        </div>
    </section>



@endsection
