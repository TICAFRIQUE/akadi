@extends('admin.layouts.app')
@section('title', 'auth')
@section('sub-title', 'Liste des clients')


@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        @include('admin.components.validationMessage')
                        <div class="card-header d-flex justify-content-between">
                            @if (request('user'))
                                <h4>Liste des {{ request('user') }} ({{ count($users) }}) </h4>
                            @else
                                <h4>Liste de tous les utilisateurs</h4>
                            @endif

                            <a href="{{ route('user.register') }}" class="btn btn-primary">Ajouter un
                                utilsateur</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tableExport" style="width:100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nom</th>
                                            <th>Contact</th>
                                            <th>Email</th>
                                            {{-- <th>Boutique</th>
                                            <th>Localisation</th> --}}
                                            <th>Type utilisateur</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $key => $item)
                                            <tr id="row_{{ $item['id'] }}">
                                                <td>{{ ++$key }} </td>
                                                <td>{{ $item['name'] }}</td>
                                                <td>{{ $item['phone'] }}</td>
                                                <td>{{ $item['email'] }}</td>
                                                {{-- <td>  {{ $item['shop_name'] }} </td>
                                                <td>{{ $item['localisation'] }} </td> --}}
                                                <td>
                                                    @foreach ($item['roles'] as $role)
                                                        <br> <span
                                                            class="text-capitalize fw-bold">{{ $role['name'] }}</span>
                                                    @endforeach
                                                </td>


                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown"
                                                            class="btn btn-warning dropdown-toggle">Options</a>
                                                        <div class="dropdown-menu">

                                                            <a href="{{ route('user.edit', $item['id']) }}"
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

    <script>
        $(document).ready(function() {



            var table = $('#tableExport').DataTable({
                // destroy: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],

                drawCallback: function(settings) {
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
                                    url: "/admin/auth/destroy/" + Id,
                                    dataType: "json",
                                    data: {
                                        _token: '{{ csrf_token() }}',

                                    },
                                    success: function(response) {
                                        if (response.status === 200) {
                                            Swal.fire({
                                                toast: true,
                                                icon: 'success',
                                                title: 'Utilisateur supprimé avec success',
                                                animation: false,
                                                position: 'top',
                                                background: '#3da108e0',
                                                iconColor: '#fff',
                                                color: '#fff',
                                                showConfirmButton: false,
                                                timer: 500,
                                                timerProgressBar: true,
                                            });
                                            $('#row_' + Id).remove();

                                        }
                                    }
                                });
                            }
                        });
                    });
                }
            });


        });
    </script>
@endsection