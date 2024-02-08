  <style>
      img {
          max-width: 180px;
      }

      input[type=file] {
          padding: 10px;
          background: #eaeaea;
      }
  </style>

  @php
      $msg_validation = 'Champs obligatoire';
  @endphp

  <script>
      function readURL(input) {
          let noimage =
              "https://ami-sni.com/wp-content/themes/consultix/images/no-image-found-360x250.png";
          if (input.files && input.files[0]) {
              var reader = new FileReader();

              reader.onload = function(e) {
                  $('#img-preview')
                      .attr('src', e.target.result);
              };


              reader.readAsDataURL(input.files[0]);
          } else {
              $("#img-preview").attr("src", noimage);
          }
      }

      // ######################
      function _readURL(input) {
          let _noimage =
              "https://ami-sni.com/wp-content/themes/consultix/images/no-image-found-360x250.png";
          if (input.files && input.files[0]) {
              var reader = new FileReader();

              reader.onload = function(e) {
                  $('#_img-preview')
                      .attr('src', e.target.result);
              };


              reader.readAsDataURL(input.files[0]);
          } else {
              $("#_img-preview").attr("src", _noimage);
          }
      }
  </script>


  <!-- Modal with form -->
  <div class="modal fade" id="modalAddCategory" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="formModal">Ajouter une categorie</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form action="{{ route('category.store') }}" class="needs-validation" novalidate="" method="post"
                      enctype="multipart/form-data">
                      @csrf
                      <div class="card-body">
                          <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Nom de la categorie</label>
                              <div class="col-sm-9">
                                  <input type="text" name="name" class="form-control" required="">
                                  <div class="invalid-feedback">
                                      {{ $msg_validation }}
                                  </div>
                              </div>
                          </div>

                          {{-- <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Type</label>
                              <div class="col-sm-9">
                                  <select name="type" class="form-control selectric " required>
                                      <option disabled selected value>Choisir un type</option>
                                      <option value="principale">Principale</option>
                                      <option value="section">Section</option>
                                      <option value="pack">Pack</option>

                                  </select>
                                  <div class="invalid-feedback">
                                      Champ obligatoire
                                  </div>

                              </div>
                          </div> --}}

                          {{-- <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Type d'affichage</label>
                              <div class="col-sm-9">
                                  <select name="type_affichage" class="form-control selectric " required>
                                      <option disabled selected value>Choisir un type d'affichage</option>
                                      <option value="bloc">bloc</option>
                                      <option value="carrousel">carrousel</option>
                                  </select>
                                  <div class="invalid-feedback">
                                      Champ obligatoire
                                  </div>

                              </div>
                          </div> --}}

                          <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Image de la categorie </label>
                              <div class="col-sm-9">
                                  <img id="img-preview"
                                      src="https://ami-sni.com/wp-content/themes/consultix/images/no-image-found-360x250.png"
                                      width="250px" />
                                  <input type="file" name="cat_img" class="form-control" onchange="readURL(this);">
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-sm-3 col-form-label">Banniere de la categorie (1920 * 350) </label>
                              <div class="col-sm-9">
                                  <img id="_img-preview"
                                      src="https://ami-sni.com/wp-content/themes/consultix/images/no-image-found-360x250.png"
                                      width="250px" />
                                  <input type="file" name="cat_banner" class="form-control"
                                      onchange="_readURL(this);" required="">
                                  <div class="invalid-feedback">
                                      {{ $msg_validation }}
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
