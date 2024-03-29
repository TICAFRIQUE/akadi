@extends('admin.layouts.app')
@section('title', 'produit')
@section('sub-title', 'Modifier un produit')
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
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" />

@endsection


<script>
    //upload principal image
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#img-preview')
                    .attr('src', e.target.result);
            };


            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Ajouter un produit</h4>
                    </div>
                    @include('admin.components.validationMessage')

                    <form class="needs-validation" novalidate="" action="{{ route('product.update', $product['id']) }}"
                        method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Title</label>
                                <div class="col-sm-12 col-md-7">
                                    <input name="title" type="text" value="{{ $product['title'] }}"
                                        class="form-control" required>
                                </div>
                                <div class="invalid-feedback">
                                    Champs obligatoire
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Price</label>
                                <div class="col-sm-12 col-md-7">
                                    <input name="price" id="product_price" value="{{ $product['price'] }}"
                                        type="number" class="form-control currency" required>
                                    <div class="invalid-feedback">
                                        Champs obligatoire
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label for=""
                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Category
                                </label>

                                <div class="col-sm-12 col-md-7">
                                    <select name="categories" id="category" class="form-control select2" required>
                                        @foreach ($category_backend as $item)
                                            {{-- @if ($product->categories->containsStrict('id', $item['id'])) @selected(true) @endif --}}
                                            <option value="{{ $item['id'] }}" tag={{ $item['name'] }}
                                                @if ($product->categories->containsStrict('id', $item['id'])) @selected(true) @endif>
                                                {{ $item['name'] }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Champs obligatoire
                                    </div>
                                </div>

                            </div>


                            <div class="form-group row mb-4 subcat">
                                <label for="" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Sous
                                    categorie</label>

                                <div class="col-sm-12 col-md-7">
                                    <select style="width: 520px" name="subcategories" class="form-control select2">
                                        @foreach ($subcategory_exist as $item)
                                            <option value="{{ $item['id'] }}"
                                                {{ $item['id'] == $product['sub_category_id'] ? 'selected' : '' }}>
                                                {{ $item['name'] }} </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Champs obligatoire
                                    </div>
                                </div>

                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Description</label>
                                <div class="col-sm-12 col-md-7">
                                    <textarea name="description" class="summernote">
                                        {{ $product['description'] }}
                                    </textarea>
                                </div>
                            </div>

                            <!-- ========== Start principal image ========== -->
                            @if ($product->getFirstMediaUrl('principal_img'))
                                <div class="form-group row mb-4" id="principal_image">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Image
                                        principale</label>
                                    <div class="col-sm-12 col-md-7">
                                        <p class="card-text">
                                            <img id="img-preview"
                                                src="{{ $product->getFirstMediaUrl('principal_img') }}"
                                                width="250px" />
                                            <input type="file" name="principal_img" id="file_single"
                                                class="form-control" onchange="readURL(this);" hidden>

                                            <br> <label for="file_single" class="btn btn-primary btn-lg border mt-3">
                                                <i data-feather="image"></i>
                                                Ajoutez une image principale </label>
                                        </p>

                                    </div>
                                </div>
                            @endif
                            <!-- ========== End principal image ========== -->

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Images</label>
                                <div class="col-sm-12 col-md-7">
                                    <p class="card-text">
                                        <input type="file" id="files" class="form-control" name="files[]"
                                            accept=".jpg, .jpeg, .png, .gif, .webp" multiple hidden />
                                        <label for="files" class="btn btn-light btn-lg border">
                                            <i data-feather="image"></i>
                                            Ajoutez des images</label>
                                    </p>

                                </div>
                            </div>


                            <hr>
                            <!-- ========== Start Remise ========== -->
                            <h4 class="fw-bold ">REMISE</h4>
                            <p class="fw-bold fs-2 col-12" id="MsgError"></p>
                            <div class="form-group row">

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Montant de la remise</label>

                                    <input type="number" id="discount_price" value="{{ $product['montant_remise'] }}"
                                        name="montant_remise" class="form-control">
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Pourcentage(%) </label>

                                    <input type="number" id="discount"
                                        value="{{ $product['pourcentage_remise'] }}" name="pourcentage_remise"
                                        class="form-control">
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Date Debut</label>

                                    <input type="text" id="date_start"
                                        value="{{ $product['date_debut_remise'] }}" name="date_debut_remise"
                                        class="form-control datetimepicker">
                                </div>

                                <div class="col-sm-3">
                                    <label class="col-sm-12 col-form-label">Date Fin</label>

                                    <input type="text" id="date_end" value="{{ $product['date_fin_remise'] }}"
                                        name="date_fin_remise" class="form-control datetimepicker">
                                </div>

                            </div>
                            <!-- ========== End Remise ========== -->

                            <hr>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-7 text-lg-right">
                                    <button type="submit" class="btn btn-primary w-100">Modifier</button>
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
    <script src="{{ asset('admin/assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/assets/bundles/jquery-selectric/jquery.selectric.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js">
    </script>
@endsection
<script type="text/javascript">
    $(document).ready(function() {
        //display principal image if category is pack
        // $('#principal_image').hide()

        $('select[name="categories"]').change(function(e) {
            var selectVal = $("#category option:selected").attr('tag');
            if (selectVal == "Pack") {
                $('#principal_image').show()
            } else {
                $('#principal_image').hide(100)
            }
        });


        //edit file 
        // recuperation des files en base de donnee

        var getImage = {{ Js::from($images) }}
        // console.log(getImage)

        for (let index = 0; index < getImage.length; index++) {
            console.log(getImage[index].id);
            $("<span class=\"pip\">" +
                "<img class=\"imageThumb\" src=\"" + getImage[index].original_url + "\" title=\"" +
                getImage[index].id + "\"/>" +
                "<br/><span class=\"remove\" data-id=\"" + getImage[index].id + "\" >x</span>" +
                "</span>").insertAfter("#files");

            $(".remove").click(function(e) {
                $(this).parent(".pip").remove();

                var getId = e.target.dataset.id;
                // console.log(getId);

                // ajax delete image
                if (getId) {
                    $.ajax({
                        url: '/admin/produit/deleteImage/' + getId,
                        type: "GET",
                        dataType: "json",
                        success: function(response) {
                            console.log(response);
                        }
                    })
                }
            });
        }


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
                console.log(files);
            });
        } else {
            alert("Your browser doesn't support to File API")
        }

        //load sub cat
        // $('.subcat').hide();

        $('select[name="categories"]').on('change', function() {
            var catId = $(this).val();
            if (catId) {
                $.ajax({
                    url: '/admin/produit/loadSubCat/' + catId,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        $('select[name="subcategories"]').empty();

                        $.each(data, function(key, value) {
                            $('select[name="subcategories"]').append(
                                '<option value=" ' + value
                                .id + '">' + value.name + '</option>');
                            // console.log(key, value.title);
                        })

                        if (data.length > 0) {
                            $('.subcat').show(200);


                        } else {
                            $('.subcat').hide(200);
                        }
                    }

                })
            } else {
                $('select[name="subcategories"]').empty();
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
            $(this).datetimepicker();
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
