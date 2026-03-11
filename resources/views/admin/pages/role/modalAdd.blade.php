<!-- Modal ajout rôle -->
<div class="modal fade" id="modalAddRole" tabindex="-1" role="dialog" aria-labelledby="roleModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roleModal">Nouveau rôle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('role.store') }}" method="POST" class="needs-validation" novalidate="">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="roleName">Nom du rôle</label>
                        <input type="text" id="roleName" name="name" class="form-control"
                            placeholder="Ex: gestionnaire" required>
                        <div class="invalid-feedback">
                            Champs obligatoire
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
