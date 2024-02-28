<!--==============================
Petite section de publicité
==============================-->
@if ($small_card)
    <div class="title-area text-center">
        <span class="sub-title">
            <img class="icon" src="{{ asset('site/assets/img/icon/title_icon.svg') }}" alt="icon">
            Decouvrir nos packs
        </span>
        {{-- <h2 class="sec-title">Tu testes, tu restes <span class="font-style text-theme"> toujours</span></h2> --}}

    </div>

    <div class="space-bottom">
        <div class="container">
            <div class="row gy-4">
                @foreach ($small_card as $item)
                    <div class="col-xl-4 col-md-6">
                        <div class="offer-card" data-bg-src="{{ $item->getFirstMediaUrl('publicite_image') }}">
                            <h3 class="offer-title box-title"> {!!$item['texte']!!} </h3>
                            <p class="offer-text"></p>
                            <a href="{{ $item['url'] }}" class="line-btn btn btn-danger text-white">Commandez</a>
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </div>
@endif
<!--==============================
Petite section de publicité
==============================-->
