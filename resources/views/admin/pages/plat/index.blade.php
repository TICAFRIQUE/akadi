@extends('admin.layouts.app')

@section('title', 'Plats')
@section('sub-title', 'Liste des plats')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div>
                        @include('admin.components.validationMessage');
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>Plats</h4>
                            <button type="button" data-toggle="modal" data-target="#modalPlat" id="btn-add-plat"
                                class="btn btn-primary">Ajouter un plat</button>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Nom</th>
                                            <th>Description</th>
                                            <th>Prix</th>
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($plats as $key => $plat)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $plat->nom }}</td>
                                                <td>{{ $plat->description }}</td>
                                                <td>{{ number_format($plat->prix, 0, ',', ' ') }} FCFA</td>
                                                <td>
                                                    <span class="badge badge-{{ $plat->actif ? 'success' : 'secondary' }}">
                                                        {{ $plat->actif ? 'Actif' : 'Inactif' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-warning btn-edit-plat"
                                                        data-toggle="modal" data-target="#modalPlat"
                                                        data-id="{{ $plat->id }}" data-nom="{{ $plat->nom }}"
                                                        data-description="{{ $plat->description }}"
                                                        data-prix="{{ $plat->prix }}"
                                                        data-actif="{{ $plat->actif ? 1 : 0 }}">
                                                        <i class="far fa-edit"></i> Modifier
                                                    </button>
                                                    <a href="#" role="button" data-id="{{ $plat->id }}"
                                                        class="btn btn-sm btn-danger delete-plat">
                                                        <i class="far fa-trash-alt"></i> Supprimer
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Aucun plat pour le moment.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Modale unique réutilisée pour la création et l'édition --}}
    <div class="modal fade" id="modalPlat" tabindex="-1" role="dialog" aria-labelledby="modalPlatLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="form-plat" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalPlatLabel">Ajouter un plat</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" id="plat-nom" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" id="plat-description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Prix (FCFA) <span class="text-danger">*</span></label>
                            <input type="number" name="prix" id="plat-prix" class="form-control" min="0" step="any" required>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="actif" class="custom-control-input" id="plat-actif" checked>
                                <label class="custom-control-label" for="plat-actif">Actif</label>
                            </div>
                            <small class="text-muted">Seuls les plats actifs sont proposés lors de la création d'un menu du jour.</small>
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

    <script>
        $(document).ready(function() {
            const $form = $('#form-plat');
            const $modalLabel = $('#modalPlatLabel');
            const storeUrl = "{{ route('plats.store') }}";

            function resetModalToCreate() {
                $form.attr('action', storeUrl);
                $modalLabel.text('Ajouter un plat');
                $('#plat-nom').val('');
                $('#plat-description').val('');
                $('#plat-prix').val('');
                $('#plat-actif').prop('checked', true);
            }

            $('#btn-add-plat').on('click', resetModalToCreate);

            $(document).on('click', '.btn-edit-plat', function() {
                const id = $(this).data('id');
                $form.attr('action', "{{ url('admin/plats/update') }}/" + id);
                $modalLabel.text('Modifier le plat');
                $('#plat-nom').val($(this).data('nom'));
                $('#plat-description').val($(this).data('description'));
                $('#plat-prix').val($(this).data('prix'));
                $('#plat-actif').prop('checked', $(this).data('actif') == 1);
            });

            $(document).on('click', '.delete-plat', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                swal({
                    title: "Suppression",
                    text: "Veuillez confirmer la suppression de ce plat",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Confirmer",
                    cancelButtonText: "Annuler",
                }).then((result) => {
                    if (result) {
                        $.ajax({
                            type: "POST",
                            url: "{{ url('admin/plats/destroy') }}/" + id,
                            dataType: "json",
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function(response) {
                                if (response.status === 200) {
                                    Swal.fire({
                                        toast: true,
                                        icon: 'success',
                                        title: 'Le plat a été supprimé avec succès!',
                                        animation: false,
                                        position: 'top',
                                        background: '#3da108e0',
                                        iconColor: '#fff',
                                        color: '#fff',
                                        showConfirmButton: false,
                                        timer: 1000,
                                        timerProgressBar: true,
                                    });
                                    setTimeout(function() {
                                        window.location.href = "{{ route('plats.index') }}";
                                    }, 500);
                                }
                            },
                            error: function(xhr) {
                                var message = xhr.responseJSON && xhr.responseJSON.message
                                    ? xhr.responseJSON.message
                                    : "Une erreur est survenue.";
                                Swal.fire({ icon: 'error', title: 'Suppression impossible', text: message });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
