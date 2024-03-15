@extends('admin.layouts.app')
@section('title', 'setting')
@section('sub-title', 'setting')

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
                <div class="col-12 col-md-10 col-lg-10 m-auto">
                    @include('admin.components.validationMessage')
                    <div class="card">
                        <form action="{{ route('setting.store') }}" class="needs-validation" novalidate="" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="col-sm-12 col-form-label">Icone</label>
                                        <input type="text" value="{{ $setting['icone'] }}" name="icone"
                                            class="form-control" placeholder="fa fa-mobile" readonly>

                                    </div>

                                    <div class="col-sm-8">
                                        <label class="col-sm-12 col-form-label">Localisation</label>
                                        <input type="text" value="{{ $setting['localisation'] }}" name="localisation"
                                            class="form-control" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="col-sm-12 col-form-label">Icone</label>
                                        <input type="text" name="icone" value="{{ $setting['phone'] }}"
                                            class="form-control" placeholder="fa fa-mobile" readonly>
                                        <div class="invalid-feedback">
                                            Champ obligatoire
                                        </div>
                                    </div>

                                    <div class="col-sm-8">
                                        <label class="col-sm-12 col-form-label">Telephone</label>
                                        <input type="number" name="phone" class="form-control" readonly>
                                        <div class="invalid-feedback">
                                            Champ obligatoire
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-4">
                                        <label class="col-sm-12 col-form-label">Icone</label>
                                        <input type="text" name="icone" class="form-control" placeholder="fa fa-mobile"
                                            readonly>
                                        <div class="invalid-feedback">
                                            Champ obligatoire
                                        </div>
                                    </div>

                                    <div class="col-sm-8">
                                        <label class="col-sm-12 col-form-label">Email</label>
                                        <input type="email" name="email" value="{{ $setting['email'] }}"
                                            class="form-control" readonly>
                                        <div class="invalid-feedback">
                                            Champ obligatoire
                                        </div>

                                    </div>
                                </div>




                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <label class="col-sm-12 col-form-label">A propos de Akadi</label>
                                        <textarea name="about" class="form-control" readonly>
                                           {{ $setting['about'] }}
                                        </textarea>
                                        <div class="invalid-feedback">
                                            Champ obligatoire
                                        </div>

                                    </div>
                                </div>



                                <div class="form-group row">

                                    <div class="col-sm-12">
                                        <label class="col-sm-12 col-form-label">Logo</label>
                                        <div>
                                            <img src="{{ $setting->getFirstMediaUrl('logo') }}" width="50px" alt="">
                                        </div>
                                        <img id="_blah" src="" alt="" />
                                        <input type="file" name="logo" class="form-control" onchange="_readURL(this);"
                                            readonly>
                                        <div class="invalid-feedback">
                                            Champ obligatoire
                                        </div>

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
