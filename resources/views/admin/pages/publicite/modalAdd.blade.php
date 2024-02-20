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
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="formModal">Nouveau slide</h5>
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
                              <label class="col-sm-3 col-form-label">Type</label>
                              <div class="col-sm-9">
                                  <select name="type" class="form-control selectric " required>
                                      <option disabled selected value>Choisir un type</option>
                                      @php
                                        $type = ['slider','popup','arriere-plan']
                                      @endphp
                                      @foreach ($type as $item)
                                      <option class="text-capitalize" value="{{$item}}"> {{$item}} </option>
                                      @endforeach
                                     
                                  </select>
                                  <div class="invalid-feedback">
                                      Champ obligatoire
                                  </div>

                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Lien</label>
                              <div class="col-sm-9">
                                  <input type="url" name="url" class="form-control">
                                  <div class="invalid-feedback">
                                      entrer le lien de redirection
                                  </div>
                              </div>
                          </div>

                            <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Texte</label>
                              <div class="col-sm-9">
                                  <input type="text" name="texte" class="form-control">
                                  <div class="invalid-feedback">
                                      entrer un texte
                                  </div>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-sm-3 col-form-label">image</label>
                              <div class="col-sm-9">
                                  <img id="img-preview"
                                      src="https://ami-sni.com/wp-content/themes/consultix/images/no-image-found-360x250.png"
                                      width="250px" />
                                  <div>
                                      <input type="file" name="image" class="form-control"
                                          onchange="readURL(this);" required="">
                                      <div class="invalid-feedback">
                                          Champs obligatoire
                                      </div>
                                  </div>
                              </div>
                          </div>

                      </div>
                      <div class="card-footer text-right">
                          <button type="submit" class="btn btn-primary">Valider</button>
                      </div>
                  </form>
              </div>
          </div>
      </div>
  </div>
