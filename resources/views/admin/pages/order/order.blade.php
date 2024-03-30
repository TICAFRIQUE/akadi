@extends('admin.layouts.app')
@section('title', 'order')
@section('sub-title', 'Liste des commandes')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Commandes {{ request('d') ? request('d') : request('s') }} </h4>
                        </div>

                        @include('admin.components.validationMessage')

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tableExport">
                                    <thead>
                                        <tr>
                                            <th class="text-center">
                                                #
                                            </th>
                                            <th>code</th>
                                            <th>client</th>
                                            {{-- <th>Livraison</th> --}}
                                            <th>Total</th>
                                            <th>date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $key => $item)
                                            <tr id="row_{{ $item['id'] }}">
                                                <td>{{ ++$key }} </td>
                                                <td><span style="font-weight:bold">{{ $item['code'] }}</span>
                                                    <br> <span
                                                        class="badge {{ $item['status'] == 'attente' ? 'badge-primary' : ($item['status'] == 'livrée' ? 'badge-success' : ($item['status'] == 'confirmée' ? 'badge-info' : ($item['status'] == 'annulée' ? 'badge-danger' : ''))) }} text-white p-1 px-3">{{ $item['status'] }}
                                                    </span>
                                                </td>
                                                <td>{{ $item['user']['name'] }} </td>
                                                {{-- <td>{{ $item['delivery_name'] }} - {{ $item['delivery_price'] }} </td> --}}
                                                <td>{{ number_format($item['total'], 0, '', ' ') }} </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($item['created_at'])->diffForHumans() }}
                                                    <br>
                                                    {{ \Carbon\Carbon::parse($item['created_at'])->isoFormat('dddd D MMMM YYYY') }}

                                                </td>

                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown"
                                                            class="btn btn-warning dropdown-toggle">Options</a>
                                                        <div class="dropdown-menu">
                                                            <a href="{{ route('order.show', $item['id']) }}"
                                                                class="dropdown-item has-icon"><i class="fas fa-eye"></i>
                                                                Detail</a>

                                                            @if ($item['status'] != 'livrée' && $item['status'] != 'annulée')
                                                                <a href="/admin/order/changeState?cs=confirmée && id={{ $item['id'] }}"
                                                                    class="dropdown-item has-icon"><i
                                                                        class="fas fa-check"></i>
                                                                    Confirmée</a>
                                                                <a href="/admin/order/changeState?cs=livrée && id={{ $item['id'] }}"
                                                                    class="dropdown-item has-icon"><i
                                                                        class="fas fa-shipping-fast"></i>
                                                                    Livrée</a>
                                                                <a href="/admin/order/changeState?cs=attente && id={{ $item['id'] }}"
                                                                    class="dropdown-item has-icon"><i
                                                                        class="fas fa-arrow-down"></i>
                                                                    Attente</a>

                                                                <a href="/admin/order/changeState?cs=annulée && id={{ $item['id'] }}"
                                                                    role="button" data-id="{{ $item['id'] }}"
                                                                    class="dropdown-item has-icon text-danger delete"><i
                                                                        data-feather="x-circle"></i> Annuler</a>
                                                            @endif


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
     $(document).ready(function () {
           var table = $('#tableExport').DataTable({
            // destroy: true,
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],

            drawCallback: function(settings) {
                // $('.delete').on("click", function(e) {
                //     e.preventDefault();
                //     var Id = $(this).attr('data-id');
                //     swal({
                //         title: "Suppression",
                //         text: "Veuillez confirmer la suppression",
                //         type: "warning",
                //         showCancelButton: true,
                //         confirmButtonText: "Confirmer",
                //         cancelButtonText: "Annuler",
                //     }).then((result) => {
                //         if (result) {
                //             $.ajax({
                //                 type: "POST",
                //                 url: "/admin/auth/destroy/" + Id,
                //                 dataType: "json",
                //                 data: {
                //                     _token: '{{ csrf_token() }}',

                //                 },
                //                 success: function(response) {
                //                     if (response.status === 200) {
                //                         Swal.fire({
                //                             toast: true,
                //                             icon: 'success',
                //                             title: 'Utilisateur supprimé avec success',
                //                             animation: false,
                //                             position: 'top',
                //                             background: '#3da108e0',
                //                             iconColor: '#fff',
                //                             color: '#fff',
                //                             showConfirmButton: false,
                //                             timer: 500,
                //                             timerProgressBar: true,
                //                         });
                //                         $('#row_' + Id).remove();

                //                     }
                //                 }
                //             });
                //         }
                //     });
                // });
            }
        });
     });
    </script>
@endsection
