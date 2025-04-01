@extends('admin.layouts.app')
@section('title', 'coupon')
@section('sub-title', 'Ajouter un coupon de reduction')


@push('css')
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom.css') }}">
@endpush

{{-- <script>
    //upload principal image
    function readURL(input) {
        let noimage =
            "https://ami-sni.com/wp-content/themes/consultix/images/no-image-found-360x250.png";
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#img-preview')
                    .attr('src', e.target.result);
            };


            reader.readAsDataURL(input.files[0]);
        } else {
            $("#img-preview").attr("src", noimage);
        }
    }
</script> --}}

@section('content')
    <style>
        input[type="file"] {
            display: block;
        }

        .imageThumb {
            /* position:absolute; */
            max-height: 75px;
            border: 2px solid;
            padding: 1px;
            cursor: pointer;
        }

        .pip {
            display: inline-block;
            margin: 10px 10px 0 0;
            color: rgb(255, 255, 255)
        }

        .remove {
            top: -85px;
            width: 30px;
            position: relative;
            display: block;
            background: #ffff;
            border-radius: 20px;
            border: 1px solid rgb(255, 255, 255);
            color: rgb(59, 59, 61);
            text-align: center;
            cursor: pointer;
            box-shadow: 3px 4px rgb(188, 188, 188);
        }

        .remove:hover {
            background: white;
            color: black;
        }
    </style>

@section('css')
    <link rel="stylesheet" href="{{ asset('admin/assets/bundles/jquery-selectric/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/bundles/select2/dist/css/select2.min.css') }}">
