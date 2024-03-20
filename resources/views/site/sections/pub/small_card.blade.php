<!--==============================
Petite section de publicité
==============================-->
@if (count($pack) > 0)
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
                @foreach ($pack as $item)
                    <div class="col-xl-4 col-md-6">
                        <a href="{{ route('detail-produit', $item['slug']) }}">
                            <div class="offer-card" data-bg-src="{{ $item->getFirstMediaUrl('principal_img') }}">
                                {{-- <h3 class="offer-title box-title">{{$item['title']}} </h3> --}}
                                {{-- <p class="offer-text">
                                {{$item['description']}}
                            </p> --}}
                                <a href="{{ route('detail-produit', $item['slug']) }}"
                                    class="line-btn btn btn-danger text-white">Commandez</a>
                            </div>
                        </a>
                        <p class="text-dark fw-500  text-center text-capitalize">
                             <a class="fs-4" href="{{ route('detail-produit', $item['slug']) }}">{{ $item['title'] }} </a>
                            <br><span class="fs-5">{{number_format($item['price'] , 0 , ',', ' ')}} <small>FCFA</small> </span>
                            </p>
                            

                    </div>
                @endforeach


            </div>
        </div>
    </div>
@endif
<!--==============================
Petite section de publicité
==============================-->
