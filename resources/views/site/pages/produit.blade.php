@extends('site.layouts.app')

@section('title', 'Liste des plats')

@section('content')

    <div class="breadcumb-wrapper " data-bg-src="">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h3 class="breadcumb-title">Menu </h3>
                <ul class="breadcumb-menu">
                    <li><a class="" href="{{ route('page-acceuil') }}">Accueil</a></li>
                    <li><a class="" href="#">Categorie</a></li>
                    <li class="active">
                        @if (request('categorie') && count($product) > 0)
                            {{ $product[0]['categories'][0]['name'] }}
                        @elseif (request('sous-categorie') && count($product) > 0)
                            {{ $product[0]['subcategorie']['name'] }}
                        @else
                            Liste de tous les plats
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>



    <section class="th-product-wrapper space-top space-extra-bottom">
        <div class="container">

            <div class="row gy-40">
                @foreach ($product as $item)
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="th-product">
                            <div class="product-img">
                                <img src="{{ asset($item->getFirstMediaUrl('product_image')) }}" alt="Product Image">
                            </div>
                            <div class="product-content">
                                <a href="/produit?categorie={{ $item['categories'][0]['id'] }}"
                                    class="category">{{ $item['categories'][0]['name'] }}</a>
                                {{-- <div class="product-rating">
                                <div class="star-rating" role="img" aria-label="Rated 4.00 out of 5">
                                    <span style="width:75%">Rated <strong class="rating">4.00</strong> out of 5</span>
                                </div>
                                (4.00)
                            </div> --}}
                                <h3 class="product-title"><a href="{{ route('detail-produit', $item['slug']) }}">
                                        {{ $item['title'] }} </a></h3>
                                <span class="price"> {{ number_format($item['price'], 0) }} FCFA <del></del></span>
                                <div class="actions">
                                    {{-- <a href="" class=""><i class="fal fa-eye"></i>Ajouter</a> --}}
                                    <button class="btn btn-outline-danger addCart" data-id="{{ $item['id'] }}"><i
                                            class="fal fa-cart-plus "></i> Ajouter au panier</button>

                                    {{-- <a href="wishlist.html" class="icon-btn"><i class="fal fa-heart"></i></a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            {{-- <div class="th-pagination text-center pt-50">
                <ul>
                    <li><a href="blog.html">1</a></li>
                    <li><a href="blog.html">2</a></li>
                    <li><a href="blog.html">3</a></li>
                    <li><a href="blog.html"><i class="far fa-arrow-right"></i></a></li>
                </ul>
            </div> --}}
        </div>
    </section>

    @include('site.components.ajouter-au-panier')
@endsection
