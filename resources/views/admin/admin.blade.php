@extends('admin.layouts.app');

@section('content')
    <section class="section">

        @include('admin.components.validationMessage')

        <style>
            .card-content {
                color: #fff;
            }

            .card-order {
                background: linear-gradient(135deg, #f48665 0%, #fda23f 100%) !important;
            }

            .card-product {
                background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
            }

            .card-user {
                background: linear-gradient(135deg, #06a278 0%, #12d298 100%) !important;
            }

            .card-visit {
                background: linear-gradient(135deg, #f48665 0%, #d75ce2 100%) !important;
            }
        </style>

        <div class="row ">
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card card-order">
                    <div class="card-statistic-4">
                        <div class="align-items-center justify-content-between">
                            <div class="row ">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                        <h5 class="font-15">Commandes</h5>
                                        <h2 class="mb-3 font-18">{{ $orders }}</h2>
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
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card card-product">
                    <div class="card-statistic-4">
                        <div class="align-items-center justify-content-between">
                            <div class="row ">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                        <h5 class="font-15"> Produits</h5>
                                        <h2 class="mb-3 font-18">{{ $products }}</h2>
                                        {{-- <p class="mb-0"><span class="col-orange">09%</span> Decrease</p> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                        {{-- <img src="assets/img/banner/2.png" alt=""> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card card-user">
                    <div class="card-statistic-4">
                        <div class="align-items-center justify-content-between">
                            <div class="row ">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                        <h5 class="font-15">Clients</h5>
                                        <h2 class="mb-3 font-18">{{ $users }} </h2>
                                        {{-- <p class="mb-0"><span class="col-green">18%</span>
                                            Increase</p> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                        {{-- <img src="assets/img/banner/3.png" alt=""> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="card card-visit">
                    <div class="card-statistic-4">
                        <div class="align-items-center justify-content-between">
                            <div class="row ">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                    <div class="card-content">
                                        <h5 class="font-15">Visites</h5>
                                        <h2 class="mb-3 font-18">500</h2>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                    <div class="banner-img">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    {{-- <div class="card-icon l-bg-purple mt">
                    <i class="fas fa-cart-plus"></i>
                  </div> --}}
                    <div class="card-wrap">
                        <div class="padding-10">
                            <div class="text-right">
                                <h3 class="font-light mb-0">
                                    <i class="ti-arrow-up text-success"></i> {{ $orders_days }}
                                </h3>
                                <span class="text-muted">Commande du jour</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    {{-- <div class="card-icon l-bg-purple mt">
                    <i class="fas fa-cart-plus"></i>
                  </div> --}}
                    <div class="card-wrap">
                        <div class="padding-10">
                            <div class="text-right">
                                <h3 class="font-light mb-0">
                                    <i class="ti-arrow-up text-success"></i> {{ $orders_week }}
                                </h3>
                                <span class="text-muted">Commande semaine</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    {{-- <div class="card-icon l-bg-purple mt">
                    <i class="fas fa-cart-plus"></i>
                  </div> --}}
                    <div class="card-wrap">
                        <div class="padding-10">
                            <div class="text-right">
                                <h3 class="font-light mb-0">
                                    <i class="ti-arrow-up text-success"></i> {{ $orders_month }}
                                </h3>
                                <span class="text-muted">Commande du mois</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    {{-- <div class="card-icon l-bg-purple mt">
                    <i class="fas fa-cart-plus"></i>
                  </div> --}}
                    <div class="card-wrap">
                        <div class="padding-10">
                            <div class="text-right">
                                <h3 class="font-light mb-0">
                                    <i class="ti-arrow-up text-success"></i> {{ $orders_year }}
                                </h3>
                                <span class="text-muted">Commande année</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xl-3 col-lg-6">
                <div class="card">
                    <div class="card-bg">
                        <div class="p-t-20 d-flex justify-content-between">
                            <div class="col">
                                <h6 class="mb-0">Revenu Jour</h6>
                                <span class="font-weight-bold mb-0 font-20"> {{ number_format($ca_days, 0, ',', ' ') }}
                                    <small>FCFA</small> </span>
                            </div>
                            <i class="fas fa-hand-holding-usd card-icon col-cyan font-30 p-r-30"></i>
                        </div>
                        {{-- <canvas id="cardChart4" height="80"></canvas> --}}
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6">
                <div class="card">
                    <div class="card-bg">
                        <div class="p-t-20 d-flex justify-content-between">
                            <div class="col">
                                <h6 class="mb-0">Revenu Semaine</h6>
                                <span class="font-weight-bold mb-0 font-20">{{ number_format($ca_week, 0, ',', ' ') }}
                                    <small>FCFA</small></span>
                            </div>
                            <i class="fas fa-hand-holding-usd card-icon col-cyan font-30 p-r-30"></i>
                        </div>
                        {{-- <canvas id="cardChart4" height="80"></canvas> --}}
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6">
                <div class="card">
                    <div class="card-bg">
                        <div class="p-t-20 d-flex justify-content-between">
                            <div class="col">
                                <h6 class="mb-0">Revenu Mois</h6>
                                <span class="font-weight-bold mb-0 font-20">{{ number_format($ca_month, 0, ',', ' ') }}
                                    <small>FCFA</small></span>
                            </div>
                            <i class="fas fa-hand-holding-usd card-icon col-cyan font-30 p-r-30"></i>
                        </div>
                        {{-- <canvas id="cardChart4" height="80"></canvas> --}}
                    </div>
                </div>
            </div>


            <div class="col-xl-3 col-lg-6">
                <div class="card">
                    <div class="card-bg">
                        <div class="p-t-20 d-flex justify-content-between">
                            <div class="col">
                                <h6 class="mb-0">Revenu Année</h6>
                                <span class="font-weight-bold mb-0 font-20">{{ number_format($ca_year, 0, ',', ' ') }}
                                    <small>FCFA</small></span>
                            </div>
                            <i class="fas fa-hand-holding-usd card-icon col-cyan font-30 p-r-30"></i>
                        </div>
                        {{-- <canvas id="cardChart4" height="80"></canvas> --}}
                    </div>
                </div>
            </div>
        </div>




        <!-- ========== Start graphique statistic ========== -->

        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Type de client</h4>
                    </div>
                    <div class="card-body">
                        <div class="recent-report__chart">
                            <div id="chart7"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Top 5 Clients</h4>
                    </div>
                    <div class="card-body">
                        <div class="card-body">
                            <ul class="list-unstyled user-details list-unstyled-border list-unstyled-noborder">
                                @foreach ($top_user_order as $item)
                                    <li class="media">
                                        <img alt="image" class="mr-3 rounded-circle" width="50"
                                            src="{{ asset('site/assets/img/custom/avatar.png') }}">
                                        <div class="media-body">
                                            <div class="media-title"> {{ $item['name'] }} </div>
                                            <div class="text-job text-muted"> <i class="fa fa-phone"></i>
                                                {{ $item['phone'] }} </div>
                                            <div class="text-job text-muted text-lowercase"> <i
                                                    class="fa fa-envelope"></i> {{ $item['email'] }} </div>
                                        </div>
                                        <div class="media-items">
                                            <div class="media-item">
                                                <div class="media-value badge badge-info">{{count($item['orders']) }}
                                                </div>
                                                <div class="media-label">Commandes</div>
                                            </div>
                                            {{-- <div class="media-item">
                                            <div class="media-value">10K</div>
                                            <div class="media-label">Followers</div>
                                        </div>
                                        <div class="media-item">
                                            <div class="media-value">2,312</div>
                                            <div class="media-label">Following</div>
                                        </div> --}}
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        </div>


                        </ul>
                    </div>
                </div>
            </div>



          <!-- ========== Start statistic product and category more buy ========== -->
          @include('admin.pages.statistic.product_and_category')
          <!-- ========== End statistic product and category more buy ========== -->
          

        </div>

        <!-- ========== End graphique statistic ========== -->


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Dernières Commandes En attente</h4>
                    </div>
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
                                    @foreach ($orders_new as $key => $item)
                                        <tr>
                                            <td>{{ ++$key }} </td>
                                            <td><span style="font-weight:bold">{{ $item['code'] }}</span>
                                                <br> <span
                                                    class="badge {{ $item['status'] == 'attente' ? 'badge-primary' : ($item['status'] == 'livrée' ? 'badge-success' : ($item['status'] == 'confirmée' ? 'badge-info' : ($item['status'] == 'annulée' ? 'badge-danger' : ($item['status'] == 'precommande' ? 'badge-dark' : '')))) }} text-white p-1 px-3"><i
                                                        class="{{ $item['status'] == 'precommande' ? 'fa fa-clock' : '' }}"></i>
                                                    {{ $item['status'] }}
                                                </span>

                                            </td>
                                            <td>{{ $item['user']['name'] }} </td>
                                            {{-- <td>{{ $item['delivery_name'] }} - {{ $item['delivery_price'] }} </td> --}}
                                            <td>{{ $item['total'] }} </td>
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

                                                        @if ($item['status'] != 'livrée')
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

    </section>
    <div class="settingSidebar">
        <a href="javascript:void(0)" class="settingPanelToggle"> <i class="fa fa-spin fa-cog"></i>
        </a>
        <div class="settingSidebar-body ps-container ps-theme-default">
            <div class=" fade show active">
                <div class="setting-panel-header">Setting Panel
                </div>
                <div class="p-15 border-bottom">
                    <h6 class="font-medium m-b-10">Select Layout</h6>
                    <div class="selectgroup layout-color w-50">
                        <label class="selectgroup-item">
                            <input type="radio" name="value" value="1"
                                class="selectgroup-input-radio select-layout" checked>
                            <span class="selectgroup-button">Light</span>
                        </label>
                        <label class="selectgroup-item">
                            <input type="radio" name="value" value="2"
                                class="selectgroup-input-radio select-layout">
                            <span class="selectgroup-button">Dark</span>
                        </label>
                    </div>
                </div>
                <div class="p-15 border-bottom">
                    <h6 class="font-medium m-b-10">Sidebar Color</h6>
                    <div class="selectgroup selectgroup-pills sidebar-color">
                        <label class="selectgroup-item">
                            <input type="radio" name="icon-input" value="1"
                                class="selectgroup-input select-sidebar">
                            <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                                data-original-title="Light Sidebar"><i class="fas fa-sun"></i></span>
                        </label>
                        <label class="selectgroup-item">
                            <input type="radio" name="icon-input" value="2"
                                class="selectgroup-input select-sidebar" checked>
                            <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                                data-original-title="Dark Sidebar"><i class="fas fa-moon"></i></span>
                        </label>
                    </div>
                </div>
                <div class="p-15 border-bottom">
                    <h6 class="font-medium m-b-10">Color Theme</h6>
                    <div class="theme-setting-options">
                        <ul class="choose-theme list-unstyled mb-0">
                            <li title="white" class="active">
                                <div class="white"></div>
                            </li>
                            <li title="cyan">
                                <div class="cyan"></div>
                            </li>
                            <li title="black">
                                <div class="black"></div>
                            </li>
                            <li title="purple">
                                <div class="purple"></div>
                            </li>
                            <li title="orange">
                                <div class="orange"></div>
                            </li>
                            <li title="green">
                                <div class="green"></div>
                            </li>
                            <li title="red">
                                <div class="red"></div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="p-15 border-bottom">
                    <div class="theme-setting-options">
                        <label class="m-b-0">
                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                                id="mini_sidebar_setting">
                            <span class="custom-switch-indicator"></span>
                            <span class="control-label p-l-10">Mini Sidebar</span>
                        </label>
                    </div>
                </div>
                <div class="p-15 border-bottom">
                    <div class="theme-setting-options">
                        <label class="m-b-0">
                            <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                                id="sticky_header_setting">
                            <span class="custom-switch-indicator"></span>
                            <span class="control-label p-l-10">Sticky Header</span>
                        </label>
                    </div>
                </div>
                <div class="mt-4 mb-4 p-3 align-center rt-sidebar-last-ele">
                    <a href="#" class="btn btn-icon icon-left btn-primary btn-restore-theme">
                        <i class="fas fa-undo"></i> Restore Default
                    </a>

                     {{-- <a href="#" class="btn btn-icon icon-left btn-primary btn-restore-theme">
                        <i class="fas fa-setting"></i> Mode maintenance
                    </a> --}}
                </div>
            </div>
        </div>
    </div>
    {{-- <button id="play"></button>
    <audio id="pop">
        <source src="{{ asset('admin/assets/audio/ring.mp3') }}" type="audio/mpeg">
    </audio> --}}

    @push('js')
        <script src="{{ asset('admin/assets/bundles/apexcharts/apexcharts.min.js') }}"></script>
        <!-- Page Specific JS File -->
        <script src="{{ asset('admin/assets/js/page/chart-apexcharts.js') }}"></script>
        <script src="{{ asset('admin/assets/bundles/chartjs/chart.min.js') }}"></script>
        <script src="{{ asset('admin/assets/js/page/widget-data.js') }}"></script>
    @endpush

    <script>
       
        $(document).ready(function() {


             // 'use strict';
        $(function() {
            // chart1();
            // chart2();
            // chart3();
            // chart4();
            // chart5();
            // chart6();
            chart7();
            // chart8();
        });

        //chart type client 
        function chart7() {
            var client_fidele = {{ Js::from($client_fidele) }}
            var client_prospect = {{ Js::from($client_prospect) }}

            var options = {
                chart: {
                    width: 400,
                    type: 'pie',
                },
                labels: [ 'Client-prospect', 'Client-fidele',],
                series: [ client_prospect, client_fidele],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            }

            var chart = new ApexCharts(
                document.querySelector("#chart7"),
                options,
                client_fidele,
                client_prospect
            );

            chart.render();
        }

           







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
