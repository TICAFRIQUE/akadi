@php
    $msg_validation = ' Champs obligatoire';
@endphp
<!-- Modal with form -->
<div class="col-md-10 m-auto" id="motif_annulation">
    <form action="{{ route('delivery.store') }}" class="needs-validation" novalidate="" method="post"
        enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">Motif d'annulation </label>
                <div class="col-sm-9 mb-3">
                    <select name="motif" class="form-control" id="motif_selected" required>
                        <option selected disabled value>Selectionner un motif d'annulation</option>
                        <option value="doublon">Doublon</option>
                        <option value="client injoignable">Client injoignable</option>
                        <option value="mauvais client">Mauvais client</option>
                        <option value="plaisantin">Plaisantin</option>
                        <option value="autre">Autre</option>
                    </select>

                    <div class="invalid-feedback">
                        {{ $msg_validation }}
                    </div>
                </div>


                <label class="col-sm-3 col-form-label motif_autre">Preciser un autre motif </label>
                <div class="col-sm-9 motif_autre">
                    <textarea name="motif_autre" id="_motif_autre" cols="30" rows="10" class="form-control" placeholder="Preciser si un autre motif "></textarea>
                </div>
            </div>


        </div>
        <div class="text-right">
            <button type="submit" class="btn btn-primary  btn-action">Valider</button>
            <button  class="btn btn-dark  btn-close">Fermer</button>

        </div>
    </form>
</div>
