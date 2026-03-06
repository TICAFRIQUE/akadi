{{-- Modal Motif Annulation --}}
<div class="modal fade" id="modalMotifAnnulation" tabindex="-1" role="dialog" aria-labelledby="modalMotifLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalMotifLabel">
                    <i class="fas fa-ban mr-2"></i>Annuler la commande
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fermer">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('order.orderCancel') }}" class="needs-validation" novalidate method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Motif d'annulation <span class="text-danger">*</span></label>
                        <select name="motif" class="form-control" id="motif_selected" required>
                            <option selected disabled value>Sélectionner un motif</option>
                            <option value="annulé par le client">Annulé par le client</option>
                            <option value="client injoignable">Client injoignable</option>
                            <option value="probl&#232;me livreur">Problème livreur</option>
                            <option value="mauvais client">Mauvais client</option>
                            <option value="plaisantin">Plaisantin</option>
                            <option value="doublon">Doublon</option>
                            <option value="autre">Autre</option>
                        </select>
                        <div class="invalid-feedback">Champs obligatoire</div>
                    </div>
                    <div class="form-group motif_autre" style="display:none">
                        <label>Pr&#233;ciser le motif <span class="text-danger">*</span></label>
                        <textarea name="motif_autre" id="_motif_autre" rows="3" class="form-control"
                            placeholder="Pr&#233;ciser le motif..."></textarea>
                        <div class="invalid-feedback">Champs obligatoire</div>
                    </div>
                    <input type="hidden" name="commandeId" id="commandeId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban mr-1"></i> Confirmer l'annulation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
