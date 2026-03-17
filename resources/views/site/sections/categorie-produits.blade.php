<!--==============================
Categorie and products
==============================-->

{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"> --}}

<style>
    /* Amélioration des boutons produits */
    .actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
        flex-wrap: wrap;
    }

    .actions .icon-btn {
        flex: 1;
        min-width: 120px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        white-space: nowrap;
    }

    .actions .icon-btn i {
        font-size: 16px;
        transition: transform 0.3s ease;
    }

    .actions .icon-btn span {
        position: relative;
        z-index: 1;
    }

    /* Bouton Détail - Utilisation de la couleur principale du site */
    .actions .btn-detail {
        background: linear-gradient(135deg, #eb0029 0%, #c4001f 100%);
        color: white;
        box-shadow: 0 4px 10px rgba(235, 0, 41, 0.3);
    }

    .actions .btn-detail:hover {
        background: linear-gradient(135deg, #c4001f 0%, #eb0029 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(235, 0, 41, 0.4);
        color: white;
    }

    .actions .btn-detail:hover i {
        transform: scale(1.1);
    }

    /* Bouton Ajouter au panier - Utilisation de la couleur secondaire */
    .actions .btn-add-cart {
        background: linear-gradient(135deg, #ff9d2d 0%, #ff8800 100%);
        color: white;
        box-shadow: 0 4px 10px rgba(255, 157, 45, 0.3);
    }

    .actions .btn-add-cart:hover {
        background: linear-gradient(135deg, #ff8800 0%, #ff9d2d 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(255, 157, 45, 0.4);
        color: white;
    }

    .actions .btn-add-cart:hover i {
        transform: scale(1.1) rotate(15deg);
    }

    .actions .btn-add-cart:active {
        transform: scale(0.95);
    }

    /* Effet ripple */
    .actions .icon-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        transform: translate(-50%, -50%);
        transition: width 0.5s, height 0.5s;
    }

    .actions .icon-btn:hover::before {
        width: 300px;
        height: 300px;
    }

    /* Responsive Desktop */
    @media (min-width: 992px) {
        .actions {
            justify-content: flex-start;
        }
        
        .actions .icon-btn {
            flex: 0 1 auto;
        }
    }

    /* Responsive Mobile */
    @media (max-width: 991px) {
        .actions {
            flex-direction: row;
            gap: 8px;
        }
        
        .actions .icon-btn {
            flex: 1 1 calc(50% - 4px);
            padding: 10px 14px;
            font-size: 13px;
            min-width: 100px;
        }

        .actions .icon-btn i {
            font-size: 14px;
        }
    }

    @media (max-width: 576px) {
        .actions {
            flex-direction: column;
            gap: 8px;
        }
        
        .actions .icon-btn {
            width: 100%;
            flex: 1 1 100%;
            padding: 4px 16px;
            font-size: 13px;
        }

        .actions .icon-btn i {
            font-size: 14px;
        }

        .actions .icon-btn span {
            display: inline;
        }
    }
</style>

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
                        @foreach ($categorie['products'] as $product)
                            <div class="col-lg-6 filter-item cat{{ $product['pivot']['category_id'] }}">
                                <div class="th-product list-view">
                                    <div class="product-img">
                                        <a href="{{ route('detail-produit', $product['slug']) }}">
                                            <img src="{{ asset($product->getFirstMediaUrl('product_image')) }}"
                                                alt="Product Image">
                                        </a>
                                        {{-- @if ($product['montant_remise'])
                                            <div class="th-menu_discount w-10">
                                                <span class="sale">{{ $product['pourcentage_remise'] }}% OFF</span>
                                            </div>
                                        @endif --}}
                                    </div>
                                    <div class="product-content">
                                        {{-- <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                            <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span
                                                    class="rating">1</span> customer rating</span>
                                        </div> --}}
                                        <h3 class="product-title"><a
                                                href="{{ route('detail-produit', $product['slug']) }}">{{ $product['title'] }}</a>
                                        </h3>

                                        @if ($product['subcategorie'])
                                            <a href="/produit?sous-categorie={{ $product['subcategorie']['id'] }}"
                                                class="category text-danger">{{ $product['subcategorie'] ? $product['subcategorie']['name'] : $product['categories'][0]['name'] }}</a>
                                        @else
                                            <a href="/produit?categorie={{ $product['categories'][0]['id'] }}"
                                                class="category text-danger">{{ $product['subcategorie'] ? $product['subcategorie']['name'] : $product['categories'][0]['name'] }}</a>
                                        @endif
                                        <p class="product-text"> {!! substr(strip_tags($product->description), 0, 50) !!}.... </p>

                                        @if ($product['montant_remise'] != null && $product['status_remise'] == 'en_cours')
                                            <span class="price">
                                                @php
                                                    $new_price = $product['price'] - $product['montant_remise']; //prix promo
                                                @endphp
                                                {{ number_format($new_price, 0, ',', ' ') }} FCFA
                                                <del> {{ number_format($product['price'], 0, ',', ' ') }} FCFA </del>
                                            </span>
                                        @else
                                            <span class="price"> {{ number_format($product['price'], 0, ',', ' ') }}
                                                FCFA
                                                <del></del>
                                            </span>
                                        @endif


                                        <div class="actions">

                                            @php
                                                $numero = '2250758838338';
                                                $message =
                                                    'Bonjour, je souhaite commander le produit *' .
                                                    $product->title .
                                                    "*.\nVoici le lien : " .
                                                    route('detail-produit', $product->slug);
                                                $urlWhatsapp =
                                                    'https://wa.me/' . $numero . '?text=' . urlencode($message);
                                            @endphp

                                            <a href="{{ route('detail-produit', $product['slug']) }}"
                                                class="icon-btn btn-detail">
                                                <i class="far fa-eye"></i>
                                                <span>Détails</span>
                                            </a>
                                            
                                            <button class="icon-btn btn-add-cart addCart"
                                                data-id="{{ $product['id'] }}">
                                                <i class="fa fa-cart-plus"></i>
                                                <span class="addCart" data-id="{{$product['id']}}">Ajouter</span>
                                            </button>

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
