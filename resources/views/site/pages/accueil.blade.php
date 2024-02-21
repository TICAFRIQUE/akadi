@extends('site.layouts.app')

@section('title', 'Accueil')

@section('content')
    @include('admin.components.validationMessage')

    <!-- ========== Start slider ========== -->
    @include('site.sections.slider3')
    <!-- ========== End slider ========== -->


    <!-- ========== Start categorie ========== -->
    @include('site.sections.categorie')
    <!-- ========== End categorie ========== -->


    <!-- ========== Start categorie-with-plats-recent ========== -->
    @include('site.sections.categorie-produits')
    <!-- ========== End categorie-with-plats-recent ========== -->

@endsection
