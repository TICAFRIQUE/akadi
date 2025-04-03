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
                    action="{{ route('depense.update', $item['id']) }}" novalidate>
                    @csrf

                    <div class="row">
                        <div class="col-md-8">
                            <label for="validationCustom01" class="form-label">Categorie</label>
                            <select name="categorie" class="form-control categorie-select" required>
                                <option disabled selected value="">Selectionner</option>
                                @foreach ($categorie_depense as $data)
                                    <!-- Si la catégorie a des libelleDepenses, rendre l'option non cliquable -->
                                    <option
                                        {{ $data['id'] == $item['categorie_depense_id'] ? 'selected' : '' }}value="{{ $data['id'] }}"
                                        class="categorie" @if ($data->libelleDepenses->isNotEmpty()) disabled @endif>
                                        {{ strtoupper($data['libelle']) }}
                                    </option>

                                    <!-- Boucle pour les libelleDepenses de cette catégorie -->
                                    @foreach ($data->libelleDepenses as $data_libelle)
                                        <option {{ $data_libelle['id'] == $item['libelle_depense_id'] ? 'selected' : '' }}
                                            value="{{ $data_libelle['id'] }}" class="libelle-depense">
                                            &nbsp;&nbsp;&nbsp;&nbsp;{{ $data_libelle['libelle'] }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label for="validationCustom01" class="form-label">Montant</label>
                            <input type="number" name="montant" value="{{ $item['montant'] }}" class="form-control"
                                id="validationCustom01" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label" for="meta-title-input">Date <span
                                    class="text-danger">*</span>
                            </label>
                            <input type="date" id="currentDate" value="{{$item->date_depense}}" name="date_depense"
                                class="form-control" required>
                        </div>
                       
                        <div class="col-md-12">
                            <label for="validationCustom01" class="form-label">Objet</label>
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

