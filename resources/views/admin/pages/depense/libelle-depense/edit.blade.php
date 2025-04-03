<!-- Default Modals -->
<div id="myModalEdit{{ $item['id'] }}" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Modification </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <form class="row g-3 needs-validation" method="post"
                    action="{{ route('libelle-depense.update', $item['id']) }}" novalidate>
                    @csrf

                    <div class="row">
                       

                        <div class="col-md-12 mb-3">
                            <label for="validationCustom01" class="form-label">Categorie</label>
                            <select name="categorie_depense_id" class="form-control" required>
                                <option disabled selected value="">Selectionner</option>
                                @foreach ($categorie_depense as $data)
                                    <option value="{{ $data['id'] }}"
                                        {{ $data['id'] == $item['categorie_depense_id'] ? 'selected' : '' }}>
                                        {{ $data['libelle'] }} </option>
                                @endforeach
                            </select>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>


                        <div class="col-md-12 mb-3">
                            <label for="validationCustom01" class="form-label">Libelle</label>
                            <input type="text" name="libelle" value="{{ $item['libelle'] }}" class="form-control"
                                id="validationCustom01" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="validationCustom01" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="" cols="30" rows="10">
                                {{ $item['description'] }}
                             </textarea>
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
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
