@extends('admin.layouts.app')
@section('title', 'produit')
@section('sub-title', 'Liste des produits')

<style>
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Produits</h4>
                            <a href="{{ route('product.create') }}" class="btn btn-primary">Ajouter un produit</a>
                        </div>

                        @include('admin.components.validationMessage')

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="tableExport">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                #
                                            </th>
                                            <th>Stock</th>
                                            <th>image</th>
                                            <th>Nom</th>
                                            <th>categories</th>
                                            <th>prix</th>
                                            <th>date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product as $key => $item)
                                            <tr>
                                                <td>
                                                    {{ ++$key }}
                                                </td>
                                                <td>
                                                    <label class="switch">
                                                        <input id="availableBtn" data-id="{{$item['id']}}" name="available" type="checkbox">
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                                <td>
                                                    <img alt="{{ asset($item->getFirstMediaUrl('product_image')) }}"
                                                        src="{{ asset($item->getFirstMediaUrl('product_image')) }}"
                                                        width="35">
                                                    <br> <small># {{ $item['code'] }} </small>
                                                </td>
                                                <td>{{ $item['title'] }}</td>
                                                <td>
                                                    @foreach ($item['categories'] as $items)
                                                        {{ $items['name'] }}
                                                    @endforeach
                                                </td>
                                                {{-- <td>{{ $item['collection'] ? $item['collection']['name'] : '' }}</td> --}}
                                                <td>{{ number_format($item['price'], 0) }} FCFA</td>
                                                {{-- <td>
                                                    @foreach ($item['pointures'] as $items)
                                                        {{ $items['pointure'] }}
                                                    @endforeach
                                                </td> --}}
                                                {{-- <td>
                                                    @foreach ($item['tailles'] as $items)
                                                        {{ $items['taille'] }}
                                                    @endforeach
                                                </td> --}}
                                                <td>{{ \Carbon\Carbon::parse($item['created_at'])->diffForHumans() }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown"
                                                            class="btn btn-warning dropdown-toggle">Actions</a>
                                                        <div class="dropdown-menu">
                                                            <a href="#" class="dropdown-item has-icon"><i
                                                                    class="fas fa-eye"></i>
                                                                Voir</a>
                                                            <a href="{{ route('product.edit', $item['id']) }}"
                                                                class="dropdown-item has-icon"><i class="far fa-edit"></i>
                                                                Modifier</a>

                                                            <a href="#" role="button" data-id="{{ $item['id'] }}"
                                                                class="dropdown-item has-icon text-danger delete"><i
                                                                    class="far fa-trash-alt"></i>Supprimer</a>

                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(document).ready(function() {

            $('.delete').on("click", function(e) {
                e.preventDefault();
                var Id = $(this).attr('data-id');
                swal({
                    title: "Suppression",
                    text: "Veuillez confirmer la suppression",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Confirmer",
                    cancelButtonText: "Annuler",
                }).then((result) => {
                    if (result) {
                        $.ajax({
                            type: "POST",
                            url: "/admin/produit/destroy/" + Id,
                            dataType: "json",
                            data: {
                                _token: '{{ csrf_token() }}',

                            },
                            success: function(response) {
                                if (response.status === 200) {
                                    Swal.fire({
                                        toast: true,
                                        icon: 'success',
                                        title: 'Le produit a été retiré du panier',
                                        animation: false,
                                        position: 'top',
                                        background: '#3da108e0',
                                        iconColor: '#fff',
                                        color: '#fff',
                                        showConfirmButton: false,
                                        timer: 500,
                                        timerProgressBar: true,
                                    });
                                    setTimeout(function() {
                                        window.location.href =
                                            "{{ route('product.index') }}";
                                    }, 500);
                                }
                            }
                        });
                    }
                });
            });


//script for to available product
            $('#availableBtn').change(function (e) { 
                e.preventDefault();
             if($('#availableBtn').is(':checked')){
                   $('#availableBtn').prop('checked')
                   console.log('eeeeee');
                }else{
                    console.log(false);

                }
                
            });
        });
    </script>
@endsection
