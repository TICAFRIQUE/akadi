<!doctype html>
<html class="no-js" lang="{{ Config::get('app.locale') }}">

<head>

    <!-- ========== Start google analytics ========== -->
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ELMED1GRWL"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', 'G-ELMED1GRWL');
    </script>
    <!-- ========== End google analytics ========== -->


    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Akadi - @yield('title')</title>
    <meta name="author" content="Ticafrique">
    {{-- <meta name="description" content="Akadi-@yield('description')"> --}}
    <meta name="og:keywords" content="restaurant, poulet, fumé, livraison, Abidjan, Côte d'Ivoire">

    <meta property="og:title" content="Akadi - @yield('title')" />
    <meta property="og:description" content="Akadi-@yield('description')" />
    <meta property="og:image" content="@yield('image')" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
    <meta property="og:site_name" content="Akadi" />
    <meta property="og:locale" content="fr_FR" />
    <meta property="og:locale:alternate" content="en_US" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:image:alt" content="Akadi" />
    <meta name="robots" content="INDEX,FOLLOW">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicons - Place favicon.ico in the root directory -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('admin/assets/img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('admin/assets/img/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('admin/assets/img/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('admin/assets/img/favicon/site.webmanifest') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
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

    <style>
        /* ── Palette Akadi (logo) ── */
        :root {
            --ak-orange:  #f85d05;   /* orange arc logo – CTA primaire  */
            --ak-red:     #eb0029;   /* rouge logo – secondaire/alertes */
            --ak-dark:    #0a0000;   /* noir chaud – fonds sombres       */
            --ak-orange-hover: #d44d00;
            --ak-red-hover:    #c4001f;
            --ak-gradient: linear-gradient(90deg, #eb0029, #f85d05);
        }

        /* ── Breadcrumb global Akadi ── */
        .ak-breadcrumb {
            background: linear-gradient(135deg, #1a0000 0%, #3d0010 100%);
            padding: 28px 0;
            position: relative;
            overflow: hidden;
        }
        .ak-breadcrumb::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #eb0029, #f85d05);
        }
        .ak-breadcrumb-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: #fff;
            margin: 0 0 6px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .ak-breadcrumb-icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            background: rgba(255,255,255,.1);
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem;
            flex-shrink: 0;
        }
        .ak-breadcrumb-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
            margin: 0; padding: 0;
            flex-wrap: wrap;
        }
        .ak-breadcrumb-nav li { font-size: .8rem; color: rgba(255,255,255,.5); }
        .ak-breadcrumb-nav li a {
            color: rgba(255,255,255,.7);
            text-decoration: none;
            transition: color .15s;
        }
        .ak-breadcrumb-nav li a:hover { color: var(--ak-orange, #f85d05); }
        .ak-breadcrumb-nav li.active { color: var(--ak-orange, #f85d05); font-weight: 600; }
        .ak-breadcrumb-sep { color: rgba(255,255,255,.3); font-size: .65rem; }
        .ak-breadcrumb-badge {
            font-size: .82rem;
            font-weight: 700;
            background: #eb0029;
            color: #fff;
            padding: 2px 10px;
            border-radius: 50px;
        }

        /* Empêcher le scroll horizontal */
        html,
        body {
            overflow-x: hidden !important;
            max-width: 100%;
        }

        body {
            position: relative;
        }

        /* Fix pour les containers */
        .container,
        .row {
            max-width: 100%;
        }

        /* Optimisation carousel cat\u00e9gories */
        .category-carousel {
            opacity: 0;
            transition: opacity 0.3s ease-in;
            overflow: hidden;
        }

        .category-carousel[style*="visible"] {
            opacity: 1;
        }

        /* Pr\u00e9venir le layout shift du carousel */
        .category-item {
            min-height: 200px;
        }

        .slick-slide {
            outline: none;
            margin: 0 10px;
        }

        /* Fix pour \u00e9viter l'overflow des sliders */
        .slick-list {
            overflow: hidden !important;
            margin: 0 -10px;
        }

        .slick-track {
            display: flex !important;
            align-items: stretch;
        }

        /* Cacher les fl\u00e8ches du carousel pendant le chargement */
        .slick-arrow {
            opacity: 0;
            transition: opacity 0.3s ease-in 0.3s;
        }

        .slick-initialized .slick-arrow {
            opacity: 1;
        }

        /* Optimisation images */
        img {
            max-width: 100%;
            height: auto;
        }

        img[loading="lazy"] {
            background: #f0f0f0;
        }

        /* Emp\u00eacher les images de causer un overflow */
        .category-border_img img {
            width: 100%;
            height: auto;
            display: block;
        }

        .whatsapp-float {
            position: fixed;
            bottom: 90px;
            right: 16px;
            background-color: #25d366;
            color: white;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.25);
            font-size: 18px;
            transition: transform 0.3s;
            text-decoration: none;
        }

        .facebook-float {
            position: fixed;
            bottom: 144px;
            right: 16px;
            background-color: #1877f2;
            color: white;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.25);
            font-size: 16px;
            transition: transform 0.3s;
            text-decoration: none;
        }

        .facebook-float:hover {
            transform: scale(1.1);
            color: white;
            text-decoration: none;
        }

        /* Menu mobile en bas */
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 999;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.3);
            padding: 10px 0;
            display: none;
        }

        .mobile-nav-items {
            display: flex;
            justify-content: space-around;
            align-items: center;
            max-width: 100%;
            margin: 0 auto;
            padding: 0 10px;
        }

        .mobile-nav-item {
            flex: 1;
            text-align: center;
            position: relative;
        }

        .mobile-nav-item a,
        .mobile-nav-item button {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s ease;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 8px;
            width: 100%;
        }

        .mobile-nav-item i {
            font-size: 22px;
            margin-bottom: 4px;
            transition: transform 0.3s ease;
        }

        .mobile-nav-item span {
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .mobile-nav-item a:hover i,
        .mobile-nav-item button:hover i {
            transform: scale(1.15);
            color: var(--theme-color, #ff6b35);
        }

        .mobile-nav-item .badge {
            position: absolute;
            top: 0;
            right: 25%;
            background-color: #e74c3c;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        /* Menu déroulant mobile pour le compte */
        .mobile-account-dropdown {
            position: fixed;
            bottom: 65px;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.2);
            border-radius: 20px 20px 0 0;
            padding: 20px;
            transform: translateY(100%);
            transition: transform 0.3s ease;
            z-index: 998;
            display: none;
        }

        .mobile-account-dropdown.active {
            transform: translateY(0);
            display: block;
        }

        .mobile-account-dropdown .dropdown-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .mobile-account-dropdown .dropdown-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .mobile-account-dropdown .close-dropdown {
            background: transparent;
            border: none;
            font-size: 24px;
            color: #999;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }

        .mobile-account-dropdown .dropdown-menu-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .mobile-account-dropdown .dropdown-menu-list li {
            margin-bottom: 10px;
        }

        .mobile-account-dropdown .dropdown-menu-list a {
            display: flex;
            align-items: center;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            text-decoration: none;
            color: #333;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .mobile-account-dropdown .dropdown-menu-list a:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .mobile-account-dropdown .dropdown-menu-list a.logout {
            color: #dc3545;
        }

        .mobile-account-dropdown .dropdown-menu-list a i {
            margin-right: 15px;
            font-size: 20px;
            width: 24px;
            text-align: center;
        }

        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 997;
            display: none;
        }

        .mobile-overlay.active {
            display: block;
        }

        /* Remplacé par .ak-bottom-nav dans menu.blade.php */
        @media (max-width: 991px) {
            .mobile-bottom-nav {
                display: none !important;
            }

            .mobile-account-dropdown {
                display: none;
            }

            .mobile-account-dropdown.active {
                display: block;
            }

            /* Ajuster WhatsApp et Facebook float pour ne pas chevaucher */
            .whatsapp-float {
                bottom: 140px;
                right: 12px;
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            .facebook-float {
                bottom: 190px;
                right: 12px;
                width: 40px;
                height: 40px;
                font-size: 14px;
            }

            /* Ajouter padding en bas pour éviter que le contenu soit caché */
            body {
                padding-bottom: 65px;
            }

            /* Scroll to top en dessous de WhatsApp */
            .scroll-top {
                bottom: 85px !important;
                right: 15px !important;
            }

            /* Bien positionner le hamburger et le logo */
            .menu-area .row {
                justify-content: space-between !important;
            }

            /* Layout mobile : Hamburger | Logo | Panier */
            .mobile-header-wrapper {
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                width: 100%;
                padding: 0 10px;
            }

            .mobile-hamburger-left {
                order: 1;
                flex: 0 0 auto;
            }

            .mobile-logo-center {
                order: 2;
                flex: 1;
                display: flex !important;
                justify-content: center !important;
                position: absolute;
                left: 50%;
                transform: translateX(-50%);
                pointer-events: none;
            }

            .mobile-logo-center .header-logo {
                pointer-events: auto;
            }

            .mobile-cart-right {
                order: 3;
                flex: 0 0 auto;
                z-index: 10;
            }

            .mobile-cart-right .simple-icon {
                position: relative;
                font-size: 20px;
                color: var(--theme-color, #333);
            }

            .mobile-cart-right .badge {
                position: absolute;
                top: -8px;
                right: -10px;
                background-color: #e74c3c;
                color: white;
                font-size: 10px;
                font-weight: bold;
                padding: 2px 6px;
                border-radius: 10px;
                min-width: 18px;
            }

            .header-button {
                order: 3;
                flex: 0 0 auto;
                z-index: 10;
            }

            .th-menu-toggle {
                font-size: 26px;
                padding: 8px;
                background: transparent;
                border: none;
                color: var(--theme-color, #333);
                cursor: pointer;
                transition: all 0.3s ease;
                margin: 0;
                line-height: 1;
            }

            .th-menu-toggle:hover {
                color: var(--theme-color, #ff6b35);
            }

            .header-logo {
                display: flex;
                align-items: center;
            }

            .header-logo img {
                width: 50px !important;
                height: 50px !important;
                object-fit: contain;
            }
        }

        /* Sur desktop */
        @media (min-width: 992px) {

            .mobile-hamburger-left,
            .mobile-cart-right,
            .mobile-account-dropdown,
            .mobile-overlay {
                display: none !important;
            }

            .mobile-logo-center {
                position: static !important;
                transform: none !important;
            }

            .header-logo img {
                width: 60px !important;
                height: 60px !important;
            }
        }

        /* Animation au chargement */
        @keyframes slideUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .mobile-bottom-nav {
            animation: slideUp 0.4s ease-out;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            color: white;
        }

        .whatsapp-icon {
            font-size: 28px;
        }

        /* ── Footer Akadi ── */
        .ak-footer {
            background: #100000;
            color: rgba(255,255,255,.7);
            font-size: .88rem;
            position: relative;
            overflow: hidden;
        }
        .ak-footer::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #eb0029, #f85d05, #eb0029);
        }
        .ak-footer-main { padding: 60px 0 40px; }
        .ak-footer-brand { max-width: 280px; }
        .ak-footer-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            text-decoration: none;
        }
        .ak-footer-logo img { width: 52px; height: 52px; }
        .ak-footer-logo-name {
            font-size: 1.2rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: .04em;
        }
        .ak-footer-logo-name span { color: #eb0029; }
        .ak-footer-desc {
            font-size: .84rem;
            line-height: 1.75;
            color: rgba(255,255,255,.55);
            margin-bottom: 20px;
        }
        .ak-footer-socials { display: flex; gap: 8px; }
        .ak-footer-social {
            width: 36px; height: 36px;
            border-radius: 50%;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,.65);
            font-size: .8rem;
            text-decoration: none;
            transition: all .2s;
        }
        .ak-footer-social:hover { text-decoration: none; }
        .ak-footer-social.fb:hover { background: #1877f2; border-color: #1877f2; color: #fff; }
        .ak-footer-social.wa:hover { background: #25d366; border-color: #25d366; color: #fff; }
        .ak-footer-col-title {
            font-size: .78rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #fff;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .ak-footer-col-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,.1);
        }
        .ak-footer-links { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 10px; }
        .ak-footer-links li a {
            color: rgba(255,255,255,.55);
            text-decoration: none;
            font-size: .85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all .2s;
        }
        .ak-footer-links li a::before {
            content: '';
            width: 5px; height: 5px;
            border-radius: 50%;
            background: #eb0029;
            flex-shrink: 0;
            transition: transform .2s;
        }
        .ak-footer-links li a:hover { color: #f85d05; padding-left: 4px; }
        .ak-footer-links li a:hover::before { transform: scale(1.5); }
        .ak-footer-contacts { display: flex; flex-direction: column; gap: 14px; }
        .ak-footer-contact-item { display: flex; align-items: flex-start; gap: 12px; }
        .ak-footer-contact-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: rgba(235,0,41,.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #eb0029;
            font-size: .8rem;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .ak-footer-contact-text { color: rgba(255,255,255,.55); font-size: .83rem; line-height: 1.5; }
        .ak-footer-contact-text a { color: rgba(255,255,255,.7); text-decoration: none; transition: color .15s; }
        .ak-footer-contact-text a:hover { color: #f85d05; text-decoration: none; }
        .ak-footer-map { border-radius: 12px; overflow: hidden; height: 180px; border: 1px solid rgba(255,255,255,.08); }
        .ak-footer-map iframe { width: 100%; height: 100%; border: 0; display: block; }
        .ak-footer-divider { height: 1px; background: rgba(255,255,255,.08); margin: 0; }
        .ak-footer-bottom { padding: 18px 0; }
        .ak-footer-bottom-inner { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px; }
        .ak-footer-copyright { font-size: .78rem; color: rgba(255,255,255,.35); }
        .ak-footer-copyright a { color: rgba(255,255,255,.5); text-decoration: none; }
        .ak-footer-copyright a:hover { color: #f85d05; }
        .ak-footer-made { font-size: .75rem; color: rgba(255,255,255,.25); display: flex; align-items: center; gap: 5px; }
        .ak-footer-made i { color: #eb0029; }
        @media (max-width: 767px) {
            .ak-footer-main { padding: 40px 0 24px; }
            .ak-footer-brand { max-width: 100%; margin-bottom: 32px; }
        }
    </style>

</head>

<body>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=G-ELMED1GRWL" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->



    <div class="popup-search-box d-lg-block">
        <button class="searchClose"><i class="fal fa-times"></i></button>
        <form action="{{ route('recherche') }}" method="GET">
            @csrf
            <input type="text" name="q" placeholder="Rechercher un produit">
            <button type="submit"><i class="fal fa-search"></i></button>
        </form>
    </div>


    <!-- ========== Start menu main and mobile ========== -->
    @include('site.sections.menu')
    <!-- ========== End menu main and mobile ========== -->

    @include('sweetalert::alert')


    <!-- ========== Start content ========== -->
    @yield('content')
    <!-- ========== End content ========== -->



    <footer class="ak-footer">

        <div class="ak-footer-main">
            <div class="container">
                <div class="row gy-4">

                    {{-- Col 1 : Brand --}}
                    <div class="col-lg-3 col-md-6">
                        <div class="ak-footer-brand">
                            <a href="{{ route('page-acceuil') }}" class="ak-footer-logo">
                                <img src="{{ asset('site/assets/img/custom/AKADI.png') }}" alt="Akadi">
                                <span class="ak-footer-logo-name">AK<span>ADI</span></span>
                            </a>
                            <p class="ak-footer-desc">
                                Restaurant en ligne spécialisé dans le poulet fumé braisé et les saveurs locales revisitées. Livré directement chez vous.
                            </p>
                            <div class="ak-footer-socials">
                                <a href="https://www.facebook.com/CvraimentDoux" target="_blank" class="ak-footer-social fb" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://wa.me/+2250758838338?text=Bonjour Akadi, je peux passer ma commande ?" target="_blank" class="ak-footer-social wa" title="WhatsApp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Col 2 : Menu --}}
                    <div class="col-lg-2 col-md-6">
                        <h4 class="ak-footer-col-title">Menu</h4>
                        <ul class="ak-footer-links">
                            <li><a href="{{ route('page-acceuil') }}">Accueil</a></li>
                            @foreach ($categories as $item)
                                <li><a href="/produit?categorie={{ $item['id'] }}">{{ $item['name'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Col 3 : Contact --}}
                    <div class="col-lg-3 col-md-6">
                        <h4 class="ak-footer-col-title">Contact</h4>
                        <div class="ak-footer-contacts">
                            <div class="ak-footer-contact-item">
                                <div class="ak-footer-contact-icon"><i class="fas fa-map-marker-alt"></i></div>
                                <div class="ak-footer-contact-text">
                                    <a href="https://maps.app.goo.gl/sDP5zuFWbu4CLivk8" target="_blank">
                                        Angré, derrière la pharmacie Arcade, Abidjan
                                    </a>
                                </div>
                            </div>
                            <div class="ak-footer-contact-item">
                                <div class="ak-footer-contact-icon"><i class="fas fa-phone-alt"></i></div>
                                <div class="ak-footer-contact-text">
                                    <a href="tel:+2250758838338">+(225) 07 58 83 83 38</a>
                                </div>
                            </div>
                            <div class="ak-footer-contact-item">
                                <div class="ak-footer-contact-icon"><i class="fas fa-envelope"></i></div>
                                <div class="ak-footer-contact-text">
                                    <a href="mailto:info@akadi.ci">info@akadi.ci</a>
                                </div>
                            </div>
                            <div class="ak-footer-contact-item">
                                <div class="ak-footer-contact-icon"><i class="fas fa-clock"></i></div>
                                <div class="ak-footer-contact-text">
                                    Lun – Sam : <strong style="color:rgba(255,255,255,.75)">10h30 – 18h00</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Col 4 : Map --}}
                    <div class="col-lg-4 col-md-6">
                        <h4 class="ak-footer-col-title">Localisation</h4>
                        <div class="ak-footer-map">
                            <iframe
                                src="https://www.google.com/maps/embed/v1/place?q=AKADI+RESTAURANT,+Akadi+Restaurant,+Abidjan,+Côte+d’Ivoire&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8"
                                loading="lazy" allowfullscreen referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="ak-footer-divider"></div>

        <div class="ak-footer-bottom">
            <div class="container">
                <div class="ak-footer-bottom-inner">
                    <p class="ak-footer-copyright">
                        &copy; {{ date('Y') }} <a href="{{ route('page-acceuil') }}">Akadi Restaurant</a>. Tous droits réservés.
                    </p>
                    <p class="ak-footer-made">
                        Fait avec <i class="fas fa-heart"></i> à Abidjan
                    </p>
                </div>
            </div>
        </div>
    </footer>


    @php
        $numero = '2250758838338';
        $message = 'Bonjour, je souhaite avoir votre menu.';
        $urlWhatsapp = 'https://wa.me/' . $numero . '?text=' . urlencode($message);
    @endphp

    <a href="https://www.facebook.com/CvraimentDoux/?locale=fr_FR" class="facebook-float" target="_blank" title="Suivez-nous sur Facebook">
        <i class="fab fa-facebook-f"></i>
    </a>

    <a href="{{ $urlWhatsapp }}" class="whatsapp-float" target="_blank" title="Discuter sur WhatsApp">
        <i class="fab fa-whatsapp whatsapp-icon"></i>
    </a>

    <!-- Menu Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        <div class="mobile-nav-items">
            <!-- Accueil -->
            <div class="mobile-nav-item">
                <a href="{{ route('page-acceuil') }}">
                    <i class="far fa-home"></i>
                    <span>Accueil</span>
                </a>
            </div>

            <!-- Recherche -->
            <div class="mobile-nav-item">
                <button type="button" class="searchBoxToggler">
                    <i class="far fa-search"></i>
                    <span>Rechercher</span>
                </button>
            </div>

            <!-- Panier -->
            <div class="mobile-nav-item">
                <a href="{{ route('panier') }}">
                    <i class="far fa-cart-shopping"></i>
                    <span>Panier</span>
                    <span class="badge">{{ Session::get('totalQuantity') ?? '0' }}</span>
                </a>
            </div>

            <!-- Mon Compte -->
            <div class="mobile-nav-item">
                @guest
                    <a href="{{ route('login') }}">
                        <i class="far fa-user"></i>
                        <span>Compte</span>
                    </a>
                @endguest
                @auth
                    <button type="button" class="mobile-account-toggle">
                        <i class="far fa-user-check"></i>
                        <span>Compte</span>
                    </button>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Menu déroulant mobile pour le compte -->
    @auth
        <div class="mobile-overlay"></div>
        <div class="mobile-account-dropdown">
            <div class="dropdown-header">
                <h3>Mon Compte</h3>
                <button class="close-dropdown">&times;</button>
            </div>
            <ul class="dropdown-menu-list">
                <li>
                    <a href="{{ route('user-profil') }}">
                        <i class="far fa-user-circle"></i>
                        Mon Profil
                    </a>
                </li>
                <li>
                    <a href="{{ route('user-order') }}">
                        <i class="far fa-shopping-bag"></i>
                        Mes Commandes
                    </a>
                </li>
                <li>
                    <a href="{{ route('logout') }}" class="logout">
                        <i class="far fa-sign-out"></i>
                        Déconnexion
                    </a>
                </li>
            </ul>
        </div>
    @endauth



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

    <!-- Script pour le menu mobile compte -->
    <script>
        $(document).ready(function() {
            // Fix carousel catégories - afficher après initialisation complète
            $(window).on('load', function() {
                setTimeout(function() {
                    $('.category-carousel').css('visibility', 'visible').css('opacity', '1');
                }, 100);
            });

            // Fallback si window.load prend trop de temps
            setTimeout(function() {
                if ($('.category-carousel').css('visibility') === 'hidden') {
                    $('.category-carousel').css('visibility', 'visible').css('opacity', '1');
                }
            }, 1000);

            // Ouvrir le menu compte
            $('.mobile-account-toggle').on('click', function() {
                $('.mobile-account-dropdown').addClass('active');
                $('.mobile-overlay').addClass('active');
                $('body').css('overflow', 'hidden');
            });

            // Fermer le menu compte
            $('.close-dropdown, .mobile-overlay').on('click', function() {
                $('.mobile-account-dropdown').removeClass('active');
                $('.mobile-overlay').removeClass('active');
                $('body').css('overflow', 'auto');
            });
        });
    </script>


    @stack('js')

</body>

</html>
