
    <div class="space">

        <div class="container">
            <div class="title-area text-center">
                <span class="sub-title">Menu</span>
                <h2 class="sec-title">Parcourir nos catégories <span class="font-style text-theme">Akadi</span></h2>
            </div>
            <div class="row th-carousel" data-slide-show="4" data-ml-slide-show="4" data-lg-slide-show="4"
                data-md-slide-show="4" data-sm-slide-show="2" data-xs-slide-show="2">
               @foreach ($categories as $item)
                     <div class="col-xl-3 col-lg-3 col-md-3 col-sm2">
                    <div class="category-border">
                        <div class="category-border_img">
                            <a href="/produit?categorie={{$item['id']}}">
                                <img src="{{ $item->getFirstMediaUrl('category_image') }}"
                                alt="{{ $item->getFirstMediaUrl('category_image') }}">
                            </a>
                        </div>
                        
                        <h3 class="category-border_title"><a href="/produit?categorie={{$item['id']}}"> {{$item['name']}} </a></h3>
                        <div class="fire"><img src="{{ asset('site/assets/img/update_2/shape/fire_2.png') }}"
                                alt="shape"></div>
                    </div>
                </div>
               @endforeach
               
            </div>
        </div>
    </div>