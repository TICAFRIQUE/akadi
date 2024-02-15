  <!-- ========== Start Mobile menu ========== -->
  <div class="th-menu-wrapper">
      <div class="th-menu-area text-center">
          <button class="th-menu-toggle"><i class="fal fa-times"></i></button>
          <div class="mobile-logo">
              <img src="{{ asset('site/assets/img/custom/logo.png') }}" width="65px" alt="logo akadi"> </a>
          </div>
          <div class="th-mobile-menu">
              <ul>
                  <li class="menu-item-has-children">
                      <a href="{{route('page-acceuil')}}">Accueil</a>
                  </li>
                  <li class="menu-item-has-children">
                      <a href="#">Menu</a>
                      <ul class="sub-menu">
                          <li><a href="menu-fast.html">Fast Food Menu</a></li>
                          <li><a href="menu-fast-v2.html">Fast Food Menu v2</a></li>
                          <li><a href="menu-rest.html">Restaurant Food Menu</a></li>
                          <li><a href="menu-rest-v2.html">Restaurant Food Menu v2</a></li>
                      </ul>
                  </li>
                  <li class="menu-item-has-children">
                      <a href="#">Categorie</a>
                      <ul class="sub-menu">
                          <li><a href="shop.html">Shop</a></li>
                          <li><a href="shop-v2.html">Shop v2</a></li>
                          <li><a href="shop-details.html">Shop Details</a></li>
                          <li><a href="shop-details-v2.html">Shop Details v2</a></li>
                      </ul>
                  </li>

                  <li class="menu-item-has-children">
                      <a href="#">News</a>
                      <ul class="sub-menu">
                          <li><a href="blog.html">News</a></li>
                          <li><a href="blog-details.html">News Details</a></li>
                      </ul>
                  </li>

              </ul>
          </div>
      </div>
  </div>
  <!-- ========== End Mobile menu ========== -->


  <!-- ========== Start main menu ========== -->
  <header class="th-header header-layout6">
     <div class="header-top">
            <div class="container">
                <div class="row justify-content-center justify-content-lg-between align-items-center">
                    <div class="col-auto d-none d-lg-block">
                        <p class="header-notice">
                            <i class="fal fa-phone"></i>
                            Appelez nous au +225 07 58 83 83 38</p>
                    </div>
                    <div class="col-auto">
                        <div class="header-links">
                            <ul>
                                <li><i class="fal fa-location-dot"></i>Abidjan , Koumassi</li>
                                <li>
                                    <div class="header-social">
                                        <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                                        <a href="https://www.twitter.com/"><i class="fab fa-twitter"></i></a>
                                        <a href="https://www.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                                        <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      <div class="sticky-wrapper">
          <!-- Main Menu Area -->
          <div class="menu-area">
              <div class="container th-container">
                  <div class="row align-items-center justify-content-between">
                      <div class="col-auto">
                          <div class="header-logo">
                              <a href="index.html">
                                  <img src="{{ asset('site/assets/img/custom/logo.png') }}" width=65px alt="logo akadi"> </a>
                          </div>
                      </div>
                      <div class="col-auto">
                          <nav class="main-menu d-none d-lg-inline-block">
                              <ul>
                                  <li class="menu-item-has-children">
                                      <a href="{{route('page-acceuil')}}">Accueil</a>
                                  </li>
                                  <li class="menu-item-has-children">
                                      <a href="#">Menu</a>
                                      <ul class="sub-menu">
                                          <li><a href="menu-fast.html">Fast Food Menu</a></li>
                                          <li><a href="menu-fast-v2.html">Fast Food Menu v2</a></li>
                                          <li><a href="menu-rest.html">Restaurant Food Menu</a></li>
                                          <li><a href="menu-rest-v2.html">Restaurant Food Menu v2</a></li>
                                      </ul>
                                  </li>
                                  <li class="menu-item-has-children">
                                      <a href="#">Categorie</a>
                                      <ul class="sub-menu">
                                          <li><a href="shop.html">Shop</a></li>
                                          <li><a href="shop-v2.html">Shop v2</a></li>
                                          <li><a href="shop-details.html">Shop Details</a></li>
                                          <li><a href="shop-details-v2.html">Shop Details v2</a></li>
                                      </ul>
                                  </li>

                                  <li class="menu-item-has-children">
                                      <a href="#">News</a>
                                      <ul class="sub-menu">
                                          <li><a href="blog.html">News</a></li>
                                          <li><a href="blog-details.html">News Details</a></li>
                                      </ul>
                                  </li>

                              </ul>
                          </nav>
                      </div>
                      <div class="col-auto">
                          <div class="header-button">
                              <button type="button" class="simple-icon searchBoxToggler"><i
                                      class="far fa-search"></i></button>
                              <button type="button" class="simple-icon sideMenuToggler">
                                  <i class="far fa-cart-shopping"></i>
                                  <span class="badge">5</span>
                              </button>
                              <button type="button" class="simple-icon sideMenuToggler">
                                  <i class="far fa-user"></i>
                              </button>
                              {{-- <a href="contact.html" class="th-btn rounded-2 style3">Book a Table<i
                                        class="fa-solid fa-arrow-right ms-2"></i></a> --}}
                              <button type="button" class="th-menu-toggle d-inline-block d-lg-none"><i
                                      class="far fa-bars"></i></button>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </header>
  <!-- ========== End main menu ========== -->
