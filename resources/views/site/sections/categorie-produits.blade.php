 <section class="space">

     <div class="container">
         <div class="title-area text-center">
             <span class="sub-title">
                 <img class="icon" src="{{ asset('site/assets/img/icon/title_icon.svg') }}" alt="icon">
                 Nouveautés
             </span>
             <h2 class="sec-title">Decouvrez nos plats du jours <span class="font-style text-theme">Akadi</span>
             </h2>

             <div class="tab-menu1 filter-menu-active">
                 {{-- <button data-filter="*" class="active" type="button">All Menus</button> --}}
                 @foreach ($categories as $item)
                     <button data-filter=".cat{{ $item['id'] }}" type="button"> {{ $item['name'] }} </button>
                 @endforeach
             </div>
         </div>

         <div class="row gy-40 filter-active">
             @foreach ($categories as $categorie)
                 @foreach ($categorie['products'] as $plat)
                     <div class="col-xl-3 col-lg-4 col-sm-6 filter-item cat{{ $plat['pivot']['category_id'] }}">
                         <div class="th-product product_style1">
                             <div class="product-img">
                                 <img src="{{ asset($plat->getFirstMediaUrl('product_image')) }}" alt="Product Image">
                             </div>
                             <div class="product-content">
                                 <a href="shop.html" class="category"> {{$categorie['name']}} </a>
                                 <div class="product-rating">
                                     <div class="star-rating" role="img" aria-label="Rated 4.00 out of 5">
                                         <span style="width:75%">Rated <strong class="rating">4.00</strong> out of
                                             5</span>
                                     </div>
                                     (4.00)
                                 </div>
                                 <h3 class="product-title"><a href="{{route('detail-produit', $plat['slug'])}}">{{ $plat['title'] }}</a></h3>
                                 <span class="price"> {{ number_format($plat['price'], 0) }} FCFA <del></del></span>
                                 <div class="actions">
                            <button class="th-btn"> <i class="fal fa-cart-plus"></i> </button>

                                     {{-- <a href="#QuickView" class="icon-btn popup-content"><i class="fal fa-eye"></i></a>
                                     <a href="cart.html" class="icon-btn"><i class="fal fa-cart-plus"></i></a>
                                     <a href="wishlist.html" class="icon-btn"><i class="fal fa-heart"></i></a>
                                  --}}
                                    </div>
                             </div>
                         </div>

                     </div>
                 @endforeach
             @endforeach
         </div>
         {{-- <div class="text-center mt-5">
             <a href="?pl={{$plat['pivot']['category_id']}}" class="th-btn">Voir tous les plats</a>
         </div> --}}
     </div>

 </section>
