<!-- Modal trigger button -->

<!-- Modal Body -->
<!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
<div class="modal fade" id="modalId{{ $item['id'] }}" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">
                    Pourquoi voulez vous annuler la commande ?
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{route('cancel-order', $item['id'])}}" method="post">
                @csrf
                <div class="modal-body">
                    <label for="">Motif d'annulation de la commande</label>
                    <textarea class="form-control" name="motif" id="" cols="30" rows="10" required></textarea>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">
                        Fermer
                    </button>
                    <button type="submit" class="btn btn-light text-white"
                        style="background-color: rgb(255, 131, 15)">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Optional: Place to the bottom of scripts -->
{{-- <script>
    const myModal = new bootstrap.Modal(
        document.getElementById("modalId"),
        options,
    );
</script> --}}
