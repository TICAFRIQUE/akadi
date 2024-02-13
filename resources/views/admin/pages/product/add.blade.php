@extends('admin.layouts.app')
@section('title', 'produit')
@section('sub-title', 'Ajouter un produit')


@push('css')
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom.css') }}">
@endpush

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
                        <h4>Ajouter un produit</h4>
                    </div>
                    @include('admin.components.validationMessage')

                    <form class="needs-validation" novalidate="" action="{{ route('product.store') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Titre</label>
                                <div class="col-sm-12 col-md-7">
                                    <input name="title" type="text" class="form-control" required>
                                    <div class="invalid-feedback">
                                        Champs obligatoire
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Prix</label>
                                <div class="col-sm-12 col-md-7">
                                    <input name="price" type="number" class="form-control currency" required>
                                    <div class="invalid-feedback">
                                        Champs obligatoire
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label for=""
                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Categorie</label>

                                <div class="col-sm-12 col-md-7">
                                    <select name="categories" class="form-control select2" required>
                                        <option value="">Selectionner une catégorie</option>
                                        @foreach ($categories as $item)
                                            <option value="{{ $item['id'] }}"> {{ $item['name'] }} </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Champs obligatoire
                                    </div>
                                </div>
                                {{-- <button type="button" data-toggle="modal" data-target="#modalAddCategory"
                                    class="btn btn-primary"><i data-feather="plus"></i></button> --}}
                            </div>



                            <div class="form-group row mb-4 subcat">
                                <label for="" class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Sous
                                    categorie</label>

                                <div class="col-sm-12 col-md-7">
                                    <select style="width: 520px" name="subcategories"
                                        class="form-control select2 subCat_required ">
                                        @foreach ($subcategories as $item)
                                            {{-- <option value="{{ $item['id'] }}"> {{ $item['name'] }} </option> --}}
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">
                                        Champs obligatoire
                                    </div>
                                </div>
                                {{-- <button type="button" data-toggle="modal" data-target="#modalAddsousCategorie"
                                    class="btn btn-primary"><i data-feather="plus"></i></button> --}}
                            </div>

                            {{-- <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Options</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="section" value="option1">
                                    <label class="form-check-label" for="section">Sections</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="collection" value="option2">
                                    <label class="form-check-label" for="collection">Collections</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="pointure" value="option2">
                                    <label class="form-check-label" for="pointure">Pointures</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="taille" value="option2">
                                    <label class="form-check-label" for="taille">Tailles</label>
                                </div>
                            </div> --}}

                            {{-- <div class="form-group row mb-4" id="sectionDiv">
                                <label for=""
                                    class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Section
                                    Category</label>

                                <div class="col-sm-12 col-md-7">
                                    <select  style="width: 520px" name="category_section[]" class="form-control select2" multiple>
                                        @foreach ($section_categories as $item)
                                            <option value="{{ $item['id'] }}"> {{ $item['name'] }} </option>
                                        @endforeach
                                    </select>

                                </div>
                                <button type="button" data-toggle="modal" data-target="#modalAddCategory"
                                    class="btn btn-primary"><i data-feather="plus"></i> Add New</button>
                            </div>

                            <div class="form-group row mb-4" id="collectionDiv">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Collection</label>
                                <div class="col-sm-12 col-md-7">
                                    <select style="width:520px" name="collection" class="form-control select2 ">
                                        @foreach ($collection as $item)
                                            <option value="{{ $item['id'] }}"> {{ $item['name'] }} </option>
                                        @endforeach
                                    </select>

                                </div>
                                <button type="button" data-toggle="modal" data-target="#modalAddCollection"
                                    class="btn btn-primary"><i data-feather="plus"></i> Add New</button>
                            </div> --}}

                            {{-- <div class="form-group row mb-4" id="pointureDiv">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Pointure</label>
                                <div class="col-sm-12 col-md-7">
                                    <select name="pointures[]" class="form-control selectric " multiple>
                                        <option disabled selected value></option>
                                        @for ($i = 35; $i < 50; $i++)
                                            <option value="{{ $i }}"> {{ $i }} </option>
                                        @endfor
                                    </select>

                                </div>
                            </div> --}}
                            {{-- <div class="form-group row mb-4" id="tailleDiv">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Taille</label>
                                <div class="col-sm-12 col-md-7">
                                    <select name="tailles[]" class="form-control selectric " multiple>
                                        <option disabled selected value></option>
                                        @php
                                            $taille = ['s', 'm', 'l', 'xl', '2xl'];
                                        @endphp
                                        @foreach ($taille as $item)
                                            <option value="{{ $item }}">
                                                {{ ucFirst($item) }} </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div> --}}

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Description</label>
                                <div class="col-sm-12 col-md-7">
                                    <textarea name="description" class="summernote-simple"></textarea>
                                </div>
                            </div>

                            <!-- ========== Start add image ========== -->
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Images</label>
                                <div class="col-sm-12 col-md-7">
                                    <p class="card-text">
                                        <input type="file" id="files" class="form-control media" name="files[]"
                                            accept="image/*" multiple hidden required />
                                        <label for="files" class="btn btn-light btn-lg border">
                                            <i data-feather="image"></i>
                                            Ajoutez des images</label>
                                    <div class="invalid-feedback">Champs obligatoire</div>
                                    </p>

                                </div>
                            </div>
                            <!-- ========== End add image ========== -->


                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                <div class="col-sm-12 col-md-7 text-lg-right">
                                    <button type="submit" class="btn btn-primary btn-submit">Enregistrer</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- @include('admin.pages.collection.modalAdd')
