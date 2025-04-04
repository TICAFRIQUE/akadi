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
                <form class="row g-3 needs-validation" method="post" action="{{ route('categorie-depense.store') }}"
                    novalidate>
                    @csrf
                    <div class="col-md-12">
                        <label for="validationCustom01" class="form-label">Nom de la categorie</label>
                        <input type="text" name="libelle" class="form-control" id="validationCustom01" required>
                        <div class="valid-feedback">
                            Looks good!
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="validationCustom01" class="form-label">Statut</label>
                        <select name="statut" class="form-control">
                            <option value="active">Activé</option>
                            <option value="desactive">Desactivé</option>

                        </select>
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
