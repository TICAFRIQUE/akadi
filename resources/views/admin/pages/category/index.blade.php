@extends('admin.layouts.app')

@section('title', 'categorie')
@section('sub-title', 'Liste des categories')



@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div>
                        @include('admin.components.validationMessage');
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Categories</h4>
                            <button type="button" data-toggle="modal" data-target="#modalAddCategory"
                                class="btn btn-primary">Ajouter
                                une categorie</button>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                #
                                            </th>
                                            <th>Statut</th>
                                            <th>Name</th>
                                            <th>image</th>
                                            {{-- <th>banner</th> --}}
                                            <th>Nombre article</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($category as $key => $item)
                                            <tr>
                                                <td>
                                                    {{ ++$key }}
                                                </td>
                                                <td> <span class="badge badge-{{ $item['is_active'] == 1 ? 'success' : 'danger' }} text-white p-1 px-3">{{ $item['is_active']? 'Active' : 'Inactive' }}</span> </td>
                                                <td> {{ $item['name'] }}
                                                    {{-- <br>#<small class="text-dark">{{ $item['type'] }} </small> --}}

                                                </td>
                                                <td>
                                                    <img alt="{{ $item->getFirstMediaUrl('category_image') }}"
                                                        src="{{ $item->getFirstMediaUrl('category_image') }}"
                                                        width="35">
                                                </td>
                                                {{-- <td>
                                                    <img alt="{{ $item->getFirstMediaUrl('category_banner') }}"
                                                        src="{{ $item->getFirstMediaUrl('category_banner') }}"
                                                        width="35">
                                                </td> --}}
                                                <td class="align-middle">
                                                    {{ $item->products->count() }}
                                                </td>

                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown"
                                                            class="btn btn-warning dropdown-toggle">Options</a>
                                                        <div class="dropdown-menu">
                                                            <a href="#" class="dropdown-item has-icon"><i
                                                                    class="fas fa-eye"></i> View</a>
                                                            <a href="{{ route('category.edit', $item['id']) }}"
                                                                class="dropdown-item has-icon"><i class="far fa-edit"></i>
                                                                Edit</a>

                                                            <a href="#" role="button" data-id="{{ $item['id'] }}"
                                                                class="dropdown-item has-icon text-danger delete"><i
                                                                    class="far fa-trash-alt"></i>Delete</a>

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
    @include('admin.pages.category.modalAdd')

    <script>
        $(document).ready(function() {
            $('.delete').on("click", function(e) {
                e.preventDefault();
                var Id = $(this).attr('data-id');
                swal({
                    title: "Suppression",
                    text: "Veuillez confirmer la suppression",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Confirmer",
                    cancelButtonText: "Annuler",
                }).then((result) => {
                    if (result) {
                        $.ajax({
                            type: "POST",
                            url: "/admin/categorie/destroy/" + Id,
                            dataType: "json",
                            data: {
                                _token: '{{ csrf_token() }}',

                            },
                            success: function(response) {
                                if (response.status === 200) {
                                    Swal.fire({
                                        toast: true,
                                        icon: 'success',
                                        title: 'La categorie a été supprimée avec succès!',
                                        animation: false,
                                        position: 'top',
                                        background: '#3da108e0',
                                        iconColor: '#fff',
                                        color: '#fff',
                                        showConfirmButton: false,
                                        timer: 1000,
                                        timerProgressBar: true,
                                    });
                                    setTimeout(function() {
                                        window.location.href =
                                            "{{ route('category.index') }}";
                                    }, 500);
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
