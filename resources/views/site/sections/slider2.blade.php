<!--==============================
Hero Area
==============================-->
<div class="d-none d-md-block">

    <div id="slider" class="ls-wp-container fitvidsignore hero-2 th-hero-carousel"
        data-bg-src="{{$background->getFirstMediaUrl('publicite_image')}}"
        style="width:1920px;height:900px;margin:0 auto;margin-bottom: 0px;">


        <!-- Slide 1-->
        @foreach ($sliders as $key=>$item)
            
        <div class="ls-slide" data-ls="duration:5000; transition2d:5; kenburnsscale:1.2;">
            <img width="837" height="813" src="{{ $item->getFirstMediaUrl('publicite_image')}}"
                class="ls-l ls-hide-tablet ls-hide-phone ls-img-layer" alt="" decoding="async"
                style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; top:187px; left:914px;"
                data-ls="offsetxin:500; durationin:600; delayin:100; easingin:easeOutBack; rotatein:80; bgcolorin:transparent; colorin:transparent; offsetxout:-80; durationout:400; startatout:slidechangeonly + ; bgcolorout:transparent; colorout:transparent; position:fixed;">

            <h1 style="text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:800; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; font-family:'Lobster Two'; line-height:80px; top:552px; left:340px; font-size:70px; color:#eb0029; white-space:normal;"
                class="ls-l hero-title ls-hide-tablet ls-hide-phone ls-text-layer"
                data-ls="offsetxin:500; easingin:easeOutBack; bgcolorin:transparent; colorin:transparent; offsetxout:-80; durationout:400; startatout:slidechangeonly + ; bgcolorout:transparent; colorout:transparent; position:fixed;">
               {{$item['texte']}}
            </h1>
            <img width="2" height="100" src="{{ asset('site/assets/img/hero/dot_line_1.png') }}"
                class="ls-l ls-hide-tablet ls-hide-phone ls-img-layer" alt="" decoding="async"
                style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; left:135px; top:349px;"
                data-ls="static:forever; position:fixed;">
            <img width="2" height="100" src="{{ asset('site/assets/img/hero/dot_line_2.png') }}"
                class="ls-l ls-hide-tablet ls-hide-phone ls-img-layer" alt="" decoding="async"
                style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; left:135px; top:718px;"
                data-ls="static:forever; position:fixed;">
           
            <a style="" class="ls-l ls-hide-tablet ls-hide-phone" href="{{$item['url']}}" target="_self"
                data-ls="offsetxin:500; easingin:easeOutBack; bgcolorin:transparent; colorin:transparent; offsetxout:-80; durationout:400; startatout:slidechangeonly + ; bgcolorout:transparent; colorout:transparent; hover:true; hoveropacity:1; hoverbgcolor:#010f1c; hovercolor:#ffffff; position:fixed;">
                <ls-layer
                    style="font-size:14px; color:#fff; text-align:center; font-family:Rubik; cursor:pointer; width:162px; left:530px; top:681px; text-transform:uppercase; line-height:14px; padding-bottom:20px; padding-top:22px; font-weight:600; border-radius:999px 999px 999px 999px; background-color:#ff9d2d;"
                    class="ls-button-layer">
                    Achetez maintenant
                </ls-layer>
            </a>


            <a style="" class="ls-l ls-hide-tablet ls-hide-phone" href="#{{$key}}" target="_self"
                data-ls="static:forever; position:fixed;">
                <img width="70" height="70" src="{{ $item->getFirstMediaUrl('publicite_image')}}"
                    class="ls-img-layer" alt="" decoding="async"
                    style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; left:100px; top:468px;">
            </a>

             <a style="" class="ls-l ls-hide-tablet ls-hide-phone" href="#{{$key}}" target="_self"
                data-ls="static:forever; position:fixed;">
                <img width="70" height="70" src="{{ $item->getFirstMediaUrl('publicite_image')}}"
                    class="ls-img-layer" alt="" decoding="async"
                    style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; left:100px; top:468px;">
            </a>
           
            {{-- <img width="70" height="70" src="{{ asset('site/assets/img/hero/hero_thumb_2_1_active.png') }}"
                class="ls-l ls-hide-tablet ls-hide-phone ls-img-layer" alt="" decoding="async"
                style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; left:100px; top:468px;"
                data-ls="position:fixed;"> --}}
        </div>
        @endforeach

        
    </div>


