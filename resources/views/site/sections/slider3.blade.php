<!--==============================
Hero Area
==============================-->
<div id="slider" class="ls-wp-container fitvidsignore hero-5 th-hero-carousel"
    data-bg-src="{{ $background ? $background->getFirstMediaUrl('publicite_image') : '' }}"
    style="width:1920px;height:810px;margin-top:90px;margin-bottom: 0px;">

    <!-- Slide-->
    @if ($sliders)
        @foreach ($sliders as $item)
            <div class="ls-slide" data-ls="duration:5000; kenburnsscale:1.2;">
                {{-- <ls-layer
            style="font-size:30px; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; top:203px; left:340px; color:#f85d05; line-height:26px; font-family:'Lobster Two';"
            class="ls-l ls-hide-tablet ls-hide-phone ls-text-layer"
            data-ls="offsetxin:500; easingin:easeOutBack; bgcolorin:transparent; colorin:transparent; offsetxout:-120; durationout:800; startatout:slidechangeonly + ; bgcolorout:transparent; colorout:transparent;">
            Welcome to Pizzan
        </ls-layer> --}}

                <img width="800" height="800" src="{{ $item->getFirstMediaUrl('publicite_image') }}"
                    class="ls-l ls-img-layer" alt="image" decoding="async"
                    style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; left:1000px; top:100px;"
                    data-ls="offsetyin:-160; easingin:easeOutSine; rotatein:20; rotatexin:30; offsetyout:-100;">



                <!-- ========== Start desktop ========== -->
                <h1 style='-webkit-background-clip: text; background-clip: text; color: transparent;text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:700; letter-spacing:0px; background-position:50% 50%; background-repeat:repeat; font-family:Rubik; line-height:90px; top:342px; left:340px; font-size:50px; background-size:auto; white-space:normal;'
                    class="ls-l ls-hide-tablet ls-hide-phone ls-text-layer text-white"
                    data-ls="offsetxin:500; delayin:80; easingin:easeOutBack; bgcolorin:transparent; colorin:transparent; offsetxout:-120; durationout:800; startatout:slidechangeonly + ; bgcolorout:transparent; colorout:transparent;">
                    {{ $item['texte'] }}
                </h1>

                <a style="" class="ls-l ls-hide-tablet ls-hide-phone" href="{{ $item['url'] }}" target="_self"
                    data-ls="offsetxin:500; delayin:180; easingin:easeOutBack; bgcolorin:transparent; colorin:transparent; offsetxout:-120; durationout:800; startatout:slidechangeonly + ; bgcolorout:transparent; colorout:transparent; hover:true; hoveropacity:1; hoverbgcolor:#fff; hovercolor:#f85d05;">
                    <ls-layer
                        style="font-size:14px; color:#fff; text-align:center; font-family:Rubik; cursor:pointer; width:180px; left:344px; top:552px; text-transform:uppercase; line-height:14px; padding-bottom:20px; padding-top:22px; font-weight:600; border-radius:4px 4px 4px 4px; background-color:#f85d05;"
                        class="ls-ib-icon ls-button-layer px-4">
                        Decouvez nos menus<i class="fa fa-arrow-right" style="margin-left:.5em; font-size:1em;"></i>
                    </ls-layer>
                </a>
                <!-- ========== End desktop ========== -->


                <!-- ========== Start mobile ========== -->
                <h1 style='-webkit-background-clip: text;  word-wrap:normal; background-clip: text; color: transparent;text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:700; letter-spacing:0px; background-position:50% 50%; background-repeat:repeat; font-family:Rubik; line-height:110px; top:229px; left:72px; font-size:100px; background-size:auto; white-space:normal; background-image:url("assets/img/update_2/hero/text_pattern.jpg");'
                    class="ls-l hero-title ls-hide-desktop ls-text-layer text-white wrap"
                    data-ls="offsetxin:500; delayin:160; easingin:easeOutBack; bgcolorin:transparent; colorin:transparent; offsetxout:-120; durationout:800; startatout:slidechangeonly + ; bgcolorout:transparent; colorout:transparent;">
                    {{ $item['texte'] }}
                </h1>

                <a style="" class="ls-l ls-hide-desktop" href="{{ $item['url'] }}" target="_self"
                    data-ls="offsetxin:500; delayin:200; easingin:easeOutBack; bgcolorin:transparent; colorin:transparent; offsetxout:-120; durationout:800; startatout:slidechangeonly + ; bgcolorout:transparent; colorout:transparent; hover:true; hoveropacity:1; hoverbgcolor:#ffffff; hovercolor:#eb0029;">
                    <ls-layer
                        style="font-size:32px; color:#fff; text-align:center; font-family:Rubik; cursor:pointer; left:308px; top:400px; text-transform:uppercase; padding-bottom:30px; padding-top:30px; font-weight:600; line-height:32px; background-color:#eb0029; border-radius:10px 10px 10px 10px; width:370px;"
                        class="ls-ib-icon ls-button-layer p-2">
                        Decouvez nos menu<i class="fa fa-arrow-right" style="margin-left:.5em; font-size:1em;"></i>
                    </ls-layer>
                </a>



                <!-- ========== End mobile ========== -->


                <!-- ========== Start circle thumb ========== -->



                {{-- <a style="" class="ls-l ls-hide-tablet ls-hide-phone" href="#2" target="_self"
                    data-ls="static:forever;">
                    <img width="50" height="50" src="assets/img/update_2/hero/hero_thumb_5_2.png"
                        class="ls-img-layer" alt="image" decoding="async"
                        style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; left:140px; top:380px;">
                </a> --}}
                {{-- <a style="" class="ls-l ls-hide-tablet ls-hide-phone" href="#1" target="_self"
                    data-ls="static:forever;">
                    <img width="50" height="50" src="assets/img/update_2/hero/hero_thumb_5_1.png"
                        class="ls-img-layer" alt="image" decoding="async"
                        style="font-size:36px; color:#000; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; left:140px; top:320px;">
                </a> --}}
                <!-- ========== End circle thumb ========== -->
                {{-- <ls-layer
                    style="font-size:36px; color:#000; stroke:#000; stroke-width:0px; text-align:left; font-style:normal; text-decoration:none; text-transform:none; font-weight:400; letter-spacing:0px; background-position:0% 0%; background-repeat:no-repeat; background-clip:border-box; overflow:visible; width:50px; height:51px; top:440px; left:140px; -webkit-background-clip:border-box;"
                    class="ls-l ls-hide-tablet ls-hide-phone ls-svg-layer">
                    <svg xmlns="http://www.w3.org/2000/svg" viewbox="0 0 50 51" fill="none">
                        <circle cx="24.9995" cy="25.0557" r="24" stroke="#f85d05" fill="transparent"
                            stroke-width="2"></circle>
                    </svg>
                </ls-layer> --}}
            </div>
        @endforeach
    @endif

</div><!--==============================
Product Area
==============================-->
