<!-- ========== Start top promo ========== -->
<style>
    #Head-Banner {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        background: #F0F0F0;
        font-family: Poppins;
        font-size: 20px;
        font-weight: 700;
    }

    a {
        color: #0F0F0F;
        text-decoration: none;
    }

    #Head-Banner-Text {
        background: #FFFF00;
    }
</style>



@if ($top_promo)
    <div class="space-bottom">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div id="Head-Banner">
                        <a href="https://webrocket.fr/">
                            <span id="Head-Banner-Text">You Only Have <span id="Promo-Timer"></span> To Take 50% Off Your
                                Next Purchase !</span>
                        </a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 m-auto">
                    <div class="img-box2 ">
                        {{-- <div class="img1">
                        <img src="{{asset('site/assets/img/normal/about_2_1.png')}}" alt="About">
                    </div> --}}
                        <div class="shape1" style="left: 0">
                            <img src="{{ $top_promo->getFirstmediaUrl('publicite_image') }}" alt="About"
                                class="ml-4" width="80%">
                        </div>
                        <div class="discount_style1"
                            data-bg-src="{{ asset('site/assets/img/shape/discount_bg_1.svg') }}">
                            <h4 class="percentage">
                                {{ $top_promo['discount'] }}
                                <span class="small-text">% <br> <span class="text">Off</span></span>
                            </h4>
                        </div>
                        {{-- <a href="shop.html" class="order-btn"><span class="font-style text-theme">Order</span>Now</a> --}}
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 m-auto ">
                    <div class="title-area mb-30 m-auto text-center">
                        <span class="sub-title fs-3 m-auto mb-3" style="text-align: center">
                            <img class="icon" src="{{ asset('site/assets/img/icon/title_icon.svg') }}" alt="icon">
                            Top promo Akadi
                        </span>



                        <p class="fs-4">
                            {{ $top_promo['texte'] }}
                        </p>


                    </div>
                    {{-- <p class="mt-n2 mb-10">Bientôt disponible.</p> --}}
                    <div class="btn-wrap style1">
                        <a href="{{ $top_promo['url'] }}" class="th-btn m-auto">{{ $top_promo['button_name'] }}</a>
                        {{-- <div class="about-counter1">
                        <h3 class="counter-title"><span class="counter-number">24</span></h3>
                        <div class="media-body">
                            <p class="counter-info">YEARS OF</p>
                            <h5 class="counter-text">Experience</h5>
                        </div>
                    </div> --}}
                    </div>
                </div>
            </div>
        </div>

    </div>
@endif

<!-- ========== End top promo ========== -->
