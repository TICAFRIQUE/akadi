@extends('site.layouts.app')

@section('title', 'Liste des plats')

@section('content')
    @includeWhen(!Auth::check(), 'site.sections.popup-register')

    <div class="breadcumb-wrapper " data-bg-src="">
        <div class="container">
            <div class="breadcumb-content p-4">
                <h3 class="breadcumb-title">Menu </h3>
                <ul class="breadcumb-menu">
                    <li><a class="" href="{{ route('page-acceuil') }}">Accueil</a></li>
                    <li><a class="" href="#">Categorie</a></li>
                    <li class="active">
                        @if (request('categorie') || request('sous-categorie'))
                            {{ $name_category['name'] }}
                        @else
                            Liste de tous les produits
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>
    {{-- @include('site.sections.pub.infos') --}}



    <section class="th-product-wrapper space-extra-bottom">
        <div class="container">
            <div class="mt-2">
                <p class="d-flex justify-content-between">
                    @if (request('q'))
                        <span style="color: #ff7419;"> Recherche pour : <i class="text-dark">{{ request('q') }}</i> </span>
                        <span>
                            {{ count($product) }} produits trouvé(s).
                        </span>
                    @endif
                </p>
            </div>
            <div class="row gy-40">
                @foreach ($product as $item)
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="th-product">
                            <div class="product-img">
                                <a href="{{ route('detail-produit', $item['slug']) }}">
                                    <img src="{{ asset($item->getFirstMediaUrl('product_image')) }}" alt="Product Image">
                                </a>
                                @if ($item['status_remise'] == 'en_cours')
                                    <div class="th-menu_discount">
                                        <span class="sale">- {{ $item['pourcentage_remise'] }}% </span>
                                    </div>
                                @endif

                            </div>
                            <div class="product-content">
                                @if ($item['subcategorie'])
                                    <a href="/produit?sous-categorie={{ $item['subcategorie']['id'] }}"
                                        class="category">{{ $item['subcategorie'] ? $item['subcategorie']['name'] : $item['categories'][0]['name'] }}</a>
                                @else
                                    <a href="/produit?categorie={{ $item['categories'][0]['id'] }}"
                                        class="category">{{ $item['subcategorie'] ? $item['subcategorie']['name'] : $item['categories'][0]['name'] }}</a>
                                @endif

                                <span class="text-dark"><a href="{{ route('detail-produit', $item['slug']) }}"
                                        class="text-dark fw-bold" style="font-size: 14px">
                                        {{ Str::limit($item['title'], 30, '...') }}</a></span>

                                @if ($item['montant_remise'] != null && $item['status_remise'] == 'en_cours')
                                    @php
                                        $new_price = $item['price'] - $item['montant_remise']; //prix promo
                                    @endphp
                                    <span class="price"> {{ number_format($new_price, 0, ',', ' ') }}
                                        FCFA
                                        <del>
                                            {{ number_format($item['price'], 0, ',', ' ') }} FCFA </del>
                                    </span>
                                @else
                                    <span class="price"> {{ number_format($item['price'], 0, ',', ' ') }} FCFA
                                        <del></del>
                                    </span>
                                @endif
                                <div class="actions">
                                    {{-- <a href="" class=""><i class="fal fa-eye"></i>Ajouter</a> --}}
                                    <button class="btn btn-danger addCart" data-id="{{ $item['id'] }}"><i
                                            class="fal fa-cart-plus "></i> Ajouter au panier</button>

                                    @php
                                        $numero = '2250758838338'; // Format international sans "+"
                                        $message =
                                            'Bonjour, je souhaite commander le produit *' .
                                            $item->title .
                                            "*.\nVoici le lien : " .
                                            route('detail-produit', $item->slug);
                                        $urlWhatsapp = 'https://wa.me/' . $numero . '?text=' . urlencode($message);
                                    @endphp

                                    <br><a href="{{ $urlWhatsapp }}" target="_blank" class="btn btn-success mt-2">
                                        <i class="fab fa-whatsapp fs-5"></i> Commander par WhatsApp
                                    </a>

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
