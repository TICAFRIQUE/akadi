@extends('admin.layouts.app')
@section('title', 'order')
@section('sub-title', 'Liste des commandes')

<style>
    .card-order1 {
        background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
        color: white
    }

    .card-order {
        background: linear-gradient(135deg, #06a278 0%, #12d298 100%) !important;
    }

    .card-content {
        color: white
    }
</style>
@section('content')
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    {{-- <h5 class="p-3">Liste des Commandes
                        @if (request('date_debut') && request('date_fin'))
                            du {{ \Carbon\Carbon::parse(request('date_debut'))->format('d-m-Y') }} au
                            {{ \Carbon\Carbon::parse(request('date_fin'))->format('d-m-Y') }}
                        @elseif (request('date_debut'))
                            du {{ \Carbon\Carbon::parse(request('date_debut'))->format('d-m-Y') }}
                        @elseif (request('date_fin'))
                            du {{ \Carbon\Carbon::parse(request('date_fin'))->format('d-m-Y') }}
                        @else
                            {{ request('d') ? request('d') : request('s') }}
                        @endif
                    </h5> --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-order">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="">Commandes
                                                        {{ request('status') != 'all' ? request('status') : '' }}</h5>
                                                    @if (request('date_debut') && request('date_fin'))
                                                        du
                                                        {{ \Carbon\Carbon::parse(request('date_debut'))->format('d-m-Y') }}
                                                        au
                                                        {{ \Carbon\Carbon::parse(request('date_fin'))->format('d-m-Y') }}
                                                    @elseif (request('date_debut'))
                                                        du
                                                        {{ \Carbon\Carbon::parse(request('date_debut'))->format('d-m-Y') }}
                                                    @elseif (request('date_fin'))
                                                        du
                                                        {{ \Carbon\Carbon::parse(request('date_fin'))->format('d-m-Y') }}
                                                    @else
                                                        {{ request('d') ? request('d') : request('s') }}
                                                    @endif
                                                    </h5>
                                                    <h2 class="mb-3 font-18">{{ count($orders) }}</h2>
                                                    {{-- <p class="mb-0"><span class="col-green">10%</span> Increase</p> --}}
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                <div class="banner-img">
                                                    {{-- <img src="assets/img/banner/1.png" alt=""> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card card-order1">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="">Total</h5>
                                                    <h2 class="mb-3 font-18">
                                                        {{ number_format($orders->sum('total'), 0, ',', ' ') }} FCFA </h2>
                                                    {{-- <p class="mb-0"><span class="col-green">10%</span> Increase</p> --}}
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                <div class="banner-img">
                                                    {{-- <img src="assets/img/banner/1.png" alt=""> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <hr class="w-50 bg-secondary" size="5">
                        <h4>Filtre</h4>
                        <hr class="w-50 bg-secondary" size="5">
                    </div>
                    <div class="card-header d-flex justify-content-between">
                        <!-- ========== Start filter ========== -->
                        <div class="dropdown d-inline">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton2"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-filter"></i> Filtre par type de commande
                            </button>
                            <div class="dropdown-menu fw-bold">
                                <a class="dropdown-item has-icon" href="/admin/order?d=jour">Jour</a>
                                <a class="dropdown-item has-icon" href="/admin/order?s=attente">Attentes</a>
                                <a class="dropdown-item has-icon" href="/admin/order?s=confirmée">Confirmée</a>
                                <a class="dropdown-item has-icon" href="/admin/order?s=livrée">Livrées</a>
                                <a class="dropdown-item has-icon" href="/admin/order?s=annulée">Annulées</a>
                                <a class="dropdown-item has-icon fw-bold" href="/admin/order?s=precommande"> <i
                                        class="far fa-clock"></i> Précommandes</a>
                                <a class="dropdown-item has-icon" href="{{ route('order.index') }}">Toute les commandes</a>
                            </div>
                        </div>


                        <form class="needs-validation" action="{{ route('order.index') }}" method="get" class="px-3"
                            novalidate>
                            @csrf
                            <!-- date start-->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date">Statut</label>
                                        <select name="status" class="form-control" required>
                                            <option value="all">Toutes les commandes</option>
                                            <option value="attente" {{ request('status') == 'attente' ? 'selected' : '' }}>
                                                Attentes</option>
                                            <option value="confirmée"
                                                {{ request('status') == 'confirmée' ? 'selected' : '' }}>Confirmée</option>
                                            <option value="livrée" {{ request('status') == 'livrée' ? 'selected' : '' }}>
                                                Livrées</option>
                                            <option value="annulée" {{ request('status') == 'annulée' ? 'selected' : '' }}>
                                                Annulées</option>
                                            <option value="precommande"
                                                {{ request('status') == 'precommande' ? 'selected' : '' }}>Précommandes
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date">Date debut</label>
                                        <input type="date" name="date_debut" id="dateStart"
                                            value="{{ request('date_debut') }}" class="form-control" style="padding: 20px"
                                            required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date">Date fin</label>
                                        <input type="date" name="date_fin" value="{{ request('date_fin') }}"
                                            id="dateEnd" class="form-control" style="padding: 20px"required>
                                    </div>
                                </div>
                                <div class="col-md-2" style="margin-top: 30px">
                                    <div class="form-group">
                                        <label for="date"></label>
                                        <button type="submit" class="btn btn-primary p-2">Valider</button>
                                    </div>
                                </div>


                            </div>
                        </form>
                        <!-- ========== End filter ========== -->


                        <!-- ========== Start Statistic ========== -->

                        <!-- ========== End Statistic ========== -->


                    </div>

                    @include('admin.components.validationMessage')

                    <div class="card-body">

                        <!-- ========== Start Section ========== -->
                        @include('admin.pages.order.motif_annulation')
                        <!-- ========== End Section ========== -->
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tableExport">

                                <thead>
                                    <tr>
                                        <th class="text-center">
                                            #
                                        </th>
                                        <th>Statut</th>
                                        <th>code</th>
                                        <th>Nom du client</th>
                                        <th>Contact du client</th>
                                        <th>Email du client</th>
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

                                            <td>
                                                <span
                                                    class="badge {{ $item['status'] == 'attente' ? 'badge-primary' : ($item['status'] == 'livrée' ? 'badge-success' : ($item['status'] == 'confirmée' ? 'badge-info' : ($item['status'] == 'annulée' ? 'badge-danger' : ($item['status'] == 'precommande' ? 'badge-dark' : '')))) }} text-white p-1 px-3"><i
                                                        class="{{ $item['status'] == 'precommande' ? 'fa fa-clock' : '' }}"></i>
                                                    {{ $item['status'] }}
                                                </span>
                                            </td>

                                            <td><span style="font-weight:bold">{{ $item['code'] }}</span> </td>
                                            <td>{{ $item['user']['name'] }}</td>
                                            <td> {{ $item['user']['phone'] }}</td>
                                            <td> {{ $item['user']['email'] }}</td>
                                            {{-- <td>{{ $item['delivery_name'] }} - {{ $item['delivery_price'] }} </td> --}}
                                            <td>{{ number_format($item['total'], 0, '', ' ') }} </td>
                                            <td>
                                                {{-- {{ \Carbon\Carbon::parse($item['created_at'])->diffForHumans() }}
                                                <br> --}}
                                                {{ \Carbon\Carbon::parse($item['created_at'])->isoFormat('dddd D MMMM YYYY à HH:mm') }}

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

                                                            {{-- <a href="/admin/order/changeState?cs=annulée && id={{ $item['id'] }}"
                                                                    role="button" data-id="{{ $item['id'] }}"
                                                                    class="dropdown-item has-icon text-danger delete"><i
                                                                        data-feather="x-circle"></i> Annuler</a> --}}


                                                            <a href="#" role="button"
                                                                data-id="{{ $item['id'] }}"
                                                                class="dropdown-item has-icon text-danger btnCancel"><i
                                                                    data-feather="x-circle"></i> Annuler</a>

                                                            {{-- @include('admin.pages.order.motif_annulation') --}}
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

    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#tableExport').DataTable({
                // destroy: true,
                dom: 'Bfrtip',
                buttons: [

                    // {
                    //     extend: 'colvis',
                    //     text: 'Colonne à afficher',
                    //     className: 'btn btn-warning',
                    //     exportOptions: {
                    //        columns: ':visible'
                    //     },

                    // },

                    {
                        extend: 'copy',
                        // text: 'Copier',
                        // className: 'btn btn-primary',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        },

                    },

                    {
                        extend: 'csv',
                        // text: 'Csv',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        },

                    },


                    {
                        extend: 'excel',
                        // text: 'Excel',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        },

                    },

                    {
                        extend: 'pdf',
                        // text: 'Pdf',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        },

                    },

                    {
                        extend: 'print',
                        // text: 'Imprimer',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        },

                    },


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

                    // motif d'annulation 

                    $('.motif_autre').hide(); //textarea autre_motif
                    $('#motif_annulation').hide(); // div form

                    //si on appuie sur annuler une commande
                    $('.btnCancel').click(function(e) {
                        e.preventDefault();
                        //on recupere ID de la commande
                        var cmdId = $(this).attr('data-id');

                        $('#commandeId').val(cmdId);

                        //scroller jusqu'au formulaire motif annulation
                        $('html, body').animate({
                            scrollTop: $("#motif_annulation").offset().top - 90
                        }, 500);

                        //afficher le formulaire
                        $('#motif_annulation').show(); // div form


                        //choisir le motif
                        $('#motif_selected').change(function(e) {
                            e.preventDefault();
                            var motif_select = $(this).val()

                            if (motif_select == 'autre') {
                                $('.motif_autre').show();
                                $('#_motif_autre').prop('required', true);

                            } else {
                                $('.motif_autre').hide();
                                $('#_motif_autre').prop('required', false);

                            }

                        });
                    });


                    //fermer le formulaire motif annulation
                    $('.btn-close').click(function(e) {
                        e.preventDefault();
                        $('#motif_selected').val('')
                        $('#_motif_autre').val('')
                        $('#motif_annulation').hide(); // div form
                        $('.motif_autre').hide(); //textarea autre_motif
                    });




                    
                //executer la fonction chaque 5 secondes
                // setInterval(newOrders, 5000);

                setInterval(function() {
                    moment.locale('fr'); // définit la langue en français
                    $.ajax({
                        url: "{{ route('order.checkNewOrder') }}",
                        method: "GET",
                        success: function(data) {
                            console.log(data);
                            
                            if (data.orders && data.orders.length > 0) {
                                data.orders.forEach(function(item, index) {
                                    if (!document.querySelector('#row_' + item
                                            .id)) {
                                        let badgeClass = '';
                                        let icon = '';

                                        switch (item.status) {
                                            case 'attente':
                                                badgeClass = 'badge-primary';
                                                break;
                                            case 'livrée':
                                                badgeClass = 'badge-success';
                                                break;
                                            case 'confirmée':
                                                badgeClass = 'badge-info';
                                                break;
                                            case 'annulée':
                                                badgeClass = 'badge-danger';
                                                break;
                                            case 'precommande':
                                                badgeClass = 'badge-dark';
                                                icon =
                                                    '<i class="fa fa-clock"></i> ';
                                                break;
                                        }

                                        const html = `
                                    <tr id="row_${item.id}">
                                        <td> <span class="badge badge-success text-white p-1 px-3"> Nouveau </span>  </td>
                                        <td><span class="badge ${badgeClass} text-white p-1 px-3">${icon}${item.status}</span></td>
                                        <td><span style="font-weight:bold">${item.code}</span></td>
                                        <td>${item.user.name}</td>
                                        <td>${item.user.phone}</td>
                                        <td>${item.user.email}</td>
                                        <td>${Number(item.total).toLocaleString()}</td>
                                        <td>${moment(item.created_at).format('dddd D MMMM YYYY à HH:mm')}</td>
                                        <td>
                                            <div class="dropdown">
                                                <a href="#" data-toggle="dropdown" class="btn btn-warning dropdown-toggle">Options</a>
                                                <div class="dropdown-menu">
                                                    <a href="/admin/order/show/${item.id}" class="dropdown-item has-icon">
                                                        <i class="fas fa-eye"></i> Detail
                                                    </a>
                                                    ${item.status !== 'livrée' && item.status !== 'annulée' ? `
                                                                    <a href="/admin/order/changeState?cs=confirmée && id=${item.id}" class="dropdown-item has-icon">
                                                                        <i class="fas fa-check"></i> Confirmée
                                                                    </a>
                                                                    <a href="/admin/order/changeState?cs=livrée && id=${item.id}" class="dropdown-item has-icon">
                                                                        <i class="fas fa-shipping-fast"></i> Livrée
                                                                    </a>
                                                                    <a href="/admin/order/changeState?cs=attente && id=${item.id}" class="dropdown-item has-icon">
                                                                        <i class="fas fa-arrow-down"></i> Attente
                                                                    </a>
                                                                    <a href="#" role="button" data-id="${item.id}" class="dropdown-item has-icon text-danger btnCancel">
                                                                        <i data-feather="x-circle"></i> Annuler
                                                                    </a>
                                                                ` : ''}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>`;

                                        $('#tableExport tbody').prepend(
                                            html
                                        ); // remplace #myTable par l’ID réel de ton tableau
                                    }
                                });
                            }
                        }
                    });
                }, 5000);

                }







            });
        });
    </script>
@endsection
