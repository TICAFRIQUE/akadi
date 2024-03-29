  <style>
      img {
          max-width: 180px;
      }

      input[type=file] {
          padding: 10px;
          background: #eaeaea;
      }
  </style>

  <script>
      //Change this to your no-image file
      // ######################
      function readURL(input) {
          let _noimage =
              "https://ami-sni.com/wp-content/themes/consultix/images/no-image-found-360x250.png";
          if (input.files && input.files[0]) {
              var reader = new FileReader();

              reader.onload = function(e) {
                  $('#img-preview')
                      .attr('src', e.target.result);
              };


              reader.readAsDataURL(input.files[0]);
          } else {
              $("#_img-preview").attr("src", _noimage);
          }
      }
  </script>


  <!-- Modal with form -->
  <div class="modal fade" id="modalAddpublicite" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="formModal">Créer une publicité</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form action="{{ route('publicite.store') }}" class="needs-validation" novalidate="" method="post"
                      enctype="multipart/form-data">
                      @csrf
                      <div class="card-body">
                          <div class="form-group row">
                              <p class="fw-bold fs-2 col-12" id="MsgError"></p>

                              <div class="col-sm-3">
                                  <label class="col-sm-12 col-form-label">Type de la publicite</label>
                                  <select name="type" class="form-control selectric " required>
                                      <option disabled selected value>Choisir un type</option>
                                      @php
                                          $type = [
                                              'slider',
                                              //   'popup',
                                              'arriere-plan',
                                              'banniere',
                                              'small-card',
                                              'top-promo',
                                          ];
                                      @endphp
                                      @foreach ($type as $item)
                                          <option class="text-capitalize" value="{{ $item }}">
                                              {{ $item }} </option>
                                      @endforeach

                                  </select>
                                  <div class="invalid-feedback">
                                      Champ obligatoire
                                  </div>

                              </div>

                              <div class="col-sm-3">
                                  <label class="col-sm-12 col-form-label">Date Debut de la publicité</label>

                                  <input type="text" id="date_start" name="date_debut_pub"
                                      class="form-control datetimepicker">
                              </div>

                              <div class="col-sm-3">
                                  <label class="col-sm-12 col-form-label">Date Fin de la publicité</label>

                                  <input type="text" id="date_end" name="date_fin_pub"
                                      class="form-control datetimepicker">
                              </div>

                              <div class="col-sm-3">
                                  <label class="col-sm-12 col-form-label">Reduction %</label>

                                  <input type="number" name="discount" class="form-control">
                              </div>

                          </div>
                          <div class="form-group row">
                              <div class="col-sm-8">
                                  <label class="col-sm-3 col-form-label">Lien de redirection</label>
                                  <input type="url" name="url" class="form-control">
                                  <div class="invalid-feedback">
                                      entrer le lien de redirection
                                  </div>
                              </div>

                              <div class="col-sm-4">
                                  <label class="col-sm-12 col-form-label">Nom du bouton</label>
                                  <input type="text" name="button_name" class="form-control">
                              </div>
                          </div>

                          <div class="form-group row">
                              <div class="col-sm-8">
                                  <label class="col-sm-12 col-form-label">Texte</label>
                                  <textarea name="texte" class="form-control summernote"></textarea>
                              </div>

                              <div class="col-sm-4">
                                  <label class="col-sm-3 col-form-label">image</label>
                                  <img id="img-preview"
                                      src="https://ami-sni.com/wp-content/themes/consultix/images/no-image-found-360x250.png"
                                      width="250px" />
                                  <div>
                                      <input type="file" name="image" class="form-control"
                                          onchange="readURL(this);">
                                      {{-- <div class="invalid-feedback">
                                          Champs obligatoire
                                      </div> --}}
                                  </div>
                              </div>
                          </div>


                      </div>
                      <div class="card-footer text-right">
                          <button type="submit" class="btn btn-primary w-100">Valider</button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>

  <script>
      $(document).ready(function() {
          //date discount

          $(".datetimepicker").each(function() {
              $(this).datetimepicker({
                  showOtherMonths: true,
                  selectOtherMonths: true,
                  changeMonth: true,
                  changeYear: true,
                  showButtonPanel: true,
                  dateFormat: 'yy-mm-dd',
                  minDate: 0
              });
          });


          $('#date_start').change(function(e) {
              var date_start = $(this).val();
              var date_end = $('#date_end').val();

              if (date_start > date_end) {
                  $('#MsgError').html(
                      'La date debut de la promo ne doit pas etre superieur à la date de fin').css({
                      'color': 'white',
                      'background-color': 'red',
                      'font-size': '16px'
                  });
                  $('.btn-submit').prop('disabled', true)
              } else {
                  $('#MsgError').html(' ')
                  $('.btn-submit').prop('disabled', false)
              }
          });


          $('#date_end').change(function(e) {
              var date_end = $(this).val();
              var date_start = $('#date_start').val();

              if (date_end < date_start) {
                  $('#MsgError').html(
                      'La date fin de la promo ne doit pas etre inferieur à la date de debut').css({
                      'color': 'white',
                      'background-color': 'red',
                      'font-size': '16px'
                  });
                  $('.btn-submit').prop('disabled', true)
              } else {
                  $('#MsgError').html(' ')
                  $('.btn-submit').prop('disabled', false)
              }
          });
      });
  </script>
