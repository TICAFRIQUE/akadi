<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Ajouter un libellé depense</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="row g-3 needs-validation" method="post" action="{{ route('libelle-depense.store') }}"
                    novalidate>
                    @csrf

                    <div class="col-md-12">
                        <label for="validationCustom01" class="form-label">Categorie</label>
                        <select name="categorie_depense_id" class="form-control" required>
                            <option disabled selected value="">Selectionner</option>
                            @foreach ($categorie_depense as $item)
                                <option value="{{ $item['id'] }}"> {{ $item['libelle'] }} </option>
                            @endforeach
                        </select>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="validationCustom01" class="form-label">Libelle</label>
                        <input type="text" name="libelle" class="form-control" id="validationCustom01" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="validationCustom01" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="" cols="30" rows="10"></textarea>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary ">Valider</button>
            </div>
            </form>
        </div>
    </div>
</div>
