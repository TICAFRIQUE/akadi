<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Akadi - @yield('title')</title>
    <meta name="author" content="Themeholy">
    <meta name="description" content="Pizzan - Fast Food & Restaurant HTML Template">
    <meta name="keywords" content="Pizzan - Fast Food & Restaurant HTML Template">
    <meta name="robots" content="INDEX,FOLLOW">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicons - Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" sizes="57x57" href="assets/img/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="assets/img/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/img/favicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/favicons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/img/favicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/img/favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="assets/img/favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/img/favicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/favicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/img/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/img/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicons/favicon-16x16.png">
    <link rel="manifest" href="{{ asset('site/assets/img/favicons/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/img/favicons/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <!--==============================
 Google Fonts
 ============================== -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lobster+Two:wght@400;700&family=Roboto:wght@100;300;400;500;700&family=Rubik:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!--==============================
 All CSS File
 ============================== -->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('site/assets/css/bootstrap.min.css') }}">
    <!-- Fontawesome Icon -->
    <link rel="stylesheet" href="{{ asset('site/assets/css/fontawesome.min.css') }}">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="{{ asset('site/assets/css/magnific-popup.min.css') }}">
    <!-- Layerslider -->
    <link rel="stylesheet" href="{{ asset('site/assets/css/layerslider.min.css') }}">
    <!-- Datepicker -->
    <link rel="stylesheet" href="{{ asset('site/assets/css/jquery.datetimepicker.min.css') }}">
    <!-- Slick Slider -->
    <link rel="stylesheet" href="{{ asset('site/assets/css/slick.min.css') }}">
    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="{{ asset('site/assets/css/style.css') }}">

</head>

<body>

    <!--==============================
     Preloader
  ==============================-->
    <div class="preloader ">
        {{-- <button class="th-btn style3 preloaderCls">Cancel Preloader </button> --}}
        <div class="preloader-inner">
            <span class="loader"></span>
        </div>
    </div><!--==============================
Product Lightbox
==============================-->



    <div class="popup-search-box d-lg-block">
        <button class="searchClose"><i class="fal fa-times"></i></button>
        <form action="{{ route('recherche') }}" method="GET">
            @csrf
            <input type="text" name="q" placeholder="Rechercher un produit">
            <button type="submit"><i class="fal fa-search"></i></button>
        </form>
    </div>


    <!-- ========== Start menu main and mobile ========== -->
    @include('site.sections.menu2')
    <!-- ========== End menu main and mobile ========== -->



    







    <!-- ========== Start content ========== -->
    @yield('content')
    <!-- ========== End content ========== -->







    <footer class="footer-wrapper footer-layout5" data-bg-src="">
        <div class="widget-area">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-md-6 col-xl-3">
                        <div class="widget footer-widget">
                            <h3 class="widget_title">A propos de AKADI</h3>
                            <div class="th-widget-about">
                                <p class="about-text">
                                    Akadi, est un restaurant en ligne spécialisé en poulet fumé crée par une jeune dame
                                    dynamique qui
                                    évolue dans le domaine de la relation clientèle.
                                </p>
                                <div class="th-social">
                                    <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                                    <a href="https://www.twitter.com/"><i class="fab fa-twitter"></i></a>
                                    <a href="https://www.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="https://www.whatsapp.com/"><i class="fab fa-whatsapp"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-auto">
                        <div class="widget widget_nav_menu footer-widget">
                            <h3 class="widget_title">MENU</h3>
                            <div class="menu-all-pages-container">
                                <ul class="menu">
                                    @foreach ($categories as $item)
                                        <li><a href="/produit?categorie={{ $item['id'] }}">{{ $item['name'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-6 col-xl-auto">
                        <div class="widget footer-widget">
                            <h3 class="widget_title">Recent Posts</h3>
                            <div class="recent-post-wrap">
                                <div class="recent-post">
                                    <div class="media-img">
                                        <a href="blog-details.html"><img
                                                src="assets/img/update_2/blog/recent-post-2-1.jpg"
                                                alt="Blog Image"></a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="post-title"><a class="text-inherit" href="blog-details.html">10
                                                Reasons To Do A Digital Detox Challenge</a></h4>
                                        <div class="recent-post-meta">
                                            <a href="blog.html"><i class="fal fa-calendar-days"></i>21 June,
                                                2023</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="recent-post">
                                    <div class="media-img">
                                        <a href="blog-details.html"><img
                                                src="assets/img/update_2/blog/recent-post-2-2.jpg"
                                                alt="Blog Image"></a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="post-title"><a class="text-inherit" href="blog-details.html">New
                                                Restaurant Town Our Ple Award
                                                Contract</a></h4>
                                        <div class="recent-post-meta">
                                            <a href="blog.html"><i class="fal fa-calendar-days"></i>22 June,
                                                2023</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-md-6 col-xl-auto">
                        <div class="widget footer-widget">
                            <h3 class="widget_title">Nous contacter</h3>
                            <div class="th-widget-contact">
                                <div class="info-box">
                                    <div class="info-box_icon">
                                        <i class="fal fa-location-dot"></i>
                                    </div>
                                    <p class="info-box_text">Abidjan , Plateau Dokui</p>
                                </div>
                                <div class="info-box">
                                    <div class="info-box_icon">
                                        <i class="fal fa-mobile-button"></i>
                                    </div>
                                    <p class="info-box_text">
                                        <a href="tel:+11234567890" class="info-box_link">+(225) 07 58 83 83 38</a>
                                        {{-- <a href="tel:+10987654321" class="info-box_link">+(1) 098 765 4321</a> --}}
                                    </p>
                                </div>
                                <div class="info-box">
                                    <div class="info-box_icon">
                                        <i class="fal fa-envelope"></i>
                                    </div>
                                    <p class="info-box_text">
                                        <a href="mailto:info@pizzan.com" class="info-box_link">info@akadi.com</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright-wrap border-top">
                <div class="row justify-content-between align-items-center">
                    <div class="col-lg-6">
                        <p class="copyright-text">Copyright <i class="fal fa-copyright"></i> @php
                        echo date('Y'); @endphp Akadi
                            All Rights
                            Reserved.</p>
                    </div>

                </div>
            </div>
        </div>
    </footer>



    <!-- Scroll To Top -->
    <div class="scroll-top">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98"
                style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
            </path>
        </svg>
    </div>

    <!--==============================
    All Js File
============================== -->
    <!-- Jquery -->
    <script src="{{ asset('site/assets/js/vendor/jquery-3.6.0.min.js') }}"></script>
    <!-- Slick Slider -->
    <script src="{{ asset('site/assets/js/slick.min.js') }}"></script>
    <!-- Layerslider -->
    <script src="{{ asset('site/assets/js/layerslider.utils.js') }}"></script>
    <script src="{{ asset('site/assets/js/layerslider.transitions.js') }}"></script>
    <script src="{{ asset('site/assets/js/layerslider.kreaturamedia.jquery.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('site/assets/js/bootstrap.min.js') }}"></script>
    <!-- Magnific Popup -->
    <script src="{{ asset('site/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <!-- Datepicker -->
    <script src="{{ asset('site/assets/js/jquery.datetimepicker.min.js') }}"></script>
    <!-- Counter Up -->
    <script src="{{ asset('site/assets/js/jquery.counterup.min.js') }}"></script>
    <!-- Isotope Filter -->
    <script src="{{ asset('site/assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('site/assets/js/isotope.pkgd.min.js') }}"></script>
    <!-- Main Js File -->
    <script src="{{ asset('site/assets/js/main.js') }}"></script>

</body>

</html>
