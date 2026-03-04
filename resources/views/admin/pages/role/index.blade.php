@extends('admin.layouts.app')

@section('title', 'roles')
@section('sub-title', 'Liste des rôles')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div>
                        @include('admin.components.validationMessage')
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Rôles</h4>
                            <button type="button" data-toggle="modal" data-target="#modalAddRole"
                                class="btn btn-primary">
                                <i class="fas fa-plus"></i> Ajouter un rôle
                            </button>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Nom du rôle</th>
                                            <th>Nombre de permissions</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($roles as $key => $item)
                                            <tr>
                                                <td class="text-center">{{ ++$key }}</td>
                                                <td>
                                                    <span class="badge badge-primary px-3 py-2">
                                                        {{ $item->name }}
                                                    </span>
                                                </td>
                                                <td>{{ $item->permissions_count }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown"
                                                            class="btn btn-warning dropdown-toggle">Options</a>
                                                        <div class="dropdown-menu">
                                                            <a href="{{ route('role.edit', $item->id) }}"
                                                                class="dropdown-item has-icon">
                                                                <i class="far fa-edit"></i> Modifier
                                                            </a>
                                                            <a href="#" role="button" data-id="{{ $item->id }}"
                                                                class="dropdown-item has-icon text-danger delete">
                                                                <i class="far fa-trash-alt"></i> Supprimer
                                                            </a>
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

    @include('admin.pages.role.modalAdd')

    <script>
        $(document).ready(function() {
            $('.delete').on("click", function(e) {
                e.preventDefault();
                var Id = $(this).attr('data-id');
                swal({
                    title: "Suppression",
                    text: "Voulez-vous vraiment supprimer ce rôle ?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Confirmer",
                    cancelButtonText: "Annuler",
                }).then((result) => {
                    if (result) {
                        $.ajax({
                            type: "POST",
                            url: "/admin/role/destroy/" + Id,
                            dataType: "json",
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                if (response.success) {
                                    swal("Supprimé!", "Le rôle a été supprimé.", "success")
                                        .then(() => location.reload());
                                }
                            },
                            error: function() {
                                swal("Erreur", "Une erreur est survenue.", "error");
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
