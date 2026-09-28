@extends('admin.layouts.app')

@php
    $labels = [
        'slider'       => 'Slider (carrousel d\'accueil)',
        'arriere-plan' => 'Image de fond',
        'top-promo'    => 'Top Promo',
        'annonce'      => 'Bandeau d\'annonce',
    ];
    $label = $labels[$type] ?? $type;
@endphp

@section('title', 'publicite')
@section('sub-title', $label)

@section('content')
    <style>
        img { max-width: 180px; }
        input[type=file] { padding: 10px; background: #eaeaea; }
    </style>

    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    @include('admin.components.validationMessage')

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4>{{ $label }}</h4>
                            <button type="button" data-toggle="modal" data-target="#modalAdd-{{ $type }}"
                                class="btn btn-primary">Ajouter</button>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            @if (in_array('image', $champs))
                                                <th>Image</th>
                                            @endif
                                            @if (in_array('texte', $champs))
                                                <th>Texte</th>
                                            @endif
                                            <th>Statut</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($publicite as $key => $item)
                                            <tr id="row_{{ $item['id'] }}">
                                                <td>{{ ++$key }}</td>
                                                @if (in_array('image', $champs))
                                                    <td>
                                                        <img src="{{ $item->getFirstMediaUrl('publicite_image') }}"
                                                            alt="{{ $label }}" width="60">
                                                    </td>
                                                @endif
                                                @if (in_array('texte', $champs))
                                                    <td>{{ Str::limit(strip_tags($item->texte), 60) }}</td>
                                                @endif
                                                <td class="align-middle">
                                                    <span
                                                        class="status badge badge-{{ $item['status'] == 'active' ? 'success' : 'danger' }}">{{ $item->status }}</span>
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown"
                                                            class="btn btn-warning dropdown-toggle">Options</a>
                                                        <div class="dropdown-menu">
                                                            <a href="{{ route('publicite.edit', $item['id']) }}"
                                                                class="dropdown-item has-icon"><i class="far fa-edit"></i>
                                                                Edit</a>
                                                            <a href="#" role="button"
                                                                data-state="{{ $item['status'] }}"
                                                                data-id="{{ $item['id'] }}"
                                                                class="dropdown-item has-icon changeState"><i
                                                                    class="fas fa-toggle-{{ $item['status'] == 'active' ? 'off' : 'on' }}"></i>
                                                                {{ $item['status'] == 'active' ? 'Desactiver' : 'Activer' }}
                                                            </a>
                                                            <a href="#" role="button" data-id="{{ $item['id'] }}"
                                                                class="dropdown-item has-icon text-danger delete"><i
                                                                    class="far fa-trash-alt"></i>Delete</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Aucun élément pour le moment.</td>
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

    {{-- Modal de création, propre à ce type : plus de <select> ni de JS pour afficher/cacher des champs --}}
    <div class="modal fade" id="modalAdd-{{ $type }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter — {{ $label }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('publicite.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">

                        <div class="form-group row">
                            @if (in_array('dates', $champs))
                                <div class="col-sm-6">
                                    <label class="col-form-label">Date début</label>
                                    <input type="text" name="date_debut_pub" class="form-control datetimepicker-{{ $type }}">
                                </div>
                                <div class="col-sm-6">
                                    <label class="col-form-label">Date fin</label>
                                    <input type="text" name="date_fin_pub" class="form-control datetimepicker-{{ $type }}">
                                </div>
                            @endif
                            @if (in_array('discount', $champs))
                                <div class="col-sm-6 mt-2">
                                    <label class="col-form-label">Réduction %</label>
                                    <input type="number" name="discount" class="form-control">
                                </div>
                            @endif
                        </div>

                        @if (in_array('lien', $champs))
                            <div class="form-group row">
                                <div class="col-sm-8">
                                    <label class="col-form-label">Lien de redirection</label>
                                    <input type="url" name="url" class="form-control">
                                </div>
                                <div class="col-sm-4">
                                    <label class="col-form-label">Nom du bouton</label>
                                    <input type="text" name="button_name" class="form-control">
                                </div>
                            </div>
                        @endif

                        @if (in_array('texte', $champs))
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label class="col-form-label">Texte</label>
                                    <textarea name="texte" class="form-control summernote"></textarea>
                                </div>
                            </div>
                        @endif

                        @if (in_array('image', $champs))
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label class="col-form-label">Image</label>
                                    <input type="file" name="image" class="form-control" required>
                                </div>
                            </div>
                        @endif

                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-primary w-100">Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            @if (in_array('dates', $champs))
                $(".datetimepicker-{{ $type }}").each(function() {
                    $(this).datetimepicker({
                        showOtherMonths: true,
                        selectOtherMonths: true,
                        changeMonth: true,
                        changeYear: true,
                        showButtonPanel: true,
                        dateFormat: 'yy-mm-dd',
                        minDate: 0
                    });
                });
            @endif

            // Supprimer
            $(document).on("click", ".delete", function(e) {
                e.preventDefault();
                var Id = $(this).attr('data-id');
                swal({
                    title: "Suppression",
                    text: "Veuillez confirmer la suppression",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Confirmer",
                    cancelButtonText: "Annuler",
                }).then((result) => {
                    if (result) {
                        $.ajax({
                            type: "POST",
                            url: "/admin/publicite/destroy/" + Id,
                            dataType: "json",
                            data: { _token: '{{ csrf_token() }}' },
                            success: function(response) {
                                if (response.status === 200) {
                                    $('#row_' + Id).remove();
                                }
                            }
                        });
                    }
                });
            });

            // Activer / désactiver
            $(document).on("click", ".changeState", function(e) {
                e.preventDefault();
                var Id = $(this).attr('data-id');
                var status = $(this).attr("data-state");

                $.ajax({
                    type: "GET",
                    url: "{{ route('publicite.changeState') }}",
                    data: { id: Id, state: status },
                    dataType: "json",
                    success: function(response) {
                        if (response.success == 200) {
                            location.reload();
                        }
                    }
                });
            });
        });
    </script>
@endsection
