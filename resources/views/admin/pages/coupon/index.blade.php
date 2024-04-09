@extends('admin.layouts.app')
@section('title', 'coupon')
@section('sub-title', 'Liste des coupons')


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
                            <h4>Coupon de reduction</h4>
                            <a href="{{ route('coupon.create') }}" class="btn btn-primary">Ajouter
                                un coupon</a>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                #
                                            </th>
                                            <th>Status</th>
                                            <th>Code</th>
                                            <th>Pourcentage</th>
                                            <th>Date de debut</th>
                                            <th>Expiration</th>
                                            <th>Date de création</th>


                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($coupon as $key => $item)
                                            <tr id="row_{{$item['id']}}">
                                                <td>
                                                    {{ ++$key }}
                                                </td>
                                                <td> {{ $item['status_coupon'] }} </td>


                                                <td class="align-middle">
                                                    {{ $item['code'] }}
                                                </td>
                                                <td class="align-middle">
                                                    {{ $item['pourcentage_coupon'] }}
                                                </td>
                                                <td class="align-middle">
                                                    {{ $item['date_debut_coupon'] }}
                                                </td>
                                                <td class="align-middle">
                                                    {{ $item['Expiration'] }}
                                                </td>
                                                <td class="align-middle">
                                                    {{ \Carbon\Carbon::parse($item['created_at'])->diffForHumans() }}
                                                </td>


                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown"
                                                            class="btn btn-warning dropdown-toggle">Options</a>
                                                        <div class="dropdown-menu">
                                                            {{-- <a href="#" class="dropdown-item has-icon"><i
                                                                    class="fas fa-eye"></i> Details</a> --}}


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
    @include('admin.pages.subCategory.modalAdd')

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
                            url: "/admin/coupon/destroy/" + Id,
                            dataType: "json",
                            data: {
                                _token: '{{ csrf_token() }}',

                            },
                            success: function(response) {
                                if (response.status === 200) {
                                    Swal.fire({
                                        toast: true,
                                        icon: 'success',
                                        title: 'Suppression reussi',
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
