<!DOCTYPE html>
<html lang="fr">
<!-- index.html  21 Nov 2019 03:44:50 GMT -->

<head>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BTWQ17CVRB"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-BTWQ17CVRB');
    </script>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}- Admin Dashboard </title>

    <!-- Favicons -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('admin/assets/img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('admin/assets/img/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('admin/assets/img/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('admin/assets/img/favicon/site.webmanifest') }}">
    <!-- General CSS Files -->
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"></script> --}}

    <script src="{{ asset('admin/assets/js/jquery.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('admin/assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/bundles/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('admin/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/bundles/prism/prism.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/bundles/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">

    @yield('css')
    @stack('css')

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/components.css') }}">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{ asset('admin/assets/css/custom.css') }}">
    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('admin/assets/img/favicon.ico') }}' />

    <link href="
    https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css
    " rel="stylesheet">
    <script src=" https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
    <style>
        body {
            color: black;

            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            /* Firefox */
            input[type=number] {
                -moz-appearance: textfield;
            }
        }
    </style>

</head>

<body>

    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>


            @if (Route::currentRouteName() !== 'auth.login')
                {{-- Horizontal nav --}}
                <nav class="navbar navbar-expand-lg main-navbar sticky">
                    <div class="form-inline mr-auto">
                        <ul class="navbar-nav mr-3">
                            <li><a href="#" data-toggle="sidebar"
                                    class="nav-link nav-link-lg
                                  collapse-btn"> <i
                                        data-feather="align-justify"></i></a></li>
                            <li><a href="{{ route('page-acceuil') }}" target="blank" class="nav-link nav-link-lg">
                                    <i data-feather="eye"></i>
                                </a></li>
                            {{-- <li>
                                <form class="form-inline mr-auto">
                                    <div class="search-element">
                                        <input class="form-control" type="search" placeholder="Recherce"
                                            aria-label="Search" data-width="200">
                                        <button class="btn" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </li> --}}


                        </ul>
                    </div>
                    <ul class="navbar-nav navbar-right">
                        <!-- ========== Start notification orders ========== -->
                        <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                                class="nav-link nav-link-lg message-toggle"><i data-feather="bell" class="bell"></i>
                                <span id="orderNew" class="badge headerBadge1">
                                    {{ count($orders_new) }}</span> </a>
                            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                                <div class="dropdown-header">
                                    Notifications
                                    <div class="float-right">
                                        <a href="/admin/order/changeState?cs=all" class="btn btn-link">Tous
                                            confirmer</a>
                                    </div>
                                </div>
                                <div class="dropdown-list-content dropdown-list-message dropdown-list-order">
                                    @foreach ($orders_new as $item)
                                        <a href="{{ route('order.show', $item['id']) }}" class="dropdown-item"> <span
                                                class="dropdown-item-avatar
											text-dark"> <i
                                                    data-feather="shopping-cart"></i>
                                            </span> <span class="dropdown-item-desc"> <span
                                                    class="message-user text-info fw-bold">{{ $item['code'] }}</span>
                                                <span
                                                    class="badge {{ $item['status'] == 'attente' ? 'badge-primary' : ($item['status'] == 'livrée' ? 'badge-success' : ($item['status'] == 'confirmée' ? 'badge-info' : ($item['status'] == 'annulée' ? 'badge-danger' : ($item['status'] == 'precommande' ? 'badge-dark' : '')))) }} text-white p-1 px-3"><i
                                                        class="{{ $item['status'] == 'precommande' ? 'fa fa-clock' : '' }}"></i>
                                                    {{ $item['status'] }}
                                                </span> <span class="time text-capitalize text-dark fst-italic">
                                                    {{ \Carbon\Carbon::parse($item['created_at'])->diffForHumans() }}</span>
                                            </span>
                                        </a>
                                    @endforeach


                                </div>
                                <div class="dropdown-footer text-center">
                                    <a href="{{ route('order.index') }}">Voir tous les commandes en attentes <i
                                            class="fas fa-chevron-right"></i></a>
                                </div>
                            </div>
                        </li>
                        <!-- ========== End notification orders ========== -->


                        <!-- ========== Start notification birthdays ========== -->
                        <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                                class="nav-link nav-link-lg message-toggle"><i data-feather="gift" class="bell"></i>
                                <span class="badge headerBadge2">
                                    {{ count($user_birthday) + count($user_upcoming_birthday) }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-list dropdown-menu-right pullDown">
                                <div class="dropdown-header">
                                    Notifications Anniversaire
                                </div>
                                <div class="dropdown-list-content dropdown-list-message">
                                    @if (count($user_birthday) > 0)
                                        <h6>Anniversaire du jour </h6>
                                        @foreach ($user_birthday as $item)
                                            <a href="{{ route('user.detail', $item['id']) }}" class="dropdown-item"
                                                style="">
                                                <span class="dropdown-item-avatar text-dark"> <i
                                                        data-feather="user"></i>

                                                </span> <span class="dropdown-item-desc "> <span
                                                        class="message-user  fw-bold">Ajourd'hui c'est
                                                        l'anniversaire de <b class=""
                                                            style="color:rgb(232, 11, 170)">{{ $item['name'] }}</b>
                                                    </span>
                                            </a>
                                        @endforeach
                                    @endif
                                    <hr>
                                    @if (count($user_upcoming_birthday) > 0)
                                        <h6>Anniversaire à venir </h6>

                                        @foreach ($user_upcoming_birthday as $item)
                                            <p>
                                                <a href="{{ route('user.detail', $item['id']) }}"
                                                    class="dropdown-item"
                                                    style="background-color: rgb(255, 249, 249)">
                                                    <span class="dropdown-item-avatar text-dark"><i
                                                            data-feather="user"></i> </span>

                                                    <span
                                                        class="dropdown-item-desc text-dark message-user text-dark fw-bold">
                                                        Dans {{ $item['notify_birthday'] }} jours est l'anniversaire de
                                                        <b style="color: rgb(8, 0, 122)"> {{ $item['name'] }} </b>
                                                    </span>

                                                </a>
                                            </p>
                                        @endforeach
                                    @endif


                                </div>

                            </div>
                        </li>
                        <!-- ========== End notification orders ========== -->

                        @auth
                            <li class="dropdown"><a href="#" data-toggle="dropdown"
                                    class="nav-link dropdown-toggle nav-link-lg nav-link-user"> <img alt="image"
                                        src="{{ asset('admin/assets/img/user.png') }}" class="user-img-radious-style">
                                    <span class="d-sm-none d-lg-inline-block"></span></a>
                                <div class="dropdown-menu dropdown-menu-right pullDown">
                                    <div class="dropdown-title">{{ Auth::user()->name }}
                                        <span>{{ Auth::user()->role }} </span>
                                    </div>
                                    <a href="{{ route('user.edit', Auth::user()->id) }}" class="dropdown-item has-icon">
                                        <i class="far
                              fa-user"></i> Profile
                                    </a>

                                    {{-- <a href="{{route('setting.index')}}" class="dropdown-item has-icon"> <i class="fas fa-cog"></i>
                            Parametres
                        </a> --}}
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('user.logout') }}" class="dropdown-item has-icon text-danger"> <i
                                            class="fas fa-sign-out-alt"></i>
                                        Se deconnecter
                                    </a>
                                </div>
                            </li>
                        @endauth
                    </ul>
                </nav>
                {{-- End Horizontal nav --}}



                {{-- Vertical nav --}}
                <div class="main-sidebar sidebar-style-2">
                    <aside id="sidebar-wrapper">
                        <div class="sidebar-brand mb-2">
                            <a href="{{ route('dashboard.index') }}">
                                <span class="logo-name">
                                    <img src="{{ asset('site/assets/img/custom/logo.png') }}" width="80"
                                        class="m-auto" alt="">
                                </span>
                            </a>
                        </div>
                        <ul class="sidebar-menu">

                            {{-- Tableau de bord --}}
                            <li class="dropdown {{ Route::is('dashboard.*') ? 'active' : '' }}">
                                <a href="{{ route('dashboard.index') }}" class="nav-link">
                                    <i data-feather="monitor"></i><span>Tableau de bord</span>
                                </a>
                            </li>

                            {{-- Produits (accès rapide) --}}
                            {{-- <li class="{{ Route::is('product.*') ? 'active' : '' }}">
                                <a href="{{ route('product.index') }}" class="nav-link">
                                    <i data-feather="box"></i><span>Produits</span>
                                </a>
                            </li> --}}

                            {{-- Catalogue : Catégories, Sous-catégories, Produits --}}
                            <li class="dropdown">
                                <a href="#"
                                    class="menu-toggle nav-link has-dropdown
                                    {{ Route::is('category.*') || Route::is('sub-category.*') || Route::is('product.*') ? 'active' : '' }}">
                                    <i data-feather="package"></i><span>Catalogue</span>
                                </a>
                                <ul
                                    class="dropdown-menu {{ Route::is('category.*') || Route::is('sub-category.*') || Route::is('product.*') ? 'show' : '' }}">
                                    @role(['developpeur', 'administrateur'])
                                        <li class="nav-item {{ Route::is('category.*') ? 'active' : '' }}">
                                            <a href="{{ route('category.index') }}" class="nav-link">Catégories</a>
                                        </li>
                                        <li class="nav-item {{ Route::is('sub-category.*') ? 'active' : '' }}">
                                            <a href="{{ route('sub-category.index') }}"
                                                class="nav-link">Sous-catégories</a>
                                        </li>
                                    @endrole
                                    <li class="nav-item {{ Route::is('product.*') ? 'active' : '' }}">
                                        <a href="{{ route('product.index') }}" class="nav-link">Produits</a>
                                    </li>
                                </ul>
                            </li>

                            {{-- Ventes : Commandes, Clients, Coupons, Livraisons --}}
                            <li class="dropdown">
                                <a href="#"
                                    class="menu-toggle nav-link has-dropdown
                                    {{ Route::is('order.*') || Route::is('coupon.*') || Route::is('delivery.*') || Route::is('pos.*') || Route::is('client.*') ? 'active' : '' }}">
                                    <i data-feather="shopping-cart"></i><span>Ventes</span>
                                </a>
                                <ul
                                    class="dropdown-menu {{ Route::is('order.*') || Route::is('coupon.*') || Route::is('delivery.*') || Route::is('pos.*') || Route::is('client.*') ? 'show' : '' }}">
                                    <li class="nav-item {{ Route::is('pos.create') ? 'active' : '' }}">
                                        <a href="{{ route('pos.create') }}" class="nav-link"><i
                                                class="fas fa-plus-circle mr-1 text-success"></i> Nouvelle vente</a>
                                    </li>
                                    <li class="nav-item {{ Route::is('order.*') ? 'active' : '' }}">
                                        <a href="{{ route('order.index') }}" class="nav-link">Commandes</a>
                                    </li>
                                    <li class="nav-item {{ Route::is('client.*') ? 'active' : '' }}">
                                        <a href="{{ route('client.list') }}" class="nav-link">Clients</a>
                                    </li>
                                    <li class="nav-item {{ Route::is('coupon.*') ? 'active' : '' }}">
                                        <a href="{{ route('coupon.index') }}" class="nav-link">Coupons de
                                            réduction</a>
                                    </li>
                                    <li class="nav-item {{ Route::is('delivery.*') ? 'active' : '' }}">
                                        <a href="{{ route('delivery.index') }}" class="nav-link">Livraisons</a>
                                    </li>
                                </ul>
                            </li>

                            {{-- Caisse --}}
                            @role(['developpeur', 'administrateur'])
                                <li class="dropdown">
                                    <a href="#"
                                        class="menu-toggle nav-link has-dropdown
                                    {{ Route::is('caisse.*') || Route::is('payment-method.*') ? 'active' : '' }}">
                                        <i data-feather="credit-card"></i><span>Caisse</span>
                                    </a>
                                    <ul
                                        class="dropdown-menu {{ Route::is('caisse.*') || Route::is('payment-method.*') ? 'show' : '' }}">
                                        <li class="nav-item {{ Route::is('caisse.*') ? 'active' : '' }}">
                                            <a href="{{ route('caisse.index') }}" class="nav-link">Gestion des
                                                caisses</a>
                                        </li>
                                        <li class="nav-item {{ Route::is('payment-method.*') ? 'active' : '' }}">
                                            <a href="{{ route('payment-method.index') }}" class="nav-link">Moyens de
                                                paiement</a>
                                        </li>
                                    </ul>
                                </li>
                            @endrole

                            {{-- Contenu : Médias, Témoignages --}}
                            <li class="dropdown">
                                <a href="#"
                                    class="menu-toggle nav-link has-dropdown
                                    {{ Route::is('publicite.*') || Route::is('temoignage.*') ? 'active' : '' }}">
                                    <i data-feather="image"></i><span>Contenu</span>
                                </a>
                                <ul
                                    class="dropdown-menu {{ Route::is('publicite.*') || Route::is('temoignage.*') ? 'show' : '' }}">
                                    <li class="nav-item {{ Route::is('publicite.*') ? 'active' : '' }}">
                                        <a href="{{ route('publicite.index') }}" class="nav-link">Médias /
                                            Publicités</a>
                                    </li>
                                    <li class="nav-item {{ Route::is('temoignage.*') ? 'active' : '' }}">
                                        <a href="{{ route('temoignage.index') }}" class="nav-link">Témoignages</a>
                                    </li>
                                </ul>
                            </li>

                            {{-- Dépenses --}}
                            <li class="dropdown">
                                <a href="#"
                                    class="menu-toggle nav-link has-dropdown
                                    {{ Route::is('categorie-depense.*') || Route::is('libelle-depense.*') || Route::is('depense.*') ? 'active' : '' }}">
                                    <i data-feather="dollar-sign"></i><span>Dépenses</span>
                                </a>
                                <ul
                                    class="dropdown-menu {{ Route::is('categorie-depense.*') || Route::is('libelle-depense.*') || Route::is('depense.*') ? 'show' : '' }}">
                                    <li class="nav-item {{ Route::is('categorie-depense.*') ? 'active' : '' }}">
                                        <a href="{{ route('categorie-depense.index') }}"
                                            class="nav-link">Catégories</a>
                                    </li>
                                    <li class="nav-item {{ Route::is('libelle-depense.*') ? 'active' : '' }}">
                                        <a href="{{ route('libelle-depense.index') }}" class="nav-link">Libellés</a>
                                    </li>
                                    <li class="nav-item {{ Route::is('depense.*') ? 'active' : '' }}">
                                        <a href="{{ route('depense.index') }}" class="nav-link">Dépenses</a>
                                    </li>
                                </ul>
                            </li>

                            {{-- Rapport --}}
                            <li class="dropdown">
                                <a href="#"
                                    class="menu-toggle nav-link has-dropdown
                                    {{ Route::is('rapport.*') ? 'active' : '' }}">
                                    <i data-feather="activity"></i><span>Rapports</span>
                                </a>
                                <ul class="dropdown-menu {{ Route::is('rapport.*') ? 'show' : '' }}">
                                    <li class="nav-item {{ Route::is('rapport.exploitation') ? 'active' : '' }}">
                                        <a href="{{ route('rapport.exploitation') }}" class="nav-link">Compte
                                            d'exploitation</a>
                                    </li>
                                    <li class="nav-item {{ Route::is('rapport.vente') ? 'active' : '' }}">
                                        <a href="{{ route('rapport.vente') }}" class="nav-link">Ventes</a>
                                    </li>
                                </ul>
                            </li>

                            {{-- Administrateurs --}}
                            @role(['developpeur', 'administrateur', 'gestionnaire'])
                                <li class="dropdown">
                                    <a href="#"
                                        class="menu-toggle nav-link has-dropdown
                                        {{ Route::is('user.*') || Route::is('role.*') ? 'active' : '' }}">
                                        <i data-feather="lock"></i><span>Administration</span>
                                    </a>
                                    <ul
                                        class="dropdown-menu {{ Route::is('user.*') || Route::is('role.*') ? 'show' : '' }}">
                                        <li class="nav-item {{ Route::is('user.*') ? 'active' : '' }}">
                                            <a class="nav-link" href="{{ route('user.list') }}">Équipe / Admins</a>
                                        </li>
                                        @role(['developpeur', 'administrateur'])
                                            <li class="nav-item {{ Route::is('role.*') ? 'active' : '' }}">
                                                <a class="nav-link" href="{{ route('role.index') }}">Rôles</a>
                                            </li>
                                        @endrole
                                    </ul>
                                </li>
                            @endrole

                        </ul>
                    </aside>
                </div>
                {{-- End Vertical nav --}}
            @endif



            <!-- Main Content -->
            <div class="main-content">
                <section class="">
                    @include('admin.components.breadcrumb')
                    @yield('content')
                </section>
            </div>
            <!-- End Main Content -->






            <footer class="main-footer">
                <div class="text-center">
                    <a href="https://ticafrique.com/" target="_blank">Conçu par Ticafrique</a></a>
                </div>
                <div class="footer-right">
                </div>
            </footer>
        </div>
    </div>
    <!-- General JS Scripts -->

    <script src="{{ asset('admin/assets/js/app.min.js') }}"></script>
    <!-- JS Libraies -->
    {{-- <script src="{{asset('admin/assets/bundles/cleave-js/dist/cleave.min.js')}}"></script> --}}
    <script src="{{ asset('admin/assets/bundles/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin/assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('admin/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}">
    </script>



    <script src="{{ asset('admin/assets/bundles/prism/prism.js') }}"></script>

    <script src="{{ asset('admin/assets/bundles/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('admin/assets/bundles/upload-preview/assets/js/jquery.uploadPreview.min.js') }}"></script>
    <script src="{{ asset('admin/assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>

    <!-- Page Specific JS File -->
    <script src="{{ asset('admin/assets/js/page/index.js') }}"></script>
    <script src="{{ asset('admin/assets/bundles/datatables/export-tables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin/assets/bundles/datatables/export-tables/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('admin/assets/bundles/datatables/export-tables/jszip.min.js') }}"></script>
    <script src="{{ asset('admin/assets/bundles/datatables/export-tables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('admin/assets/bundles/datatables/export-tables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('admin/assets/bundles/datatables/export-tables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/page/datatables.js') }}"></script>
    {{-- <script src="{{ asset('admin/assets/js/page/create-post.js') }}"></script> --}}
    {{-- <script src="{{ asset('admin/assets/js/page/forms-advanced-forms.js') }}"></script> --}}
    <script src="{{ asset('admin/assets/bundles/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/page/sweetalert.js') }}"></script>
    <script src="{{ asset('admin/assets/bundles/echart/echarts.js') }}"></script>
    <script src="{{ asset('admin/assets/js/page/chart-amchart.js') }}"></script>
    <script src="{{ asset('admin/assets/bundles/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('admin/assets/bundles/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('admin/assets/js/page/widget-chart.js') }}"></script>
    <script src="{{ asset('admin/assets/js/page/widget-data.js') }}"></script>

    @yield('script')
    @stack('js')
    <script src="{{ asset('admin/assets/js/scripts.js') }}"></script>

    <!-- Template JS File -->
    <!-- Custom JS File -->
    <script src="{{ asset('admin/assets/js/custom.js') }}"></script>
    <!-- CDN JS File -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" />


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js">
    </script>






    <script>
        function checkNewOrders() {
            $.ajax({
                url: "/dashboard/check-new-order",
                method: "GET",
                success: function(data) {
                    if (data.count > 0) {
                        // Mettre à jour le badge
                        $("#orderNew").text(data.count);

                        // Vider et reconstruire la liste
                        const listContainer = $(".dropdown-list-order");
                        listContainer.empty();

                        data.orders_new.forEach(function(order) {
                            const orderHtml = `
                        <a href="/admin/order/show/${order.id}" class="dropdown-item">
                            <span class="dropdown-item-avatar text-dark">
                                <i data-feather="shopping-cart"></i>
                            </span>
                            <span class="dropdown-item-desc">
                                <span class="message-user text-info fw-bold">${order.code}</span>
                                <span class="badge badge-primary text-white p-1 px-3">${order.status}</span>
                                <span class="time text-capitalize text-dark fst-italic">
                                    ${order.created_at} - ${order.created_at_human}
                                </span>
                            </span>
                        </a>
                    `;
                            listContainer.append(orderHtml);
                        });

                        // 🔊 Jouer le son directement
                        const alertSound = new Audio("{{ asset('audio/notify_bell.wav') }}");
                        alertSound.play();
                    } else {
                        $("#orderNew").text("");
                    }
                },
                error: function(xhr) {
                    console.error("Erreur lors de la récupération des commandes.");
                },
            });
        }

        // Vérifier les nouvelles commandes toutes les 15 secondes
        setInterval(checkNewOrders, 5000);
    </script>

</body>



</html>
