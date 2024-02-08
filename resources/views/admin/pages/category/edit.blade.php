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
                        <form action="{{ route('category.update', $category['id']) }}" class="needs-validation" novalidate=""
                            method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Nom de la categorie</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="name" value="{{ $category['name'] }}"
                                            class="form-control" required="">
                                        <div class="invalid-feedback">
                                            {{ $msg_validation }}
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Type</label>
                    <div class="col-sm-9">
                        <select name="type" class="form-control selectric " required>
                            <option disabled selected value>Choisir un type</option>
                            <option value="principale" {{$category['type']=='principale' ? 'selected' : ''}}>Principale</option>
                            <option value="section"  {{$category['type']=='section' ? 'selected' : ''}}>Section</option>
                            <option value="pack"  {{$category['type']=='pack' ? 'selected' : ''}}>Pack</option>
                       
                        </select>
                        <div class="invalid-feedback">
                            Champ obligatoire
                        </div>

                    </div>
                </div> --}}

                                {{-- <div class="form-group row">
                    <label class="col-sm-3 col-form-label">Type d'affichage</label>
                    <div class="col-sm-9">
                        <select name="type_affichage" class="form-control selectric " required>
                            <option disabled selected value>Choisir un type d'affichage</option>
                            <option value="bloc" {{$category['type_affichage']=='bloc' ? 'selected' : ''}}>Bloc</option>
                            <option value="carrousel"  {{$category['type_affichage']=='carrousel' ? 'selected' : ''}}>Carrousel</option>
                        </select>
                        <div class="invalid-feedback">
                            Champ obligatoire
                        </div>

                    </div>
                </div> --}}


                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Image de la categorie
                                    </label>
                                    <div class="col-sm-9">
                                        <img id="_blah" src=" {{ $category->getFirstMediaUrl('category_image') }}"
                                            alt="{{ $category->getFirstMediaUrl('category_image') }}" />
                                        <input type="file" name="cat_img" class="form-control"
                                            onchange="_readURL(this);">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Banniere de la categorie (1920 * 350)</label>
                                    <div class="col-sm-9">
                                        <img id="blah" src=" {{ $category->getFirstMediaUrl('category_banner') }}"
                                            alt="{{ $category->getFirstMediaUrl('category_banner') }}" />
                                        <input type="file" name="cat_banner" class="form-control"
                                            onchange="readURL(this);">

                                    </div>
                                </div>



                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Valider</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </section>


    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah')
                        .attr('src', e.target.result);
                };


                reader.readAsDataURL(input.files[0]);
            }
        }

        // ######################
        function _readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#_blah')
                        .attr('src', e.target.result);
                };


                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
