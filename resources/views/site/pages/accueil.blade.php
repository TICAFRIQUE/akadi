@extends('site.layouts.app')

@section('title', 'Accueil')

@section('content')
{{-- @includeWhen(!Auth::check(), 'site.sections.popup-register' ) --}}
    <!-- ========== Start slider ========== -->
    @include('site.sections.slider')
    <!-- ========== End slider ========== -->

    {{-- @include('admin.components.validationMessage') --}}

    <!-- ========== Start categorie ========== -->
    @include('site.sections.categorie')
    <!-- ========== End categorie ========== -->


    <!-- ========== Start top promo ========== -->
    @include('site.sections.pub.top_promo')
    <!-- ========== End top promo ========== -->


    <!-- ========== Start categorie-with-plats-recent ========== -->
    @include('site.sections.categorie-produits')
    <!-- ========== End categorie-with-plats-recent ========== -->

    <!-- ========== Start publicite small card ========== -->
    @include('site.sections.pub.small_card')
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
