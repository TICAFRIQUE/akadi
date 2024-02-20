 <div class="tab-pane fade show active" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
     <div class="woocommerce-Reviews">
         <div class="th-comments-wrap ">
             <ul class="comment-list">
                 @foreach ($product['commentaires'] as $item)
                     <li class="review th-comment-item">
                         <div class="th-post-comment">
                             <div class="comment-content">
                                 <h4 class="name"> {{ $item['user']['name'] }} </h4>
                                 <span class="commented-on"><i class="fal fa-calendar-alt"></i>
                                     {{ \Carbon\Carbon::parse($item['created_at'])->diffForHumans() }} </span>
                                 <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">

                                     @php
                                         $star = ($item['note'] / 5) * 100;
                                     @endphp
                                     <span style="width:{{ $star }}%"> </span>
                                 </div>
                                 <p class="text">{{ $item['description'] }}.</p>
                             </div>
                         </div>
                     </li>
                 @endforeach


             </ul>
         </div>

         <!-- ========== Start commentaire formulaire ========== -->
        @auth
             <div class="th-comment-form ">
             <div class="form-title">
                 <h3 class="blog-inner-title ">Donnez votre avis sur le plat</h3>
             </div>
             <div class="row">

                 <form action="{{ route('commentaire') }}">
                     <div class="form-group rating-select d-flex align-items-center">
                         <label>Notez le plat</label>
                         <p class="stars">
                             <span>
                                 <a class="star" href="#">1</a>
                                 <a class="star" href="#">2</a>
                                 <a class="star" href="#">3</a>
                                 <a class="star" href="#">4</a>
                                 <a class="star" href="#">5</a>
                             </span>
                         </p>
                     </div>

                     {{-- <div class="col-md-6 form-group">
                                    <input type="text" placeholder="Your Name" class="form-control">
                                    <i class="text-title far fa-user"></i>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="text" placeholder="Your Email" class="form-control">
                                    <i class="text-title far fa-envelope"></i>
                                </div> --}}
                     <div class="col-12 form-group">
                         <input type="text" id="productId" value="{{ $product['id'] }}" hidden>
                         <textarea placeholder="" class="form-control" id="comment"></textarea>
                         <i class="text-title far fa-pencil-alt"></i>
                     </div>
                 </form>


                 <div class="col-12 form-group mb-0">
                     <button class="th-btn rounded-2 btn-save">Enregistrer</button>
                 </div>
             </div>
         </div>
        @endauth

        @guest
             <div class="col-12 form-group mb-0">
                     <a href="{{route('login')}}" class="th-btn rounded-2">Connectez vous pour laisser un commentaire <i class="fa fa-pen-alt"></i> </a>
                 </div>
        @endguest
         <!-- ========== End commentaire formulaire ========== -->


     </div>
 </div>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
 <script>
     $(document).ready(function() {

         let star = '';
         $('.star').on('click', function(event) {
             var rating = $(this).html();
             star = rating;
             var comment = $('#comment').val();
             event.preventDefault();

         })

         $('.btn-save').click(function(e) {
             e.preventDefault();
             var comment = $('#comment').val();
             var productId = $('#productId').val();

             var rating = star

             if (!rating) {
                 Swal.fire("Donnez une note au plat");
             } else if (!comment) {
                 Swal.fire("veuillez donner votre avis pour ce plat");
             } else {
                 $.ajax({
                     type: "GET",
                     url: "{{ route('commentaire') }}",
                     data: {
                         comment: comment,
                         rating: rating,
                         productId: productId
                     },
                     dataType: "json",
                     success: function(response) {
                         if (response.status == 200) {
                             Swal.fire({
                                 toast: true,
                                 icon: 'success',
                                 title: 'Votre commentaire à été pris en compte',
                                 width: '100%',
                                 animation: false,
                                 position: 'top',
                                 background: '#3da108e0',
                                 iconColor: '#fff',
                                 color: '#fff',
                                 showConfirmButton: false,
                                 timer: 1500,
                                 timerProgressBar: true,
                             });
                             location.reload()
                         }
                     }
                 });
             }

         });
     });
 </script>
