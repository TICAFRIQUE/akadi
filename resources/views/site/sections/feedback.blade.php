<!--==============================
Testimonial Area  
==============================-->
    <section class="space bg-smoke2">
        <div class="container">
            <div class="title-area text-center">
                <span class="sub-title">
                    <img class="icon" src="{{asset('site/assets/img/icon/title_icon.svg')}}" alt="icon">
                    Témoignages
                </span>
                <h2 class="sec-title">Le retour d'expérience de nos  <span class="font-style text-theme">clients</span></h2>
                {{-- <p class="sec-text ms-auto me-auto">Objectively pontificate quality models before intuitive information. Dramatically recaptiualize multifunctional materials.</p> --}}
            </div>
            <div class="row slider-shadow th-carousel number-dots" data-slide-show="3" data-lg-slide-show="2" data-md-slide-show="1" data-dots="true" data-xl-dots="true" data-ml-dots="true" data-lg-dots="true">
                
                @for ($i = 0; $i <= 4; $i++)
                     <div class="col-xl-4 col-lg-6">
                    <div class="testi-box">
                        <div class="testi-box_icon">
                            <img src="{{asset('site/assets/img/icon/quote_left.svg')}}" alt="quote">
                        </div>
                        <p class="testi-box_text">“Synergistically strategize interdependent ROI through distinctive markets. Credibly restore one-to-one through.”</p>
                        <div class="testi-box_review">
                            <i class="fa-sharp fa-solid fa-star"></i><i class="fa-sharp fa-solid fa-star"></i><i class="fa-sharp fa-solid fa-star"></i><i class="fa-sharp fa-solid fa-star"></i><i class="fa-sharp fa-solid fa-star"></i>
                        </div>
                        <div class="testi-box_profile">
                            <div class="testi-box_avater">
                                <img src="{{asset('site/assets/img/custom/avatar.png')}}" alt="Avater">
                            </div>
                            <div class="media-body">
                                <h3 class="testi-box_name">Rayan Kook</h3>
                                <span class="testi-box_desig">NYC, USA</span>
                            </div>
                        </div>
                        {{-- <div class="testi-box_img">
                            <img src="{{asset('site/assets/img/custom/chicken.png')}}" alt="Reveiw Image" width="30%">
                        </div> --}}
                    </div>
                </div>
                @endfor
               

               
            </div>
        </div>
        <div class="shape-mockup leaf jump-reverse" data-top="0%" data-left="0"><img src="{{asset('site/assets/img/custom/chicken.png')}}" alt="shape"></div>
        <div class="shape-mockup leaf jump" data-bottom="0%" data-right="0"><img src="{{asset('site/assets/img/custom/chicken.png')}}" alt="shape"></div>
    </section>