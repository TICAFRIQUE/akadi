<div class="tab-pane fade show active" id="couponGroupe" role="tabpanel" aria-labelledby="home-tab3">
    <form class="needs-validation" novalidate="" action="{{ route('coupon.store') }}" method="post"
        enctype="multipart/form-data">
        @csrf
        <div class="card-body">

            <!-- ========== Start Remise ========== -->

            <div class="form-group row">
                <p class="fw-bold fs-2 col-12" id="MsgError"></p>


                <div class="col-sm-3">
                    <label class="col-sm-12 col-form-label">Nom du coupon</label>
                    <input type="text" id="nameCoupon" name="nom" class="form-control" required>
                </div>

                <div class="col-sm-3">
                    <label class="col-sm-12 col-form-label">Code coupon <span class="text-danger">(cliquez pour
                            generer)</span></label>
                    <div class="d-flex">
                        <button class="btn btn-primary" id="codeCouponBtn">Generer</button>

                        <input type="text" id="codeCoupon" name="code" class="form-control" required>
                        <div class="invalid-feedback">
                            Champs obligatoire
                        </div>
                    </div>
                </div>


                <div class="col-sm-3">
                    <label class="col-sm-12 col-form-label">Quantité à generer</label>
                    <input type="number" name="quantite" class="form-control" required>
                </div>

                <div class="col-sm-3">
                    <label class="col-sm-12 col-form-label">Nombre d'utilisations par utilisateur</label>

                    <input type="number" name="utilisation_max" class="form-control" required>
                </div>

                <div class="col-sm-3">
                    <label class="col-sm-12 col-form-label">Type de remise</label>
                    <select name="type_remise" class="form-control" id="typeRemise" required>
                        <option value="">Choisir</option>
                        <option value="pourcentage">Pourcentage</option>
                        <option value="montant">Montant</option>
                    </select>
                    <div class="invalid-feedback">
                        Champs obligatoire
                    </div>
                </div>

                <div class="col-sm-3">
                    <label class="col-sm-12 col-form-label">Valeur de la remise </label>
                    <input type="number" id="valeurRemise" name="valeur_remise" class="form-control" required>
                    <div class="invalid-feedback">
                        Champs obligatoire
                    </div>
                </div>

                <div class="col-sm-3">
                    <label class="col-sm-12 col-form-label">Montant minimum d'achat</label>
                    <input type="number" name="montant_min" class="form-control" required>
                    <div class="invalid-feedback">
                        Champs obligatoire
                    </div>
                </div>

                <div class="col-sm-3">
                    <label class="col-sm-12 col-form-label">Montant maximum d'achat</label>
                    <input type="number" name="montant_max" class="form-control">

                </div>


                <div class="col-sm-3">
                    <label class="col-sm-12 col-form-label">Date Debut</label>

                    <input type="text" id="date_start" name="date_debut" class="form-control datetimepicker"
                        required>
                    <div class="invalid-feedback">
                        Champs obligatoire
                    </div>
                </div>

                <div class="col-sm-3">
                    <label class="col-sm-12 col-form-label">Date Fin</label>

                    <input type="text" id="date_end" name="date_fin" class="form-control datetimepicker" required>

                    <div class="invalid-feedback">
                        Champs obligatoire
                    </div>
                </div>

                <input type="text" name="type_coupon" value="groupe" hidden>

            </div>
            <!-- ========== End Remise ========== -->





            <hr>



            <!-- ========== Start customers and products ========== -->
            {{-- <div class="form-group row">
                <div class="col-md-6">
                    <label class="col-sm-12 col-form-label">Clients</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="customers" id="customerCheckboxAll"
                            value="customerAllChecked">
                        <label class="form-check-label" for="customerCheckboxAll">Tous
                            sélectionner</label>
                    </div>

                    <select name="customers[]" id="customer" class="form-control select2 " multiple required>
                        @foreach ($customer as $item)
                            <option value="{{ $item['id'] }}"> {{ $item['name'] }} </option>
                        @endforeach

                    </select>

                </div>

                <div class="col-md-6">
                    <label class="col-sm-12 col-form-label">Produits</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="products" id="productCheckboxAll"
                            value="productAllChecked">
                        <label class="form-check-label" for="productCheckboxAll">Tous
                            sélectionner</label>
                    </div>

                    <select name="products[]" id="product" class="form-control select2 " multiple required>
                        @foreach ($product as $item)
                            <option value="{{ $item['id'] }}"> {{ $item['title'] }} </option>
                        @endforeach
                    </select>
                </div>

            </div> --}}
            <!-- ========== End customers and products ========== -->


            <hr>
            <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                <div class="col-sm-12 col-md-7 text-lg-right">
                    <button type="submit" class="btn btn-primary btn-submit w-100">Enregistrer</button>
                </div>
            </div>
        </div>

    </form>
</div>
