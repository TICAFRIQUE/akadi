<!-- ========== Start top promo ========== -->
<style>
   .promo-banner{
    background-color: rgb(237, 70, 70)
   }
</style>



@if ($top_promo)
    <div class="space-bottom">
        <div class="container">
            <div class="row">

              
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 m-auto">

                    <div class=" promo-banner py-3 mb-3 fs-5">
                        <span id="" class=" text-white p-2 text-center">Se termine dans <span class="fw-bold" id="Promo-Timer"></span>
                            Profitez de {{$top_promo['discount']}}%
                            de reduction !</span>
                    </div>
                    <div class="img-box2 ">

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {

var value = {{Js::from($top_promo)}}
console.log(value);

        setInterval(function time() {
            var d = new Date();
            var hours = 24 - d.getHours();
            var min = 60 - d.getMinutes();
            if ((min + '').length == 1) {
                min = '0' + min;
            }
            var sec = 60 - d.getSeconds();
            if ((sec + '').length == 1) {
                sec = '0' + sec;
            }
            jQuery('#Promo-Timer').html(hours + ':' + min + ':' + sec)
        }, 1000);
    });
</script>

<!-- ========== End top promo ========== -->
