@extends('admin.layouts.app')

@section('title', 'Temoignage')
@section('sub-title', 'Liste des temoignages')


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
                            <h4>Temoignages</h4>
                            <button type="button" data-toggle="modal" data-target="#modalAdd" class="btn btn-primary">Ajouter
                                un temoignage</button>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                #
                                            </th>
                                            <th>Nom</th>
                                            <th>Description</th>

                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($feedback as $key => $item)
                                            <tr id="row_{{ $item->id }}">
                                                <td>
                                                    {{ ++$key }}
                                                </td>
                                                <td> {{ $item['nom'] }} </td>

                                                <td> {!! $item['description'] !!} </td>


                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown"
                                                            class="btn btn-warning dropdown-toggle">Options</a>
                                                        <div class="dropdown-menu">

                                                            <a href="{{ route('temoignage.edit', $item['id']) }}"
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
    @include('admin.pages.feedback.modalAdd')

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
                            url: "/admin/temoignage/destroy/" + Id,
                            dataType: "json",
                            data: {
                                _token: '{{ csrf_token() }}',

                            },
                            success: function(response) {
                                if (response.status === 200) {
                                    Swal.fire({
                                        toast: true,
                                        icon: 'success',
                                        title: 'Temoignage a été retiré du panier',
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
        });
    </script>
@endsection
