@extends('site.layouts.app')

@section('title', 'Detail-' . $product['title'])

@section('content')
    <!--==============================
        Breadcumb
    ============================== -->
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
    </div><!--==============================
        Product Details
        ==============================-->
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
                <div class="col-lg-5 align-self-center">
                    <div class="product-about">
                        {{-- <div class="product-rating">
                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span
                                    style="width:100%">Rated <strong class="rating">5.00</strong> out of 5 based on <span
                                        class="rating">1</span> customer rating</span></div>
                            <a href="shop-details.html" class="woocommerce-review-link">(<span class="count">3</span>
                                customer reviews)</a>
                        </div> --}}
                        <h2 class="product-title"> {{$product['title']}} </h2>
                                 <span class="price"> {{ number_format($product['price'], 0) }} FCFA <del></del></span>
                        <div class="actions">
                            <div class="quantity">
                                <input type="number" class="qty-input" step="1" min="1" max="100"
                                    name="quantity" value="1" title="Qty">
                                <button class="quantity-plus qty-btn"><i class="far fa-chevron-up"></i></button>
                                <button class="quantity-minus qty-btn"><i class="far fa-chevron-down"></i></button>
                            </div>
                            <button class="th-btn">Ajouter au panier</button>
                            <a class="icon-btn" href="wishlist.html"><i class="fal fa-heart"></i></a>
                        </div>
                        <div class="product_meta">
                            <span class="sku_wrapper">SKU: <span class="sku"> {{$product['id']}} </span></span>
                            <span class="posted_in">Categorie: <a href="shop.html" rel="tag"> {{$product['categories'][0]['name']}} </a></span>
                        </div>
                       
                    </div>
                </div>
            </div>
            <ul class="nav product-tab-style1" id="productTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link th-btn rounded-2" id="description-tab" data-bs-toggle="tab" href="#description"
                        role="tab" aria-controls="description" aria-selected="false">Product Description</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link th-btn rounded-2 active" id="reviews-tab" data-bs-toggle="tab" href="#reviews"
                        role="tab" aria-controls="reviews" aria-selected="true">Customer Reviews</a>
                </li>
            </ul>
            <div class="tab-content" id="productTabContent">
                <div class="tab-pane fade" id="description" role="tabpanel" aria-labelledby="description-tab">
                    <p>Conveniently build adaptive users with front-end human capital. Appropriately unleash team building
                        technology for goal-oriented paradigms. Dynamically generate interoperable e-business
                        vis-a-visgoal-oriented value. Completely pursue fully tested content whereas multifunctional core
                        competencies. Progressively scale team driven process improvements before premier functionalities.
                        Holisticly cultivate intermandated methodologies rather than virtual technology. Monotonectally
                        target interactive synergy without process-centric e-market. Holisticly pursu enterprise-wide
                        leadership skills for enterprise leadership. Collaboratively underwhelm standardized expertise after
                        effective bandwidth. Conveniently productivate holistic collaboration and idea-sharing rather than
                        granular strategic theme areas. </p>
                    <p>Enthusiastically aggregate ethical systems for standardized mindshare. Energistically target resource
                        maximizing leadership skills without backward-compatible action items. Credibly impact alternative
                        expertise vis-a-vis economically sound results. Dynamically synergize empowered benefits through
                        functional partnerships. Authoritatively empower prospective infomediaries for interactive content.
                        Synergistically embrace 2.0 paradigms through professional intellectual capital. Interactively
                        strategize parallel growth strategies without out-of-the-box web services. Assertively reinvent
                        installed base.</p>
                </div>
                <div class="tab-pane fade show active" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                    <div class="woocommerce-Reviews">
                        <div class="th-comments-wrap ">
                            <ul class="comment-list">
                                <li class="review th-comment-item">
                                    <div class="th-post-comment">
                                        <div class="comment-avater">
                                            <img src="assets/img/blog/comment-author-3.jpg" alt="Comment Author">
                                        </div>
                                        <div class="comment-content">
                                            <h4 class="name">Mark Jack</h4>
                                            <span class="commented-on"><i class="fal fa-calendar-alt"></i>22 April,
                                                2023</span>
                                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                                <span style="width:100%">Rated <strong class="rating">5.00</strong> out of
                                                    5 based on <span class="rating">1</span> customer rating</span>
                                            </div>
                                            <p class="text">Completely build enabled web-readiness and distributed
                                                customer service. Proactively customize.</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="review th-comment-item">
                                    <div class="th-post-comment">
                                        <div class="comment-avater">
                                            <img src="assets/img/blog/comment-author-2.jpg" alt="Comment Author">
                                        </div>
                                        <div class="comment-content">
                                            <h4 class="name">Alexa Deo</h4>
                                            <span class="commented-on"><i class="fal fa-calendar-alt"></i>26 April,
                                                2023</span>
                                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                                <span style="width:100%">Rated <strong class="rating">5.00</strong> out of
                                                    5 based on <span class="rating">1</span> customer rating</span>
                                            </div>
                                            <p class="text">Completely build enabled web-readiness and distributed
                                                customer service. Proactively customize.</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="review th-comment-item">
                                    <div class="th-post-comment">
                                        <div class="comment-avater">
                                            <img src="assets/img/blog/comment-author-1.jpg" alt="Comment Author">
                                        </div>
                                        <div class="comment-content">
                                            <h4 class="name">Tara sing</h4>
                                            <span class="commented-on"><i class="fal fa-calendar-alt"></i>26 April,
                                                2023</span>
                                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                                <span style="width:100%">Rated <strong class="rating">5.00</strong> out of
                                                    5 based on <span class="rating">1</span> customer rating</span>
                                            </div>
                                            <p class="text">Completely build enabled web-readiness and distributed
                                                customer service. Proactively customize.</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="review th-comment-item">
                                    <div class="th-post-comment">
                                        <div class="comment-avater">
                                            <img src="assets/img/blog/comment-author-3.jpg" alt="Comment Author">
                                        </div>
                                        <div class="comment-content">
                                            <h4 class="name">Tara sing</h4>
                                            <span class="commented-on"><i class="fal fa-calendar-alt"></i>26 April,
                                                2023</span>
                                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                                <span style="width:100%">Rated <strong class="rating">5.00</strong> out of
                                                    5 based on <span class="rating">1</span> customer rating</span>
                                            </div>
                                            <p class="text">Completely build enabled web-readiness and distributed
                                                customer service. Proactively customize.</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div> <!-- Comment Form -->
                        <div class="th-comment-form ">
                            <div class="form-title">
                                <h3 class="blog-inner-title ">Add a review</h3>
                            </div>
                            <div class="row">
                                <div class="form-group rating-select d-flex align-items-center">
                                    <label>Your Rating</label>
                                    <p class="stars">
                                        <span>
                                            <a class="star-1" href="#">1</a>
                                            <a class="star-2" href="#">2</a>
                                            <a class="star-3" href="#">3</a>
                                            <a class="star-4" href="#">4</a>
                                            <a class="star-5" href="#">5</a>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-12 form-group">
                                    <textarea placeholder="Write a Message" class="form-control"></textarea>
                                    <i class="text-title far fa-pencil-alt"></i>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="text" placeholder="Your Name" class="form-control">
                                    <i class="text-title far fa-user"></i>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="text" placeholder="Your Email" class="form-control">
                                    <i class="text-title far fa-envelope"></i>
                                </div>
                                <div class="col-12 form-group">
                                    <input id="reviewcheck" name="reviewcheck" type="checkbox">
                                    <label for="reviewcheck">Save my name, email, and website in this browser for the next
                                        time I comment.<span class="checkmark"></span></label>
                                </div>
                                <div class="col-12 form-group mb-0">
                                    <button class="th-btn rounded-2">Post Review</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--==============================
      Related Product
      ==============================-->
            <div class="space-extra-top mb-30">
                <div class="title-area text-center">
                    <h2 class="sec-title">Related Products</h2>
                    <p class="sec-text ms-auto me-auto">Objectively pontificate quality models before intuitive
                        information. Dramatically recaptiualize multifunctional materials.</p>
                </div>
                <div class="row th-carousel" data-slide-show="4" data-lg-slide-show="3" data-md-slide-show="2"
                    data-sm-slide-show="2" data-xs-slide-show="1" data-arrows="true">

                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="th-menu">
                            <div class="th-menu_img">
                                <img class="spin" src="assets/img/update_2/menu/menu_3_1.png" alt="menu Image">
                                <div class="product-action">
                                    <a href="cart.html"><span class="action-text">Add To cart</span><span
                                            class="icon"><i class="far fa-cart-shopping"></i></span></a>
                                    <a href="wishlist.html"><span class="action-text">Wishlist</span><span
                                            class="icon"><i class="far fa-heart"></i></span></a>
                                    <a class="popup-content" href="#QuickView"><span class="action-text">Quick
                                            View</span><span class="icon"><i class="far fa-eye"></i></span></a>
                                </div>
                                <div class="th-menu_discount">
                                    <span class="sale">24% OFF</span>
                                </div>
                            </div>
                            <div class="th-menu-content">
                                <h3 class="th-menu_title"><a href="shop-details.html">Barbecue Sauce</a></h3>
                                <p class="th-menu_desc">Barbecue Italian cuisine pizza</p>
                                <span class="th-menu_price">$19.85</span>
                            </div>
                            <div class="fire"><img src="assets/img/update_2/shape/fire.png" alt="shape"></div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="th-menu">
                            <div class="th-menu_img">
                                <img class="spin" src="assets/img/update_2/menu/menu_3_2.png" alt="menu Image">
                                <div class="product-action">
                                    <a href="cart.html"><span class="action-text">Add To cart</span><span
                                            class="icon"><i class="far fa-cart-shopping"></i></span></a>
                                    <a href="wishlist.html"><span class="action-text">Wishlist</span><span
                                            class="icon"><i class="far fa-heart"></i></span></a>
                                    <a class="popup-content" href="#QuickView"><span class="action-text">Quick
                                            View</span><span class="icon"><i class="far fa-eye"></i></span></a>
                                </div>
                                <div class="th-menu_discount">
                                    <span class="sale">24% OFF</span>
                                </div>
                            </div>
                            <div class="th-menu-content">
                                <h3 class="th-menu_title"><a href="shop-details.html">Brief Pizza</a></h3>
                                <p class="th-menu_desc">Barbecue Italian cuisine pizza</p>
                                <span class="th-menu_price">$16.85</span>
                            </div>
                            <div class="fire"><img src="assets/img/update_2/shape/fire.png" alt="shape"></div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="th-menu">
                            <div class="th-menu_img">
                                <img class="spin" src="assets/img/update_2/menu/menu_3_3.png" alt="menu Image">
                                <div class="product-action">
                                    <a href="cart.html"><span class="action-text">Add To cart</span><span
                                            class="icon"><i class="far fa-cart-shopping"></i></span></a>
                                    <a href="wishlist.html"><span class="action-text">Wishlist</span><span
                                            class="icon"><i class="far fa-heart"></i></span></a>
                                    <a class="popup-content" href="#QuickView"><span class="action-text">Quick
                                            View</span><span class="icon"><i class="far fa-eye"></i></span></a>
                                </div>
                                <div class="th-menu_discount">
                                    <span class="sale">24% OFF</span>
                                </div>
                            </div>
                            <div class="th-menu-content">
                                <h3 class="th-menu_title"><a href="shop-details.html">Fresh Sea Foods</a></h3>
                                <p class="th-menu_desc">Barbecue Italian cuisine pizza</p>
                                <span class="th-menu_price">$17.85</span>
                            </div>
                            <div class="fire"><img src="assets/img/update_2/shape/fire.png" alt="shape"></div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="th-menu">
                            <div class="th-menu_img">
                                <img class="spin" src="assets/img/update_2/menu/menu_3_4.png" alt="menu Image">
                                <div class="product-action">
                                    <a href="cart.html"><span class="action-text">Add To cart</span><span
                                            class="icon"><i class="far fa-cart-shopping"></i></span></a>
                                    <a href="wishlist.html"><span class="action-text">Wishlist</span><span
                                            class="icon"><i class="far fa-heart"></i></span></a>
                                    <a class="popup-content" href="#QuickView"><span class="action-text">Quick
                                            View</span><span class="icon"><i class="far fa-eye"></i></span></a>
                                </div>
                                <div class="th-menu_discount">
                                    <span class="sale">24% OFF</span>
                                </div>
                            </div>
                            <div class="th-menu-content">
                                <h3 class="th-menu_title"><a href="shop-details.html">Sashimi Fish</a></h3>
                                <p class="th-menu_desc">Barbecue Italian cuisine pizza</p>
                                <span class="th-menu_price">$12.85</span>
                            </div>
                            <div class="fire"><img src="assets/img/update_2/shape/fire.png" alt="shape"></div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="th-menu">
                            <div class="th-menu_img">
                                <img class="spin" src="assets/img/update_2/menu/menu_3_5.png" alt="menu Image">
                                <div class="product-action">
                                    <a href="cart.html"><span class="action-text">Add To cart</span><span
                                            class="icon"><i class="far fa-cart-shopping"></i></span></a>
                                    <a href="wishlist.html"><span class="action-text">Wishlist</span><span
                                            class="icon"><i class="far fa-heart"></i></span></a>
                                    <a class="popup-content" href="#QuickView"><span class="action-text">Quick
                                            View</span><span class="icon"><i class="far fa-eye"></i></span></a>
                                </div>
                                <div class="th-menu_discount">
                                    <span class="sale">24% OFF</span>
                                </div>
                            </div>
                            <div class="th-menu-content">
                                <h3 class="th-menu_title"><a href="shop-details.html">Chicken Pasta</a></h3>
                                <p class="th-menu_desc">Barbecue Italian cuisine pizza</p>
                                <span class="th-menu_price">$10.85</span>
                            </div>
                            <div class="fire"><img src="assets/img/update_2/shape/fire.png" alt="shape"></div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="th-menu">
                            <div class="th-menu_img">
                                <img class="spin" src="assets/img/update_2/menu/menu_3_6.png" alt="menu Image">
                                <div class="product-action">
                                    <a href="cart.html"><span class="action-text">Add To cart</span><span
                                            class="icon"><i class="far fa-cart-shopping"></i></span></a>
                                    <a href="wishlist.html"><span class="action-text">Wishlist</span><span
                                            class="icon"><i class="far fa-heart"></i></span></a>
                                    <a class="popup-content" href="#QuickView"><span class="action-text">Quick
                                            View</span><span class="icon"><i class="far fa-eye"></i></span></a>
                                </div>
                                <div class="th-menu_discount">
                                    <span class="sale">24% OFF</span>
                                </div>
                            </div>
                            <div class="th-menu-content">
                                <h3 class="th-menu_title"><a href="shop-details.html">Beef Biryani</a></h3>
                                <p class="th-menu_desc">Barbecue Italian cuisine pizza</p>
                                <span class="th-menu_price">$19.85</span>
                            </div>
                            <div class="fire"><img src="assets/img/update_2/shape/fire.png" alt="shape"></div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="th-menu">
                            <div class="th-menu_img">
                                <img class="spin" src="assets/img/update_2/menu/menu_3_7.png" alt="menu Image">
                                <div class="product-action">
                                    <a href="cart.html"><span class="action-text">Add To cart</span><span
                                            class="icon"><i class="far fa-cart-shopping"></i></span></a>
                                    <a href="wishlist.html"><span class="action-text">Wishlist</span><span
                                            class="icon"><i class="far fa-heart"></i></span></a>
                                    <a class="popup-content" href="#QuickView"><span class="action-text">Quick
                                            View</span><span class="icon"><i class="far fa-eye"></i></span></a>
                                </div>
                                <div class="th-menu_discount">
                                    <span class="sale">24% OFF</span>
                                </div>
                            </div>
                            <div class="th-menu-content">
                                <h3 class="th-menu_title"><a href="shop-details.html">Natural Vegetable</a></h3>
                                <p class="th-menu_desc">Barbecue Italian cuisine pizza</p>
                                <span class="th-menu_price">$16.85</span>
                            </div>
                            <div class="fire"><img src="assets/img/update_2/shape/fire.png" alt="shape"></div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="th-menu">
                            <div class="th-menu_img">
                                <img class="spin" src="assets/img/update_2/menu/menu_3_8.png" alt="menu Image">
                                <div class="product-action">
                                    <a href="cart.html"><span class="action-text">Add To cart</span><span
                                            class="icon"><i class="far fa-cart-shopping"></i></span></a>
                                    <a href="wishlist.html"><span class="action-text">Wishlist</span><span
                                            class="icon"><i class="far fa-heart"></i></span></a>
                                    <a class="popup-content" href="#QuickView"><span class="action-text">Quick
                                            View</span><span class="icon"><i class="far fa-eye"></i></span></a>
                                </div>
                                <div class="th-menu_discount">
                                    <span class="sale">24% OFF</span>
                                </div>
                            </div>
                            <div class="th-menu-content">
                                <h3 class="th-menu_title"><a href="shop-details.html">Italian Pasta</a></h3>
                                <p class="th-menu_desc">Barbecue Italian cuisine pizza</p>
                                <span class="th-menu_price">$17.85</span>
                            </div>
                            <div class="fire"><img src="assets/img/update_2/shape/fire.png" alt="shape"></div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="th-menu">
                            <div class="th-menu_img">
                                <img class="spin" src="assets/img/update_2/menu/menu_3_9.png" alt="menu Image">
                                <div class="product-action">
                                    <a href="cart.html"><span class="action-text">Add To cart</span><span
                                            class="icon"><i class="far fa-cart-shopping"></i></span></a>
                                    <a href="wishlist.html"><span class="action-text">Wishlist</span><span
                                            class="icon"><i class="far fa-heart"></i></span></a>
                                    <a class="popup-content" href="#QuickView"><span class="action-text">Quick
                                            View</span><span class="icon"><i class="far fa-eye"></i></span></a>
                                </div>
                                <div class="th-menu_discount">
                                    <span class="sale">24% OFF</span>
                                </div>
                            </div>
                            <div class="th-menu-content">
                                <h3 class="th-menu_title"><a href="shop-details.html">Sichuan cocktail</a></h3>
                                <p class="th-menu_desc">Barbecue Italian cuisine pizza</p>
                                <span class="th-menu_price">$12.85</span>
                            </div>
                            <div class="fire"><img src="assets/img/update_2/shape/fire.png" alt="shape"></div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="th-menu">
                            <div class="th-menu_img">
                                <img class="spin" src="assets/img/update_2/menu/menu_3_10.png" alt="menu Image">
                                <div class="product-action">
                                    <a href="cart.html"><span class="action-text">Add To cart</span><span
                                            class="icon"><i class="far fa-cart-shopping"></i></span></a>
                                    <a href="wishlist.html"><span class="action-text">Wishlist</span><span
                                            class="icon"><i class="far fa-heart"></i></span></a>
                                    <a class="popup-content" href="#QuickView"><span class="action-text">Quick
                                            View</span><span class="icon"><i class="far fa-eye"></i></span></a>
                                </div>
                                <div class="th-menu_discount">
                                    <span class="sale">24% OFF</span>
                                </div>
                            </div>
                            <div class="th-menu-content">
                                <h3 class="th-menu_title"><a href="shop-details.html">Pizza Italian</a></h3>
                                <p class="th-menu_desc">Barbecue Italian cuisine pizza</p>
                                <span class="th-menu_price">$10.85</span>
                            </div>
                            <div class="fire"><img src="assets/img/update_2/shape/fire.png" alt="shape"></div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="th-menu">
                            <div class="th-menu_img">
                                <img class="spin" src="assets/img/update_2/menu/menu_3_11.png" alt="menu Image">
                                <div class="product-action">
                                    <a href="cart.html"><span class="action-text">Add To cart</span><span
                                            class="icon"><i class="far fa-cart-shopping"></i></span></a>
                                    <a href="wishlist.html"><span class="action-text">Wishlist</span><span
                                            class="icon"><i class="far fa-heart"></i></span></a>
                                    <a class="popup-content" href="#QuickView"><span class="action-text">Quick
                                            View</span><span class="icon"><i class="far fa-eye"></i></span></a>
                                </div>
                                <div class="th-menu_discount">
                                    <span class="sale">24% OFF</span>
                                </div>
                            </div>
                            <div class="th-menu-content">
                                <h3 class="th-menu_title"><a href="shop-details.html">California Roll</a></h3>
                                <p class="th-menu_desc">Barbecue Italian cuisine pizza</p>
                                <span class="th-menu_price">$19.85</span>
                            </div>
                            <div class="fire"><img src="assets/img/update_2/shape/fire.png" alt="shape"></div>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="th-menu">
                            <div class="th-menu_img">
                                <img class="spin" src="assets/img/update_2/menu/menu_3_12.png" alt="menu Image">
                                <div class="product-action">
                                    <a href="cart.html"><span class="action-text">Add To cart</span><span
                                            class="icon"><i class="far fa-cart-shopping"></i></span></a>
                                    <a href="wishlist.html"><span class="action-text">Wishlist</span><span
                                            class="icon"><i class="far fa-heart"></i></span></a>
                                    <a class="popup-content" href="#QuickView"><span class="action-text">Quick
                                            View</span><span class="icon"><i class="far fa-eye"></i></span></a>
                                </div>
                                <div class="th-menu_discount">
                                    <span class="sale">24% OFF</span>
                                </div>
                            </div>
                            <div class="th-menu-content">
                                <h3 class="th-menu_title"><a href="shop-details.html">Testy Sea Foods</a></h3>
                                <p class="th-menu_desc">Barbecue Italian cuisine pizza</p>
                                <span class="th-menu_price">$16.85</span>
                            </div>
                            <div class="fire"><img src="assets/img/update_2/shape/fire.png" alt="shape"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!--==============================
     Footer Area
    ==============================-->
@endsection
