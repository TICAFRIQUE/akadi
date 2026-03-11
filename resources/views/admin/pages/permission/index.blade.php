@extends('admin.layouts.app')
@section('title', 'Permissions')
@section('sub-title', 'Gestion des permissions')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    @include('admin.components.validationMessage')

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="mb-0"><i class="fas fa-shield-alt mr-2"></i>Permissions</h4>
                            <button type="button" data-toggle="modal" data-target="#modalAddPermission"
                                class="btn btn-primary">
                                <i class="fas fa-plus mr-1"></i> Nouvelle permission
                            </button>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="table-permissions">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center" style="width:50px">#</th>
                                            <th>Nom de la permission</th>
                                            <th>Utilisateurs</th>
                                            <th style="width:120px">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($permissions as $key => $perm)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <span class="badge badge-info px-3 py-2">
                                                        <i class="fas fa-key mr-1"></i>{{ $perm->name }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($perm->users_count > 0)
                                                        <span class="badge badge-success px-2 py-1">
                                                            <i class="fas fa-users mr-1"></i>{{ $perm->users_count }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted small">Aucun utilisateur</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown"
                                                            class="btn btn-sm btn-warning dropdown-toggle">Options</a>
                                                        <div class="dropdown-menu">
                                                            <a href="{{ route('permission.edit', $perm->id) }}"
                                                                class="dropdown-item has-icon">
                                                                <i class="far fa-edit mr-1"></i> Modifier
                                                            </a>
                                                            <a href="#" role="button" data-id="{{ $perm->id }}"
                                                                data-name="{{ $perm->name }}"
                                                                class="dropdown-item has-icon text-danger btn-delete">
                                                                <i class="far fa-trash-alt mr-1"></i> Supprimer
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Modal ajout permission --}}
    <div class="modal fade" id="modalAddPermission" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-key mr-2"></i>Nouvelle permission</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form action="{{ route('permission.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="permName">Nom de la permission <span class="text-danger">*</span></label>
                            <input type="text" id="permName" name="name" class="form-control"
                                placeholder="Ex: voir-commandes, modifier-produits…" required>
                            <small class="form-text text-muted">Utiliser des tirets et minuscules. Ex : <code>voir-commandes</code></small>
                            <div class="invalid-feedback">Ce champ est obligatoire.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#table-permissions').DataTable({
                order: [[1, 'asc']],
                pageLength: 25,
            });

            $('.btn-delete').on('click', function (e) {
                e.preventDefault();
                const id   = $(this).data('id');
                const name = $(this).data('name');
                Swal.fire({
                    title: 'Supprimer la permission ?',
                    html: `La permission <strong>${name}</strong> sera supprimée définitivement.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Supprimer',
                    cancelButtonText: 'Annuler',
                    confirmButtonColor: '#e74a3b',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: '/admin/permission/destroy/' + id,
                            data: { _token: '{{ csrf_token() }}' },
                            success: function (res) {
                                if (res.success) {
                                    Swal.fire({ icon: 'success', title: 'Supprimé !', timer: 1500, showConfirmButton: false })
                                        .then(() => location.reload());
                                }
                            },
                            error: function () {
                                Swal.fire('Erreur', 'Une erreur est survenue.', 'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
