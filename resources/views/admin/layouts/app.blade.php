<!DOCTYPE html>
<html lang="fr">
<!-- index.html  21 Nov 2019 03:44:50 GMT -->

<head>
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
                            <li><a href="{{route('page-acceuil')}}" target="blank" class="nav-link nav-link-lg">
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
                            <li class="dropdown active">
                                <a href="/dashboard" class="nav-link"><i data-feather="monitor"></i><span>Tableau de
                                        bord</span></a>
                            </li>

                            @role(['developpeur', 'administrateur'])
                                <li class="dropdown">
                                    <a href="{{ route('category.index') }}" class="nav-link"><i
                                            data-feather="grid"></i><span>Categories</span></a>
                                </li>

                                <li class="dropdown">
                                    <a href="{{ route('sub-category.index') }}" class="nav-link"><i
                                            data-feather="grid"></i><span>Sous Categories</span></a>
                                </li>
                            @endrole


                            {{-- <li class="dropdown">
                                <a href="{{ route('collection.index') }}" class="nav-link"><i
                                        data-feather="grid"></i><span>Collections</span></a>
                            </li> --}}

                            <li class="dropdown">
                                <a href="{{ route('product.index') }}" class="nav-link"><i
                                        data-feather="shopping-bag"></i><span>Produits</span></a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                        data-feather="shopping-cart"></i><span>Commandes</span></a>
                                <ul class="dropdown-menu">
                                    <li><a class="nav-link" href="/admin/order?d=jour">Jour</a></li>
                                    <li><a class="nav-link" href="/admin/order?s=attente">Attentes</a></li>
                                    <li><a class="nav-link" href="/admin/order?s=confirmée">Confirmée</a></li>
                                    <li><a class="nav-link" href="/admin/order?s=livrée">Livrées</a></li>
                                    <li><a class="nav-link" href="/admin/order?s=annulée">Annulées</a></li>

                                    <li><a class="nav-link" href="{{ route('order.index') }}">Toutes les
                                            commandes</a></li>

                                </ul>
                            </li>

                            @role(['developpeur', 'administrateur', 'gestionnaire'])
                                <li class="dropdown">
                                    <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                            data-feather="users"></i><span>Utilisateurs</span></a>
                                    <ul class="dropdown-menu">
                                        @foreach ($roles as $item)
                                            <li><a class="nav-link" href="/admin/auth?user={{ $item['name'] }}">
                                                    {{ $item['name'] }} </a></li>
                                        @endforeach

                                        <li><a class="nav-link" href="{{ route('user.list') }}">Liste des
                                                utilisateurs</a></li>
                                    </ul>

                                </li>
                            @endrole


                            <li class="dropdown">
                                <a href="{{ route('delivery.index') }}" class="nav-link"><i
                                        data-feather="truck"></i><span>Livraisons</span></a>
                            </li>

                            <li class="dropdown">
                                <a href="{{ route('publicite.index') }}" class="nav-link"><i
                                        data-feather="image"></i><span>Publicité / Slider</span></a>
                            </li>

                            <li class="dropdown">
                                <a href="{{ route('temoignage.index') }}" class="nav-link"><i
                                        data-feather="message-square"></i><span>Témoignages</span></a>
                            </li>

                            {{-- <li class="dropdown">
                                <a href="#" class="menu-toggle nav-link has-dropdown"><i
                                        data-feather="settings"></i><span>Parametres</span></a>
                                <ul class="dropdown-menu">
                                    <li><a class="nav-link" href="avatar.html">Rôles</a></li>
                                    <li><a class="nav-link" href="card.html">Publicités</a></li>

                                </ul>
                            </li> --}}
                        </ul>
                    </aside>
                </div>
                {{-- End Vertical nav --}}
            @endif



            <!-- Main Content -->
            <div class="main-content">
                @include('admin.components.breadcrumb')
                @yield('content')
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
    @yield('script')
    @stack('js')
    <script src="{{ asset('admin/assets/js/scripts.js') }}"></script>

    <!-- Template JS File -->
    <!-- Custom JS File -->
    <script src="{{ asset('admin/assets/js/custom.js') }}"></script>
    <!-- CDN JS File -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css" />


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js">
    </script>


</body>


<!-- index.html  21 Nov 2019 03:47:04 GMT -->

</html>
