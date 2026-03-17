
    <div class="space">

        <div class="container">
            <div class="title-area text-center">
                <span class="sub-title">Menu</span>
                <h2 class="sec-title">Parcourir nos catégories <span class="font-style text-theme">Akadi</span></h2>
                <p class="sec-text d-md-none" style="font-size: 14px; color: #666; margin-top: 10px;">
                    <i class="fas fa-hand-point-left"></i> Glissez pour voir plus de catégories <i class="fas fa-hand-point-right"></i>
                </p>
            </div>
            <div class="row th-carousel category-carousel" id="categoryCarousel" data-slide-show="4" data-ml-slide-show="4" data-lg-slide-show="4"
                data-md-slide-show="3" data-sm-slide-show="2" data-xs-slide-show="1.5" data-arrows="true" data-dots="true" style="visibility: hidden;">
               @foreach ($categories as $item)
                     <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6 category-item">
                    <div class="category-border">
                        <div class="category-border_img">
                            <a href="/produit?categorie={{$item['id']}}">
                                <img src="{{ $item->getFirstMediaUrl('category_image') }}"
                                alt="{{ $item['name'] }}" class="img-fluid" loading="lazy">
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