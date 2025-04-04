
<div class="modal fade" id="myModalAddLibelle" tabindex="-1" role="dialog" aria-labelledby="formModal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Ajouter une categorie</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formSave" class="row g-3 needs-validation" method="post" novalidate>
                    @csrf

                    <input type="text" name="categorie_depense_id" class="form-control categorieId" hidden>

                    <div class="col-md-12">
                        <label for="validationCustom01" class="form-label">Libelle</label>
                        <input type="text" name="libelle" class="form-control libelleDepense" id="validationCustom01"
                            required>
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
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fermer</button>
                <button type="submit" class="btn btn-primary ">Valider</button>
            </div>
            </form>
        </div>
    </div>
</div>
