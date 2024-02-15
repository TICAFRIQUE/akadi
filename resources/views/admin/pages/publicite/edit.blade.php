@extends('admin.layouts.app')

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
                    <a class="btn btn-primary fas fa-arrow-left mb-2" href="{{ route('publicite.index') }}"> Retour à la liste
                    </a>

                    @include('admin.components.validationMessage')
                    <div class="card">
                        <form action="{{ route('publicite.update', $publicite['id']) }}" class="needs-validation"
                            novalidate="" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    {{-- <label class="col-sm-3 col-form-label">Type</label>
                                    <div class="col-sm-9">
                                        <select name="type" class="form-control selectric " required>
                                            <option disabled selected value>Choisir un type</option>
                                            @php
                                                $type = ['slider', 'banniere', 'top-header', 'popup', 'small-card', 'background'];
                                            @endphp
                                            @foreach ($type as $item)
                                                <option class="text-capitalize" value="{{ $item }}"
                                                    {{ $publicite['type'] == $item ? 'selected' : '' }}> {{ $item }}
                                                </option>
                                            @endforeach

                                        </select>
                                        <div class="invalid-feedback">
                                            Champ obligatoire
                                        </div>

                                    </div> --}}
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Lien</label>
                                    <div class="col-sm-9">
                                        <input type="url" value="{{ $publicite['url'] }}" name="url"
                                            class="form-control">
                                        <div class="invalid-feedback">
                                            entrer le lien de redirection
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Image
                                    </label>
                                    <div class="col-sm-9">
                                        <img id="img-preview" src=" {{ $publicite->getFirstMediaUrl('publicite_image') }}"
                                            alt="{{ $publicite->getFirstMediaUrl('publicite_image') }}" />
                                        <input type="file" name="image" class="form-control" onchange="readURL(this);">
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer text-right">
                                <button type="submit" class="btn btn-primary">Modifier</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </section>


    <script>
        //Change this to your no-image file
        // ######################
        function readURL(input) {
            let _noimage =
                "https://ami-sni.com/wp-content/themes/consultix/images/no-image-found-360x250.png";
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#img-preview')
                        .attr('src', e.target.result);
                };


                reader.readAsDataURL(input.files[0]);
            } else {
                $("#_img-preview").attr("src", _noimage);
            }
        }
    </script>
@endsection
