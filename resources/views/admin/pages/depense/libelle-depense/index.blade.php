@extends('admin.layouts.app')

@section('title', 'libelle-depense')
@section('sub-title', 'Liste des libellé depenses')



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
                            <h4>Libellé depense</h4>
                            <button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-primary">Ajouter
                                un libellé depense</button>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Categorie</th>
                                            <th>Libelle</th>
                                            <th>Créer par</th>
                                            <th>Date creation</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data_libelleDepense as $key => $item)
                                            <tr>
                                                <td> {{ ++$key }} </td>
                                                <td>{{ $item['categorie_depense']['libelle'] ?? '' }}</td>
                                                <td> {{ $item['libelle'] }}</td>
                                                <td> {{ $item['user']['name'] }}</td>
                                                <td> {{ $item['created_at'] }} </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown"
                                                            class="btn btn-warning dropdown-toggle">Options</a>
                                                        <div class="dropdown-menu">
                                                            {{-- <a href="#" role="button" class="dropdown-item has-icon"
                                                                data-toggle="modal"
                                                                data-target="#myModalPosition{{ $item['id'] }}"><i
                                                                    class="fas fa-eye"></i> Position</a> --}}


                                                            <a href="{{ route('libelle-depense.edit', $item->id) }}"
                                                                class="dropdown-item has-icon"><i class="far fa-edit"></i>
                                                                Modifier</a>



                                                            <a href="#" role="button" data-id="{{ $item['id'] }}"
                                                                class="dropdown-item has-icon text-danger delete"><i
                                                                    class="far fa-trash-alt"></i>Delete</a>

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
    @include('admin.pages.depense.libelle-depense.create')

    <script>
        $(document).ready(function() {
            $('.delete').on("click", function(e) {
                e.preventDefault();
                var Id = $(this).attr('data-id');
                swal({
                    title: "Suppression",
                    text: "Veuillez confirmer la suppression",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Confirmer",
                    cancelButtonText: "Annuler",
                }).then((result) => {
                    if (result) {
                        $.ajax({
                            type: "POST",
                            url: "/admin/libelle-depense/destroy/" + Id,
                            dataType: "json",
                            data: {
                                _token: '{{ csrf_token() }}',

                            },
                            success: function(response) {
                                if (response.status === 200) {
                                    Swal.fire({
                                        toast: true,
                                        icon: 'success',
                                        title: 'La categorie a été supprimée avec succès!',
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
                                        window.location.href =
                                            "{{ route('libelle-depense.index') }}";
                                    }, 500);
                                }
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