@endsection
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Coupon de reduction</h4>
                    </div>
                    @include('admin.components.validationMessage')

                    {{-- <form class="needs-validation" novalidate="" action="{{ route('coupon.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">

                            <!-- ========== Start Remise ========== -->

                            <div class="form-group row">
                                <p class="fw-bold fs-2 col-12" id="MsgError"></p>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Code coupon</label>
                                    <div class="d-flex">
                                        <button class="btn btn-primary" id="codeCouponBtn">Generer</button>

                                        <input type="text" id="codeCoupon" name="code" class="form-control"
                                            required>
                                        <div class="invalid-feedback">
                                            Champs obligatoire
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Montant de la remise</label>

                                    <input type="number" id="discount_price" name="montant_remise"
                                        class="form-control">
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Pourcentage(%) </label>

                                    <input type="number" id="discount" name="pourcentage_coupon" class="form-control"
                                        required>
                                    <div class="invalid-feedback">
                                        Champs obligatoire
                                    </div>
                                </div>


                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Date Debut</label>

                                    <input type="text" id="date_start" name="date_debut_coupon"
                                        class="form-control datetimepicker" required>
                                    <div class="invalid-feedback">
                                        Champs obligatoire
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Date Fin</label>

                                    <input type="text" id="date_end" name="date_fin_coupon"
                                        class="form-control datetimepicker" required>

                                    <div class="invalid-feedback">
                                        Champs obligatoire
                                    </div>
                                </div>

                            </div>
                            <!-- ========== End Remise ========== -->





                            <hr>



                            <!-- ========== Start customers and products ========== -->
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label class="col-sm-12 col-form-label">Clients</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="customers"
                                            id="customerCheckboxAll" value="customerAllChecked">
                                        <label class="form-check-label" for="customerCheckboxAll">Tous
                                            sélectionner</label>
                                    </div>

                                    <select name="customers[]" id="customer" class="form-control select2 " multiple
                                        required>
                                        @foreach ($customer as $item)
                                            <option value="{{ $item['id'] }}"> {{ $item['name'] }} </option>
                                        @endforeach

                                    </select>

                                </div>

                                <div class="col-md-6">
                                    <label class="col-sm-12 col-form-label">Produits</label>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="products"
                                            id="productCheckboxAll" value="productAllChecked">
                                        <label class="form-check-label" for="productCheckboxAll">Tous
                                            sélectionner</label>
                                    </div>

                                    <select name="products[]" id="product" class="form-control select2 " multiple
                                        required>
                                        @foreach ($product as $item)
                                            <option value="{{ $item['id'] }}"> {{ $item['title'] }} </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <!-- ========== End customers and products ========== -->


                            <hr>
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-7 text-lg-right">
                                    <button type="submit" class="btn btn-primary btn-submit w-100">Enregistrer</button>
                                </div>
                            </div>
                        </div>

                    </form> --}}
                </div>





                <div class="card">
                    <div class="card-header">
                        <h4> <code>BON DE COMMANDE</code></h4>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-pills" id="myTab3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active tabCoupon" id="home-tab3" data-tag="couponGroupe"
                                    data-toggle="tab" href="#couponGroupe" role="tab" aria-controls="home"
                                    aria-selected="true">Groupe</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link tabCoupon" id="profile-tab3" data-tag="couponUnique"
                                    data-toggle="tab" href="#couponUnique" role="tab" aria-controls="profile"
                                    aria-selected="false">Unique</a>
                            </li>

                        </ul>
                        <div class="tab-content" id="myTabContent2">

                            <!-- ========== Start coupon groupe ========== -->
                            @include('admin.pages.coupon.type.coupon_groupe')
                            <!-- ========== End coupon groupe ========== -->




                            {{-- 
                            <div class="tab-pane fade" id="couponUnique" role="tabpanel" aria-labelledby="profile-tab3">
                                <!-- ========== Start coupon groupe ========== -->
                                @include('admin.pages.coupon.type.coupon_unique')
                                <!-- ========== End coupon groupe ========== -->
                            </div> --}}



                            {{-- <div class="tab-pane fade" id="contact3" role="tabpanel"
                                aria-labelledby="contact-tab3">
                                Vestibulum imperdiet odio sed neque ultricies, ut dapibus mi maximus. Proin ligula
                                massa,
                                gravida in lacinia efficitur, hendrerit eget mauris. Pellentesque fermentum, sem
                                interdum
                                molestie finibus, nulla diam varius leo, nec varius lectus elit id dolor.
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>




@section('script')
    {{-- <script src="{{ asset('site/assets/js/jquery.min.js') }}"></script> --}}

    <script src="{{ asset('admin/assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/assets/bundles/jquery-selectric/jquery.selectric.min.js') }}"></script>
@endsection
<script type="text/javascript">
    $(document).ready(function() {

        // par defaut disabled valeur de remise si type de remise = pourcentage ou montant

        //recuperer le type de remise
        var typeRemise = $('#typeRemise').val();
        if (typeRemise == 'pourcentage' || typeRemise == 'montant') {
            $('#valeurRemise').prop('disabled', false);
        } else {
            $('#valeurRemise').prop('disabled', true);
        }



        // recuperer le type de remise
        $('#typeRemise').change(function(e) {
            // si type de remise = pourcentage
            if ($(this).val() == 'pourcentage') {
                // activer la valeur de remise
                $('#valeurRemise').prop('disabled', false);
                // empecher la valeur de remise de depasser 100%
                $('#valeurRemise').keyup(function(e) {
                    if ($(this).val() > 100) {
                        $(this).val(100)
                    }
                })
            } else if ($(this).val() == 'montant') {
                // desactiver la valeur de remise
                $('#valeurRemise').prop('disabled', false);
            } else {
                // desactiver la valeur de remise
                $('#valeurRemise').prop('disabled', true);

                //mettre la valeur de remise a null
                $('#valeurRemise').val(null);
            }
            var type = $(this).val();
        })




        //Gestion des dates

        $(".datetimepicker").each(function() {
            $(this).datetimepicker({
                showOtherMonths: true,
                selectOtherMonths: true,
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                dateFormat: 'dd-mm-yy', // Format pour la date
                timeFormat: 'HH:mm', // Format pour l'heure
                minDate: 0

            });
        });





        // $('#date_start').change(function(e) {
        //     var date_start = $(this).val();
        //     var date_end = $('#date_end').val();

        //     if (date_start > date_end) {
        //         $('#MsgError').html(
        //             'La date debut de la promo ne doit pas etre superieur à la date de fin').css({
        //             'color': 'white',
        //             'background-color': 'red',
        //             'font-size': '16px'
        //         });
        //         $('.btn-submit').prop('disabled', true)
        //     } else {
        //         $('#MsgError').html(' ')
        //         $('.btn-submit').prop('disabled', false)
        //     }
        // });


        // $('#date_end').change(function(e) {
        //     var date_end = $(this).val();
        //     var date_start = $('#date_start').val();

        //     if (date_end < date_start) {
        //         $('#MsgError').html(
        //             'La date fin de la promo ne doit pas etre inferieur à la date de debut').css({
        //             'color': 'white',
        //             'background-color': 'red',
        //             'font-size': '16px'
        //         });
        //         $('.btn-submit').prop('disabled', true)
        //     } else {
        //         $('#MsgError').html(' ')
        //         $('.btn-submit').prop('disabled', false)
        //     }
        // });

        function validateDates() {
            var date_start = $('#date_start').val();
            var date_end = $('#date_end').val();

            if (date_start && date_end) { // Vérifie que les deux dates sont définies
                if (date_start > date_end) {
                    $('#MsgError').html(
                        'La date de début de la promo ne doit pas être supérieure à la date de fin'
                    ).css({
                        'color': 'white',
                        'background-color': 'red',
                        'font-size': '16px'
                    });
                    $('.btn-submit').prop('disabled', true);
                } else {
                    $('#MsgError').html('');
                    $('.btn-submit').prop('disabled', false);
                }
            }
        }

        // appeler la fonction validateDates() lorsque les dates sont chargées
        validateDates
            (); // on appelle la fonction validateDates() pour vérifier les dates au chargement de la page


        // appeler la fonction validateDates() lorsque les dates changent
        $('#date_start, #date_end').on('change',
            validateDates); // on utilise la fonction change() pour détecter les changements




        //Generate code coupon
        // $('#codeCouponBtn').click(function(e) {
        //     e.preventDefault();

        //     // on va recuperer le nom du coupon et le concatenner avec le code genéré
        //     var nom = $('#nameCoupon').val() || 'AK'; // Utilise 'AK' si le champ est vide
        //     var code = (nom + '-').toUpperCase(); // Mets en majuscule et ajoute le tiret

        //     // abcdefghijklmnopqrstuvwxyz
        //     var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

        //     for (var i = 0; i < 8; i++)
        //         code += possible.charAt(Math.floor(Math.random() * possible.length));

        //     $('#codeCoupon').val(code)
        //     $('#codeCoupon').prop('readonly', true)


        // });


        // Fonction pour générer le code en temps réel
        function generateCode() {
            var nom = $('#nameCoupon').val() || 'AK'; // Utilise 'AK' si le champ est vide
            var code = (nom + '-').toUpperCase(); // Mets en majuscule et ajoute le tiret

            // Génère une partie aléatoire du code
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            for (var i = 0; i < 8; i++) {
                code += possible.charAt(Math.floor(Math.random() * possible.length));
            }

            // Met à jour le champ du code
            $('#codeCoupon').val(code);
        }

        // Exécute la fonction dès que le champ #nameCoupon change
        $('#nameCoupon').on('input', function() {
            generateCode(); // Appel de la fonction de génération de code
        });

        // Générer le code initialement lors du clic sur le bouton
        $('#codeCouponBtn').click(function(e) {
            e.preventDefault();
            generateCode(); // Appel de la fonction de génération de code
            $('#codeCoupon').prop('readonly', true); // Rend le champ readonly
        });


        // afficher la liste des clients si data-tag est coupon unique

        $('.clientDiv').hide(); // Cacher la liste des clients par défaut
        $('.clientDiv').hide(); // Cacher la liste des clients par défaut

        $('a.tabCoupon').on('shown.bs.tab', function(e) {
            let dataTag = $(this).data('tag');
            console.log(dataTag); // Vérifier la valeur dans la console

            if (dataTag === 'couponUnique') {
                $('.clientDiv').show();
                // le rendre obligatoire
                $('#customer').prop('required', true);

                // afficher le message d'erreur
                $('.customerMsg').removeClass('d-none');

                // cacher le message d'erreur si un client est choisi sinon le rendre obligatoire et afficher le message
                // $('#customer').on('change', function() {
                //     if ($(this).val() === '') {
                //         $('.customerMsg').removeClass('d-none');
                //         $('#customer').prop('required', true);
                //     } else {
                //         $('.customerMsg').addClass('d-none');
                //         $('#customer').prop('required', false);
                //     }
                // })
            } else {
                $('.clientDiv').hide();
                // vider la liste des clients
                $('#customer').val('').trigger('change');// Vider le champ et déclencher l'événement change
                $('#customer').prop('required', false);
            }
        });



        //customer checked
        $('#customerCheckboxAll').change(function(e) {
            e.preventDefault();

            if ($("#customerCheckboxAll").is(':checked')) {
                // $('#customer').prop('disabled', true)
                // $('#customer').val('').trigger('change')

                $("#customer > option").prop("selected", true);
                $("#customer").trigger("change");

            } else {
                // $('#customer').prop('disabled', false)
                $("#customer > option").prop("selected", false);
                $("#customer").trigger("change");
            }
        });


        //product checked
        $('#productCheckboxAll').change(function(e) {
            e.preventDefault();

            if ($("#productCheckboxAll").is(':checked')) {
                // $('#product').prop('disabled', true)
                // $('#product').val('').trigger('change')
                $("#product > option").prop("selected", true);
                $("#product").trigger("change");

            } else {
                $("#product > option").prop("selected", false);
                $("#product").trigger("change");
            }
        });




    });
</script>
@endsection
