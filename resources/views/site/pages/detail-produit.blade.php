@extends('site.layouts.app')

@section('title', 'Detail-' . $product['title'])
@section('description', $product['description'])


@section('content')
    @includeWhen(!Auth::check(), 'site.sections.popup-register')

    <!--============================== Breadcumb  ============================== -->

    <div class="breadcumb-wrapper " data-bg-src="">
        <div class="container z-index-common">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Detail</h1>
                <ul class="breadcumb-menu">
                    <li><a href="javascript:history.go(-1)">Retour</a></li>
                    <li><a href="i{{ route('page-acceuil') }}">Accueil</a></li>
                    <li>Detail</li>
                    <li>{{ $product['title'] }}</li>

                </ul>
            </div>
        </div>
    </div><!--==============================Product Details==============================-->

    <section class="th-product-wrapper product-details space-top space-extra-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="product-thumb-area">
                        <div class="product-thumb-tab" data-asnavfor=".product-big-img">
                            @foreach ($product->getMedia('product_image') as $item)
                                <div class="tab-btn active">
                                    <img src="{{ $item->getUrl() }}" alt="Product Thumb">
                                </div>
                            @endforeach
                        </div>
                        <div class="product-big-img th-carousel" data-slide-show="1" data-md-slide-show="1"
                            data-fade="true">
                            @foreach ($product->getMedia('product_image') as $item)
                                <div class="col-auto">
                                    <div class="img"><img src="{{ $item->getUrl() }}" alt="Product Image">
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="product-about">
                        {{-- <div class="product-rating">
                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span
                                    style="width:100%">Rated <strong class="rating">5.00</strong> out of 5 based on <span
                                        class="rating">1</span> customer rating</span></div>
                            <a href="shop-details.html" class="woocommerce-review-link">(<span class="count">3</span>
                                customer reviews)</a>
                        </div> --}}
                        <h4 class="product-title mb-4"> {{ $product['title'] }} </h4>
                        @if ($product['montant_remise'] != null && $product['status_remise'] == 'en_cours')
                            @php
                                $new_price = $product['price'] - $product['montant_remise']; //prix promo
                            @endphp
                            <span class="bg-danger text-white p-2 fw-bold">- {{ $product['pourcentage_remise'] }} %</span>

                            <span class="price fs-2">
                                {{ number_format($new_price, 0, ',', ' ') }} FCFA
                                <del class="text-dark"> {{ number_format($product['price'], 0, ',', ' ') }} FCFA
                                </del>
                            </span>
                        @else
                            <span class="price fs-2"> {{ number_format($product['price'], 0, ',', ' ') }}
                                FCFA
                                <del></del>
                            </span>
                        @endif

                        <div class="product_meta">
                            <span class="sku_wrapper">Description: <span class="sku"> {!! $product['description'] !!}
                                </span></span>
                            <span class="sku_wrapper">SKU: <span class="sku"> {{ $product['id'] }} </span></span>
                            <span class="posted_in">Categorie: <a
                                    href="/produit?categorie={{ $product['categories'][0]['id'] }}" rel="tag">
                                    {{ $product['categories'][0]['name'] }} </a></span>
                        </div>

                        <div class="actions">
                            {{-- <div class="quantity">
                                <input type="number" class="qty-input" step="1" min="1" max="100"
                                    name="quantity" value="1" title="Qty">
                                <button class="quantity-plus qty-btn"><i class="far fa-chevron-up"></i></button>
                                <button class="quantity-minus qty-btn"><i class="far fa-chevron-down"></i></button>
                            </div> --}}
                            <button class="th-btn addCart" data-id="{{ $product['id'] }}">Ajouter au panier</button>
                            {{-- <a class="icon-btn" href="wishlist.html"><i class="fal fa-heart"></i></a> --}}
                        </div>

                    </div>
                </div>
            </div>
            {{-- <ul class="nav product-tab-style1" id="productTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link th-btn rounded-2" id="description-tab" data-bs-toggle="tab" href="#description"
                        role="tab" aria-controls="description" aria-selected="false">Description</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link th-btn rounded-2 active" id="reviews-tab" data-bs-toggle="tab" href="#reviews"
                        role="tab" aria-controls="reviews" aria-selected="true">Commentaires</a>
                </li>
            </ul> --}}
            <div class="tab-content" id="productTabContent">
                {{-- <div class="tab-pane fade" id="description" role="tabpanel" aria-labelledby="description-tab">
                    <p>
                        {!! $product['description'] !!}
                    </p>

                </div> --}}

                <!-- ========== Start commentaire  ========== -->
                @include('site.sections.commentaire-produit')
                <!-- ========== End commentaire  ========== -->



            </div>

            <!-- ========== Start produit similaire ========== -->
            <div class="space-extra-top mb-30">
                <div class="title-area text-center">
                    <h2 class="sec-title"> {{ count($product_related) > 0 ? 'Dans la même catégorie' : '' }} </h2>
                </div>
                <div class="row th-carousel" data-slide-show="4" data-lg-slide-show="3" data-md-slide-show="2"
                    data-sm-slide-show="2" data-xs-slide-show="1" data-arrows="true">

                    @foreach ($product_related as $item)
                        <div class="col-xl-3 col-lg-4 col-sm-6">
                            <div class="th-menu">
                                <div class="">
                                    <a href="{{ route('detail-produit', $item['slug']) }}">
                                        <img src="{{ asset($item->getFirstMediaUrl('product_image')) }}"
                                            alt="Product Image">
                                    </a>

                                    @if ($item['status_remise'] == 'en_cours')
                                        <div class="th-menu_discount">
                                            <span class="sale"> - {{ $item['pourcentage_remise'] }} %</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="th-menu-content">
                                    <span class=" text-dark fw-bold"><a href="{{ route('detail-produit', $item['slug']) }}"
                                            class="text-dark"
                                            style="font-size: 14px">{{ Str::limit($item['title'], 30, '...') }}</a>
                                    </span>
                                    {{-- <p class="th-menu_desc">Barbecue Italian cuisine pizza</p> --}}
                                    @if ($item['montant_remise'] != null && $item['status_remise'] == 'en_cours')
                                        @php
                                            $new_price = $item['price'] - $item['montant_remise']; //prix promo
                                        @endphp
                                        <span class="th-menu_price">
                                            {{ number_format($new_price, 0, ',', ' ') }} FCFA
                                            <del class="text-dark"> {{ number_format($item['price'], 0, ',', ' ') }} FCFA
                                            </del>
                                        </span>
                                    @else
                                        <span class="th-menu_price"> {{ number_format($item['price'], 0, ',', ' ') }}
                                            FCFA
                                            <del></del>
                                        </span>
                                    @endif
                                </div>

                                <div class="product-action mt-4">
                                    <button class="btn btn-danger addCart" data-id="{{ $item['id'] }}"><i
                                            class="fal fa-cart-plus "></i> Ajouter au panier</button>
                                </div>
                                <div class="fire"><img src="{{ asset('site/assets/img/update_2/shape/fire.png') }}"
                                        alt="shape"></div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
            <!-- ========== End produit similaire ========== -->


        </div>
    </section>

    @include('site.components.ajouter-au-panier')
@endsection