</div>
<!-- Layer Slider Mobile version start here -->
{{-- <div class="d-block d-md-none">

    <div class="ls-wp-container fitvidsignore hero-2 th-hero-carousel phone"
        style="width:500px;height:660px;margin:0 auto;margin-bottom: 0px;">
        <!-- Slide 1-->
        <div class="ls-slide" data-ls="duration:5000; transition2d:5; kenburnsscale:1.2;">
            <img width="500" height="660" src="assets/img/hero/hero_bg_2_1_phone.jpg" class="ls-bg"
                alt="hero img">
            <h1 style="text-align:left; font-style:normal; text-decoration:none; text-transform:none; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; font-family:Rubik; line-height:54px; top:259px; left:15px; color:#010f1c; font-weight:800; font-size:44px; white-space:normal;"
                class="ls-l ls-text-layer"
                data-ls="offsetyin:-100lh; durationin:1500; easingin:easeOutQuint; bgcolorin:transparent; colorin:transparent; offsetyout:-150; durationout:1500; easingout:easeInQuint; bgcolorout:transparent; colorout:transparent; position:fixed;">
                Welcome To Our
            </h1>
            <h1 style="text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:800; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; font-family:Rubik; line-height:54px; top:313px; left:15px; font-size:44px; color:#010f1c; white-space:normal;"
                class="ls-l hero-title ls-text-layer"
                data-ls="offsetyin:-100lh; durationin:1500; easingin:easeOutQuint; bgcolorin:transparent; colorin:transparent; offsetyout:-150; durationout:1500; easingout:easeInQuint; bgcolorout:transparent; colorout:transparent; position:fixed;">
                Pizzan <span class="text-theme font-style">Fast Food</span> &amp;
            </h1>
            <h1 style="text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:800; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; font-family:'Lobster Two'; line-height:55px; top:367px; left:15px; font-size:44px; color:#eb0029; white-space:normal;"
                class="ls-l hero-title ls-text-layer"
                data-ls="offsetyin:-100lh; durationin:1500; easingin:easeOutQuint; bgcolorin:transparent; colorin:transparent; offsetyout:150; durationout:1500; easingout:easeInQuint; bgcolorout:transparent; colorout:transparent; position:fixed;">
                Restaurants
            </h1>
            <a style="" class="ls-l" href="reservation.html" target="_self"
                data-ls="offsetyin:-100lh; durationin:1500; easingin:easeOutQuint; bgcolorin:transparent; colorin:transparent; offsetyout:150; durationout:1500; easingout:easeInQuint; bgcolorout:transparent; colorout:transparent; hover:true; hoveropacity:1; hoverbgcolor:#010f1c; hovercolor:#ffffff; position:fixed;">
                <ls-layer
                    style="font-size:20px; color:#fff; text-align:center; font-family:Rubik; cursor:pointer; width:198px; left:18px; top:465px; text-transform:uppercase; line-height:20px; font-weight:600; border-radius:999px 999px 999px 999px; background-color:#eb0029; padding-bottom:22px; padding-top:22px;"
                    class="ls-button-layer">
                    Book a table
                </ls-layer>
            </a>
            <a style="" class="ls-l" href="shop.html" target="_self"
                data-ls="offsetyin:-100lh; durationin:1500; easingin:easeOutQuint; bgcolorin:transparent; colorin:transparent; offsetyout:150; durationout:1500; easingout:easeInQuint; bgcolorout:transparent; colorout:transparent; hover:true; hoveropacity:1; hoverbgcolor:#010f1c; hovercolor:#ffffff; position:fixed;">
                <ls-layer
                    style="font-size:19px; color:#fff; text-align:center; font-family:Rubik; cursor:pointer; width:178px; left:234px; top:465px; text-transform:uppercase; line-height:19px; padding-bottom:22px; padding-top:22px; font-weight:600; border-radius:999px 999px 999px 999px; background-color:#ff9d2d;"
                    class="ls-button-layer">
                    ORDER NOW
                </ls-layer>
            </a>
            <img width="190" height="118" src="assets/img/hero/hero_shape_7.png" class="ls-l ls-img-layer"
                alt="hero img"
                style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; top:593px; left:11px; width:92px; height:57px;"
                data-ls="offsetyin:200; durationin:1400; rotatein:60deg; bgcolorin:transparent; colorin:transparent; parallax:true; parallaxlevel:12; parallaxdurationmove:400; rotation:00deg; position:fixed;">
            <img width="200" height="173" src="assets/img/hero/hero_shape_8.png" class="ls-l ls-img-layer"
                alt="hero img"
                style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; top:554px; left:279px; width:65px; height:56px;"
                data-ls="offsetyin:200; durationin:1400; rotatein:-90deg; bgcolorin:transparent; colorin:transparent; parallax:true; parallaxlevel:12; parallaxdurationmove:400; position:fixed;">
            <img width="153" height="149" src="assets/img/hero/hero_shape_2_2.png" class="ls-l ls-img-layer"
                alt="hero img"
                style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; top:194px; left:19px; width:56px; height:54px;"
                data-ls="offsetxin:-300; durationin:1400; rotatein:80deg; durationout:1500; parallax:true; parallaxlevel:10; parallaxdurationmove:800; position:fixed;">
            <img width="178" height="76" src="assets/img/hero/hero_shape_2_1.png" class="ls-l ls-img-layer"
                alt="hero img"
                style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; top:563px; left:145px; width:97px; height:42px;"
                data-ls="offsetyin:200; durationin:1400; rotatein:50deg; bgcolorin:transparent; colorin:transparent; parallax:true; parallaxlevel:12; parallaxdurationmove:400; position:fixed;">
        </div>


        <!-- Slide 2-->
        <div class="ls-slide" data-ls="duration:5000; transition2d:5; kenburnsscale:1.2;">
            <img width="500" height="660" src="assets/img/hero/hero_bg_2_1_phone.jpg" class="ls-bg"
                alt="hero img">
            <h1 style="text-align:left; font-style:normal; text-decoration:none; text-transform:none; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; font-family:Rubik; line-height:54px; top:259px; left:15px; color:#010f1c; font-weight:800; font-size:44px; white-space:normal;"
                class="ls-l ls-text-layer"
                data-ls="offsetyin:-100lh; durationin:1500; easingin:easeOutQuint; bgcolorin:transparent; colorin:transparent; offsetyout:-150; durationout:1500; easingout:easeInQuint; bgcolorout:transparent; colorout:transparent; position:fixed;">
                Get Started With
            </h1>
            <h1 style="text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:800; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; font-family:Rubik; line-height:54px; top:313px; left:15px; font-size:44px; color:#010f1c; white-space:normal;"
                class="ls-l hero-title ls-text-layer"
                data-ls="offsetyin:-100lh; durationin:1500; easingin:easeOutQuint; bgcolorin:transparent; colorin:transparent; offsetyout:-150; durationout:1500; easingout:easeInQuint; bgcolorout:transparent; colorout:transparent; position:fixed;">
                Pizzan <span class="text-theme font-style">Fast Food</span> &amp;
            </h1>
            <h1 style="text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:800; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; font-family:'Lobster Two'; line-height:55px; top:367px; left:15px; font-size:44px; color:#eb0029; white-space:normal;"
                class="ls-l hero-title ls-text-layer"
                data-ls="offsetyin:-100lh; durationin:1500; easingin:easeOutQuint; bgcolorin:transparent; colorin:transparent; offsetyout:150; durationout:1500; easingout:easeInQuint; bgcolorout:transparent; colorout:transparent; position:fixed;">
                Restaurants
            </h1>
            <a style="" class="ls-l" href="reservation.html" target="_self"
                data-ls="offsetyin:-100lh; durationin:1500; easingin:easeOutQuint; bgcolorin:transparent; colorin:transparent; offsetyout:150; durationout:1500; easingout:easeInQuint; bgcolorout:transparent; colorout:transparent; hover:true; hoveropacity:1; hoverbgcolor:#010f1c; hovercolor:#ffffff; position:fixed;">
                <ls-layer
                    style="font-size:20px; color:#fff; text-align:center; font-family:Rubik; cursor:pointer; width:198px; left:18px; top:465px; text-transform:uppercase; line-height:20px; font-weight:600; border-radius:999px 999px 999px 999px; background-color:#eb0029; padding-bottom:22px; padding-top:22px;"
                    class="ls-button-layer">
                    Book a table
                </ls-layer>
            </a>
            <a style="" class="ls-l" href="shop.html" target="_self"
                data-ls="offsetyin:-100lh; durationin:1500; easingin:easeOutQuint; bgcolorin:transparent; colorin:transparent; offsetyout:150; durationout:1500; easingout:easeInQuint; bgcolorout:transparent; colorout:transparent; hover:true; hoveropacity:1; hoverbgcolor:#010f1c; hovercolor:#ffffff; position:fixed;">
                <ls-layer
                    style="font-size:19px; color:#fff; text-align:center; font-family:Rubik; cursor:pointer; width:178px; left:234px; top:465px; text-transform:uppercase; line-height:19px; padding-bottom:22px; padding-top:22px; font-weight:600; border-radius:999px 999px 999px 999px; background-color:#ff9d2d;"
                    class="ls-button-layer">
                    ORDER NOW
                </ls-layer>
            </a>
            <img width="190" height="118" src="assets/img/hero/hero_shape_7.png" class="ls-l ls-img-layer"
                alt="hero img"
                style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; top:593px; left:11px; width:92px; height:57px;"
                data-ls="offsetyin:200; durationin:1400; rotatein:60deg; bgcolorin:transparent; colorin:transparent; parallax:true; parallaxlevel:12; parallaxdurationmove:400; rotation:00deg; position:fixed;">
            <img width="200" height="173" src="assets/img/hero/hero_shape_8.png" class="ls-l ls-img-layer"
                alt="hero img"
                style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; top:554px; left:279px; width:65px; height:56px;"
                data-ls="offsetyin:200; durationin:1400; rotatein:-90deg; bgcolorin:transparent; colorin:transparent; parallax:true; parallaxlevel:12; parallaxdurationmove:400; position:fixed;">
            <img width="153" height="149" src="assets/img/hero/hero_shape_2_2.png" class="ls-l ls-img-layer"
                alt="hero img"
                style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; top:194px; left:19px; width:56px; height:54px;"
                data-ls="offsetxin:-300; durationin:1400; rotatein:80deg; durationout:1500; parallax:true; parallaxlevel:10; parallaxdurationmove:800; position:fixed;">
            <img width="146" height="63" src="assets/img/hero/hero_shape_2_5.png" class="ls-l ls-img-layer"
                alt="hero img"
                style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; top:563px; left:145px; width:97px; height:42px;"
                data-ls="offsetyin:200; durationin:1400; rotatein:50deg; bgcolorin:transparent; colorin:transparent; parallax:true; parallaxlevel:12; parallaxdurationmove:400; position:fixed;">
        </div>


        <!-- Slide 3-->
        <div class="ls-slide" data-ls="duration:5000; transition2d:5; kenburnsscale:1.2;">
            <img width="500" height="660" src="assets/img/hero/hero_bg_2_1_phone.jpg" class="ls-bg"
                alt="hero img">
            <h1 style="text-align:left; font-style:normal; text-decoration:none; text-transform:none; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; font-family:Rubik; line-height:54px; top:259px; left:15px; color:#010f1c; font-weight:800; font-size:44px; white-space:normal;"
                class="ls-l ls-text-layer"
                data-ls="offsetyin:-100lh; durationin:1500; easingin:easeOutQuint; bgcolorin:transparent; colorin:transparent; offsetyout:-150; durationout:1500; easingout:easeInQuint; bgcolorout:transparent; colorout:transparent; position:fixed;">
                Claim Best Offer On
            </h1>
            <h1 style="text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:800; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; font-family:Rubik; line-height:54px; top:313px; left:15px; font-size:44px; color:#010f1c; white-space:normal;"
                class="ls-l hero-title ls-text-layer"
                data-ls="offsetyin:-100lh; durationin:1500; easingin:easeOutQuint; bgcolorin:transparent; colorin:transparent; offsetyout:-150; durationout:1500; easingout:easeInQuint; bgcolorout:transparent; colorout:transparent; position:fixed;">
                Pizzan <span class="text-theme font-style">Fast Food</span> &amp;
            </h1>
            <h1 style="text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:800; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; font-family:'Lobster Two'; line-height:55px; top:367px; left:15px; font-size:44px; color:#eb0029; white-space:normal;"
                class="ls-l hero-title ls-text-layer"
                data-ls="offsetyin:-100lh; durationin:1500; easingin:easeOutQuint; bgcolorin:transparent; colorin:transparent; offsetyout:150; durationout:1500; easingout:easeInQuint; bgcolorout:transparent; colorout:transparent; position:fixed;">
                Restaurants
            </h1>
            <a style="" class="ls-l" href="reservation.html" target="_self"
                data-ls="offsetyin:-100lh; durationin:1500; easingin:easeOutQuint; bgcolorin:transparent; colorin:transparent; offsetyout:150; durationout:1500; easingout:easeInQuint; bgcolorout:transparent; colorout:transparent; hover:true; hoveropacity:1; hoverbgcolor:#010f1c; hovercolor:#ffffff; position:fixed;">
                <ls-layer
                    style="font-size:20px; color:#fff; text-align:center; font-family:Rubik; cursor:pointer; width:198px; left:18px; top:465px; text-transform:uppercase; line-height:20px; font-weight:600; border-radius:999px 999px 999px 999px; background-color:#eb0029; padding-bottom:22px; padding-top:22px;"
                    class="ls-button-layer">
                    Book a table
                </ls-layer>
            </a>
            <a style="" class="ls-l" href="shop.html" target="_self"
                data-ls="offsetyin:-100lh; durationin:1500; easingin:easeOutQuint; bgcolorin:transparent; colorin:transparent; offsetyout:150; durationout:1500; easingout:easeInQuint; bgcolorout:transparent; colorout:transparent; hover:true; hoveropacity:1; hoverbgcolor:#010f1c; hovercolor:#ffffff; position:fixed;">
                <ls-layer
                    style="font-size:19px; color:#fff; text-align:center; font-family:Rubik; cursor:pointer; width:178px; left:234px; top:465px; text-transform:uppercase; line-height:19px; padding-bottom:22px; padding-top:22px; font-weight:600; border-radius:999px 999px 999px 999px; background-color:#ff9d2d;"
                    class="ls-button-layer">
                    ORDER NOW
                </ls-layer>
            </a>
            <img width="190" height="118" src="assets/img/hero/hero_shape_7.png" class="ls-l ls-img-layer"
                alt="hero img"
                style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; top:593px; left:11px; width:92px; height:57px;"
                data-ls="offsetyin:200; durationin:1400; rotatein:60deg; bgcolorin:transparent; colorin:transparent; parallax:true; parallaxlevel:12; parallaxdurationmove:400; rotation:00deg; position:fixed;">
            <img width="200" height="173" src="assets/img/hero/hero_shape_8.png" class="ls-l ls-img-layer"
                alt="hero img"
                style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; top:554px; left:279px; width:65px; height:56px;"
                data-ls="offsetyin:200; durationin:1400; rotatein:-90deg; bgcolorin:transparent; colorin:transparent; parallax:true; parallaxlevel:12; parallaxdurationmove:400; position:fixed;">
            <img width="153" height="149" src="assets/img/hero/hero_shape_2_2.png" class="ls-l ls-img-layer"
                alt="hero img"
                style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; top:194px; left:19px; width:56px; height:54px;"
                data-ls="offsetxin:-300; durationin:1400; rotatein:80deg; durationout:1500; parallax:true; parallaxlevel:10; parallaxdurationmove:800; position:fixed;">
            <img width="135" height="96" src="assets/img/hero/hero_shape_2_6.png" class="ls-l ls-img-layer"
                alt="hero img"
                style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; top:563px; left:145px; width:97px; height:42px;"
                data-ls="offsetyin:200; durationin:1400; rotatein:50deg; bgcolorin:transparent; colorin:transparent; parallax:true; parallaxlevel:12; parallaxdurationmove:400; position:fixed;">
        </div>
    </div>

</div> --}}
<!--======== / Hero Section ========--><!--==============================