@include('admin.pages.category.modalAdd')
@include('admin.pages.subCategory.modalAdd') --}}

@section('script')
    <script src="{{ asset('admin/assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('admin/assets/bundles/jquery-selectric/jquery.selectric.min.js') }}"></script>
@endsection
<script type="text/javascript">
    $(document).ready(function() {
        let imgArray = [];

        // Gestion upload image
        // if (window.File && window.FileList && window.FileReader) {
        //     $("#files").on("change", function(e) {
        //         var files = e.target.files,
        //             filesLength = files.length;
        //         for (var i = 0; i < filesLength; i++) {
        //             var f = files[i]
        //             imgArray.push(f);

        //             for (let i = 0; i < imgArray.length; i++) {
        //                 var fileReader = new FileReader();
        //                 fileReader.onload = (function(e) {
        //                     var file = e.target;
        //                     $("<span class=\"pip\">" +
        //                         "<img class=\"imageThumb\" src=\"" + e.target.result +
        //                         "\" title=\"" + imgArray[i]
        //                         .name + "\"/>" +
        //                         "<br/><span class=\"remove\"data-id=\"" +
        //                         i + "\">x</span>" +
        //                         "</span>").insertAfter("#files");
        //                     $(".remove").click(function(e) {
        //                         let filename = $(this).parent().find("img")
        //                             .attr("title");
        //                         var removeId = $(this).attr(
        //                             'data-id');

        //                         if (imgArray[i].name === filename) {
        //                             var remove = imgArray.splice(removeId, 1);
        //                         }

        //                         console.log('media', imgArray);

        //                         $(this).parent(".pip").remove();
        //                     });

        //                 });

        //             }

        //             fileReader.readAsDataURL(f);
        //         }

        //     });

        //     // console.log(imgArray);

        // } else {
        //     alert("Your browser doesn't support to File API")
        // }



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



        //hide elements
        // $('#sectionDiv').hide();
        // $('#collectionDiv').hide();
        // $('#pointureDiv').hide();
        // $('#tailleDiv').hide();

        //show if checked


        // $('#collection').change(function() {
        //     $('#collectionDiv').toggle(200);
        // });

        // $('#pointure').change(function() {
        //     $('#pointureDiv').toggle(200);
        // });

        // $('#taille').change(function() {
        //     $('#tailleDiv').toggle(200);
        // });

        // $('#section').change(function() {
        //     $('#sectionDiv').toggle(200);
        // });

        // $('.btn-submit').click(function(e) {
        //     e.preventDefault();
        //     $.ajaxSetup({
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         }
        //     });

        //     var img = $('#files').val();
        //     console.log(img);
            
        //     $.ajax({
        //         type: "POST",
        //         url: "/admin/produit/add",
        //         data: imgArray,
        //         dataType: "json",
        //         success: function(response) {
        //             console.log(response);
        //         }
        //     });

        // });







    });
</script>
@endsection
