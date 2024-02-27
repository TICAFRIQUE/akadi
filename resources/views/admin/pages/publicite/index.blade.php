@extends('admin.layouts.app')

@section('title', 'publicite')
@section('sub-title', 'Liste des sliders')


@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div>
                        @include('admin.components.validationMessage');
                    </div>
                    <div class="card">
                        <div class="card-header d-flex justify-content-around">
                            <h4>publicité {{ request('type') ?? '' }} </h4>
                            <button type="button" data-toggle="modal" data-target="#modalAddpublicite"
                                class="btn btn-primary">Ajouter
                                une publicite</button>

                            <div class="dropdown">
                                @php
                                    $type = ['slider', 'popup', 'arriere-plan', 'banniere', 'small-card', 'top-promo'];
                                @endphp
                                <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">
                                    <i class="fa fa-filter"></i>
                                    Filtre par type</a>
                                <div class="dropdown-menu">
                                    @foreach ($type as $item)
                                        <a href="/admin/publicite?type={{ $item }}"
                                            class="dropdown-item has-icon text-capitalize"><i class="far fa-file-image"></i>
                                            {{ $item }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                #
                                            </th>
                                            <th>Type</th>
                                            <th>image</th>
                                            <th>Url</th>
                                            <th>status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($publicite as $key => $item)
                                            <tr id="row_{{ $item['id'] }}">
                                                <td>
                                                    {{ ++$key }}
                                                </td>
                                                <td> {{ $item['type'] }} </td>
                                                <td>
                                                    <img alt="{{ $item->getFirstMediaUrl('publicite_image') }}"
                                                        src="{{ $item->getFirstMediaUrl('publicite_image') }}"
                                                        width="35">
                                                </td>
                                                <td class="align-middle">
                                                    {{ $item->url }}
                                                </td>

                                                <td class="align-middle ">
                                                    <span
                                                        class="status badge badge-{{ $item['status'] == 'active' ? 'success' : 'danger' }}">{{ $item->status }}</span>
                                                </td>

                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown"
                                                            class="btn btn-warning dropdown-toggle">Options</a>
                                                        <div class="dropdown-menu">

                                                            <a href="{{ route('publicite.edit', $item['id']) }}"
                                                                class="dropdown-item has-icon"><i class="far fa-edit"></i>
                                                                Edit</a>

                                                            <a href="#" role="button"
                                                                data-state={{ $item['status'] }}
                                                                data-id="{{ $item['id'] }}"
                                                                class="dropdown-item has-icon changeState"><i
                                                                    class="fas fa-toggle-{{ $item['status'] == 'active' ? 'off' : 'on' }}"></i>
                                                                {{ $item['status'] == 'active' ? 'Desactiver' : 'Activer' }}
                                                            </a>

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
    @include('admin.pages.publicite.modalAdd')

    <script>
        $(document).ready(function() {

            //delete pubs
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
                            url: "/admin/publicite/destroy/" + Id,
                            dataType: "json",
                            data: {
                                _token: '{{ csrf_token() }}',

                            },

                            success: function(response) {
                                if (response.status === 200) {
                                    Swal.fire({
                                        toast: true,
                                        icon: 'success',
                                        title: 'Le publicite a été retiré',
                                        animation: false,
                                        position: 'top',
                                        background: '#3da108e0',
                                        iconColor: '#fff',
                                        color: '#fff',
                                        showConfirmButton: false,
                                        timer: 500,
                                        timerProgressBar: true,
                                    });
                                    // delete row on table
                                    $('#row_' + Id).remove();
                                }
                            }
                        });
                    }
                });
            });

            //change state
            $('.changeState').click(function(e) {
                e.preventDefault();
                var Id = $(this).attr('data-id');
                var status = $(this).attr("data-state");

                $.ajax({
                    type: "GET",
                    url: "{{ route('publicite.changeState') }}",
                    data: {
                        id: Id,
                        state: status
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success == 200) {
                            location.reload();
                            // $('.status').html(response.state);
                        }
                    }
                });

            });
        });
    </script>
@endsection
