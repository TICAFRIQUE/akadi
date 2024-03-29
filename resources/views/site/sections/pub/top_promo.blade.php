<!-- ========== Start top promo ========== -->
<style>
    .promo-banner {
        background-color: #f85d05
    }

     /* .promo-banner span {
        background-color: #f85d05
    } */
</style>



@if ($top_promo)
    <div class="space-bottom">
        <div class="container">
            <div class="row">

                  <div class="col-12 promo-banner py-3 mb-3 fs-5">
                        <span class=" text-white p-2 ">Se termine dans <span class="fw-bold"
                                id="Promo-Timer"></span>
                            Profitez de {{ $top_promo['discount'] }}%
                            de reduction !</span>
                    </div>
                <div class="col-md-6 col-lg-6 col-sm-12 col-xs-12 m-auto">

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
                            {!!$top_promo['texte']!!}
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

        var topPromo = {{ Js::from($top_promo) }}


        var startDate = topPromo.date_debut_pub
        var endDate = topPromo.date_fin_pub

        var dateNow = new Date();
        console.log(dateNow == startDate);

        function countDown() {
            var currDate = new Date(startDate)
            var endTime = new Date(endDate);
            endTime = (Date.parse(endTime) / 1000);
            var now = new Date();
            now = (Date.parse(now) / 1000);
            //var endTime = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 24);			


            // $("#log").html(endTime.getHour());
            var timeLeft = endTime - now;

            var days = Math.floor(timeLeft / 86400);
            var hours = Math.floor((timeLeft - (days * 86400)) / 3600);
            var minutes = Math.floor((timeLeft - (days * 86400) - (hours * 3600)) / 60);
            var seconds = Math.floor((timeLeft - (days * 86400) - (hours * 3600) - (minutes * 60)));
            // if (hours > 8) {
            //     hours = 0;
            //     minutes = 0;
            //     seconds = 0;
            // }
            // if (hours < "17") {
            //     hours = "0" + hours;
            // }
            // if (minutes < "10") {
            //     minutes = "0" + minutes;
            // }
            // if (seconds < "10") {
            //     seconds = "0" + seconds;
            // }

            $("#Promo-Timer").html(days + ' j' + " : " + hours + ' h' + " : " + minutes + ' m' + " : " +
                seconds + ' s');

        }

        setInterval(function() {
            countDown();
        }, 1000);


    });
</script>

<!-- ========== End top promo ========== -->
