@extends('site.layouts.app')

@section('title', 'Accueil')

@section('content')




    {{-- @includeWhen(!Auth::check(), 'site.sections.popup-register' ) --}}

    <!-- ========== Start bandeau menu du jour ========== -->
    @include('site.sections.bandeau-menu-jour')
    <!-- ========== End bandeau menu du jour ========== -->

    <!-- ========== Start slider ========== -->
    @include('site.sections.slider')
    <!-- ========== End slider ========== -->

    <!-- ========== Start annonce ========== -->
    @include('site.sections.pub.annonce')
    <!-- ========== End annonce ========== -->



    {{-- @include('admin.components.validationMessage') --}}

    <!-- ========== Start categorie ========== -->
    @include('site.sections.categorie')
    <!-- ========== End categorie ========== -->


    <!-- ========== Start top promo ========== -->
    @include('site.sections.pub.top_promo')
    <!-- ========== End top promo ========== -->

    <!-- ========== Start menu du jour ========== -->
    @include('site.sections.menu-du-jour')
    <!-- ========== End menu du jour ========== -->


    <!-- ========== Start categorie-with-plats-recent ========== -->
    @include('site.sections.categorie-produits')
    <!-- ========== End categorie-with-plats-recent ========== -->

    <!-- ========== Start publicite small card ========== -->
    @include('site.sections.pub.pack')
    <!-- ========== End publicite small card ========== -->

    <!-- ========== Start A propos de akadi ========== -->
    @include('site.sections.a-propos')
    <!-- ========== End A propos de akadi ========== -->

    <!-- ========== Start video and facebook ========== -->
    @include('site.sections.pub.video_and_facebook')
    <!-- ========== End video and facebook ========== -->


    <!-- ========== Start feedback ========== -->
    @include('site.sections.feedback')
    <!-- ========== End feedback ========== -->




   


@endsection
