<!--==============================
Counter Area  
==============================-->
    <div class="space-bottom">
        <div class="container">
            <div class="row gy-4">
                @foreach ($small_card as $item)
                    
                <div class="col-xl-4 col-md-6">
                    <div class="offer-card" data-bg-src="{{$item->getFirstMediaUrl('publicite_image')}}">
                        <h3 class="offer-title box-title"></h3>
                        <p class="offer-text"></p>
                        <a href="{{$item['url']}}" class="line-btn btn btn-danger text-white">Acheter</a>
                    </div>
                </div>
                @endforeach
               
              
            </div>
        </div>
    </div><!--==============================
Product Area  
==============================-->