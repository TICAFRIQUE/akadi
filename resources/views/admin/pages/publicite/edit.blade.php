@extends('admin.layouts.app')

@section('title', 'publicite')
@section('sub-title', 'Modifier une publicite')

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
                <div class="col-12 col-md-12 col-lg-12 m-auto">
                    <a class="btn btn-primary fas fa-arrow-left mb-2" href="{{ route('publicite.index') }}"> Retour à la liste
                    </a>

                    @include('admin.components.validationMessage')
                    <div class="card">
                        <form action="{{ route('publicite.update', $publicite['id']) }}" class="needs-validation"
                            novalidate="" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="form-group row">
                                    <p class="fw-bold fs-2 col-12" id="MsgError"></p>

                                    <div class="col-sm-3">
                                        <label class="col-sm-12 col-form-label">Type de publicite</label>
                                        <select name="type" class="form-control selectric typeMedia " required>
                                            <option disabled selected value>Choisir un type</option>
                                            @php
                                                $type = [
                                                    'slider',
                                                    // // 'popup',
                                                    // 'arriere-plan',
                                                    // 'banniere',
                                                    // 'pack',
                                                    'top-promo',
                                                    'annonce',
                                                ];
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

                                    </div>

                                    <div class="col-sm-3  dateStartDiv">
                                        <label class="col-sm-12 col-form-label">Date Debut de la publicité</label>

                                        <input type="text" id="date_start" name="date_debut_pub"
                                            value="{{ $publicite['date_debut_pub'] }}" class="form-control datetimepicker">
                                    </div>

                                    <div class="col-sm-3 dateEndDiv">
                                        <label class="col-sm-12 col-form-label">Date Fin de la publicité</label>

                                        <input type="text" id="date_end" value="{{ $publicite['date_fin_pub'] }}"
                                            name="date_fin_pub" class="form-control datetimepicker">
                                    </div>
                                    <div class="col-sm-3 remiseDiv">
                                        <label class="col-sm-12 col-form-label">Reduction %</label>
                                        <input type="number" name="discount" value="{{ $publicite['discount'] }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="form-group row linkDiv">
                                    <div class="col-sm-8">
                                        <label class="col-sm-3 col-form-label">Lien</label>
                                        <input type="url" value="{{ $publicite['url'] }}" name="url"
                                            class="form-control">
                                        <div class="invalid-feedback">
                                            entrer le lien de redirection
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <label class="col-sm-12 col-form-label">Nom du bouton</label>
                                        <input type="text" value="{{ $publicite['button_name'] }}" name="button_name"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Texte</label>
                                    <div class="col-sm-9">
                                        <textarea name="texte" class="form-control summernote">
                                           {{ $publicite['texte'] }}
                                        </textarea>
                                    </div>
                                </div>

                                <div class="form-group row imageDiv">
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
                                <button type="submit" class="btn btn-primary w-100">Modifier</button>
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

        $(document).ready(function() {



            // si par defaut le value type

            var type = $('.typeMedia').val();

           
                if (type_media == 'annonce') {
                    $('.remiseDiv').hide();
                    $('.linkDiv').hide();
                    $('.imageDiv').hide();
                } else {
                    $('.remiseDiv').show();
                    $('.linkDiv').show();
                    $('.imageDiv').show();
                }



                if (type_media == 'slider') {
                    $('.remiseDiv').hide();

                }
            



            $('.typeMedia').on('change', function() {
                var type_media = $(this).val();

                console.log(type_media);


                if (type_media == 'annonce') {
                    $('.remiseDiv').hide();
                    $('.linkDiv').hide();
                    $('.imageDiv').hide();
                } else {
                    $('.remiseDiv').show();
                    $('.linkDiv').show();
                    $('.imageDiv').show();
                }

                if (type_media == 'slider') {
                    $('.remiseDiv').hide();

                }
            })
            //date discount

            $(".datetimepicker").each(function() {
                $(this).datetimepicker({
                    showOtherMonths: true,
                    selectOtherMonths: true,
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: true,
                    dateFormat: 'yy-mm-dd',
                    minDate: 0
                });
            });



            $('#date_start').change(function(e) {
                var date_start = $(this).val();
                var date_end = $('#date_end').val();

                if (date_start > date_end) {
                    $('#MsgError').html(
                        'La date debut de la promo ne doit pas etre superieur à la date de fin').css({
                        'color': 'white',
                        'background-color': 'red',
                        'font-size': '16px'
                    });
                    $('.btn-submit').prop('disabled', true)
                } else {
                    $('#MsgError').html(' ')
                    $('.btn-submit').prop('disabled', false)
                }
            });


            $('#date_end').change(function(e) {
                var date_end = $(this).val();
                var date_start = $('#date_start').val();

                if (date_end < date_start) {
                    $('#MsgError').html(
                        'La date fin de la promo ne doit pas etre inferieur à la date de debut').css({
                        'color': 'white',
                        'background-color': 'red',
                        'font-size': '16px'
                    });
                    $('.btn-submit').prop('disabled', true)
                } else {
                    $('#MsgError').html(' ')
                    $('.btn-submit').prop('disabled', false)
                }
            });



        });
    </script>
@endsection
