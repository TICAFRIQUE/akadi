<!--==============================
Categorie and products
==============================-->
<section class="space bg-smoke2" data-bg-src="">
    <div class="container">
        <div class="title-area text-center">
            <span class="sub-title">
                <img class="icon" src="{{ asset('site/assets/img/icon/title_icon.svg') }}" alt="icon">
                Decouvrir nos menus
            </span>
            <h2 class="sec-title">Tu testes, tu restes <span class="font-style text-theme"> toujours</span></h2>

        </div>

        <div class="tab-content">
            <!-- Single item -->
            <div class="tab-pane fade show active" id="nav-one" role="tabpanel" aria-labelledby="nav-one-tab">
                <div class="tab-menu2 filter-menu-active">
                    <button data-filter="*" class="active" type="button">
                        <img src="{{ asset('site/assets/img/icon/menu_1_1.svg') }}" alt="Icon">
                        Tous les menus
                    </button>

                    @foreach ($categories as $item)
                        <button data-filter=".cat{{ $item['id'] }}" type="button">
                            <img src="{{ $item->getFirstMediaUrl('category_image') }}" width="60%" alt="Icon">
                            {{ $item['name'] }}
                        </button>
                    @endforeach
                </div>
                <div class="row gy-4 filter-active">
                    @foreach ($categories as $categorie)
                        @foreach ($categorie['products'] as $plats)
                            <div class="col-lg-6 filter-item cat{{ $plats['pivot']['category_id'] }}">
                                <div class="th-product list-view">
                                    <div class="product-img">
                                        <img src="{{ asset($plats->getFirstMediaUrl('product_image')) }}"
                                            alt="Product Image">
                                    </div>
                                    <div class="product-content">
                                        {{-- <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                            <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span
                                                    class="rating">1</span> customer rating</span>
                                        </div> --}}
                                        <h3 class="product-title"><a
                                                href="{{ route('detail-produit', $plats['slug']) }}">{{ $plats['title'] }}</a>
                                        </h3>
                                        @if ($plats['subcategorie'])
                                            <a href="/produit?sous-categorie={{ $plats['subcategorie']['id'] }}"
                                                class="category text-danger">{{ $plats['subcategorie'] ? $plats['subcategorie']['name'] : $plats['categories'][0]['name'] }}</a>
                                        @else
                                            <a href="/produit?categorie={{ $plats['categories'][0]['id'] }}"
                                                class="category text-danger">{{ $plats['subcategorie'] ? $plats['subcategorie']['name'] : $plats['categories'][0]['name'] }}</a>
                                        @endif
                                        <p class="product-text"> {!! substr(strip_tags($plats->description), 0, 100) !!}.... </p>
                                        <span class="price"> {{ number_format($plats['price'], 0 , ',', ' ') }} FCFA
                                            <del></del></span>

                                        <div class="actions">
                                            <a href="{{ route('detail-produit', $plats['slug']) }}" class="icon-btn"><i
                                                    class="far fa-eye"></i></a>
                                            <button class="icon-btn"><i class="far fa-cart-plus addCart"
                                                    data-id="{{ $plats['id'] }}"></i></button>
                                            {{-- <a href="wishlist.html" class="icon-btn"><i class="far fa-heart"></i></a> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach

                </div>
                <div class="text-center mt-5">
                    <a href="{{ route('liste-produit') }}" class="th-btn">Voir tous nos produits</a>
                </div>
            </div>

        </div>
    </div>
    @include('site.components.ajouter-au-panier')

</section>
<!--==============================
Categorie and products
==============================-->
