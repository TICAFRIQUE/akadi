@extends('admin.layouts.app')
@section('title', 'coupon')
@section('sub-title', 'Ajouter un coupon de reduction')


@push('css')
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom.css') }}">
@endpush

<script>
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
</script>

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

                    <form class="needs-validation" novalidate="" action="{{ route('coupon.store') }}" method="post"
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
                                            required >
                                        <div class="invalid-feedback">
                                            Champs obligatoire
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Montant de la remise</label>

                                    <input type="number" id="discount_price" name="montant_remise"
                                        class="form-control">
                                </div> --}}

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

                                    <select name="customers[]" id="customer" class="form-control select2 " multiple required>
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

                                    <select name="products[]" id="product" class="form-control select2 " multiple required>
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

                    </form>
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


        //display principal image if category is pack
        $('#principal_image').hide()

        $('select[name="categories"]').change(function(e) {
            var selectVal = $("#category option:selected").attr('tag');
            if (selectVal == "Pack") {
                $('#principal_image').show()
            } else {
                $('#principal_image').hide(100)
            }
        });




        // Gestion upload image
        if (window.File && window.FileList && window.FileReader) {
            $("#files").on("change", function(e) {
                var files = e.target.files,
                    filesLength = files.length;
                for (var i = 0; i < filesLength; i++) {
                    var f = files[i]
                    var fileReader = new FileReader();
                    fileReader.onload = (function(e) {
                        var file = e.target;
                        $("<span class=\"pip\">" +
                            "<img class=\"imageThumb\" src=\"" + e.target.result +
                            "\" title=\"" + file
                            .name + "\"/>" +
                            "<br/><span class=\"remove\">x</span>" +
                            "</span>").insertAfter("#files");
                        $(".remove").click(function() {
                            $(this).parent(".pip").remove();
                        });

                        // Old code here
                        /*$("<img></img>", {
                          class: "imageThumb",
                          src: e.target.result,
                          title: file.name + " | Click to remove"
                        }).insertAfter("#files").click(function(){$(this).remove();});*/

                    });
                    fileReader.readAsDataURL(f);
                }
                // console.log(img);
            });
        } else {
            alert("Your browser doesn't support to File API")
        }



        //load sub cat
        $('.subcat').hide();

        $('select[name="categories"]').on('change', function() {
            var catId = $(this).val();
            if (catId) {
                $.ajax({
                    url: '/admin/produit/loadSubCat/' + catId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="subcategories"]').empty();
                        $('select[name="subcategories"]')
                            .append(
                                '<option value="" selected disabled value>Selectionner une sous categorie</option>'
                            );
                        $.each(data, function(key, value) {
                            $('select[name="subcategories"]').append(
                                '<option value=" ' + value
                                .id + '">' + value.name + '</option>');
                            // console.log(key, value.title);
                        })

                        if (data.length > 0) {
                            $('.subcat').show(200);
                            $('.subCat_required').prop('required', true);

                        } else {
                            $('.subcat').hide(200);
                            $('.subCat_required').prop('required', false);

                        }
                    }

                })
            } else {
                $('select[name="subcategories"]').empty();
                $('.subcat').hide(200);

            }
        });


        // SCRIPT FOR DISCOUNT

        //amount 
        $('#discount_price').keyup(function(e) {
            var discount_price = $('#discount_price').val()
            var product_price = $('#product_price').val()

            if (parseFloat(discount_price) > parseFloat(product_price)) {
                $('#MsgError').html('Le prix de la remise doit etre inferieur au prix normal').css({
                    'color': 'white',
                    'background-color': 'red',
                    'font-size': '16px'
                });
                $('.btn-submit').prop('disabled', true)
            } else {
                // calcul de pourcentage
                var discount = parseFloat(discount_price * 100) / parseFloat(product_price)

                $('#discount').val(Math.round(discount))


                $('#MsgError').html(' ')
                $('.btn-submit').prop('disabled', false)

            }

        });


        //percent
        $('#discount').keyup(function(e) {
            var discount = $('#discount').val()
            var product_price = $('#product_price').val()

            if (discount > 100) {
                $('#MsgError').html('Le pourcentage ne doit pas exceder 100%').css({
                    'color': 'white',
                    'background-color': 'red',
                    'font-size': '16px'
                });
                $('.btn-submit').prop('disabled', true)
            } else {
                var amount_discount = parseFloat(product_price) * (discount / 100)
                $('#discount_price').val(amount_discount)

                $('#MsgError').html(' ')
                $('.btn-submit').prop('disabled', false)
            }
        });


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


        //Generate code coupon
        $('#codeCouponBtn').click(function(e) {
            e.preventDefault();
            var code = "AK-";
            // abcdefghijklmnopqrstuvwxyz
            var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

            for (var i = 0; i < 8; i++)
                code += possible.charAt(Math.floor(Math.random() * possible.length));

            $('#codeCoupon').val(code)
            $('#codeCoupon').prop('readonly' , true)


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
