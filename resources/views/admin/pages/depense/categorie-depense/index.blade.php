@extends('admin.layouts.app')

@section('title', 'categorie-depense')
@section('sub-title', 'Liste des categories depense')



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
                            <h4>Categories depense</h4>
                            <button type="button" data-toggle="modal" data-target="#modalAddCategory"
                                class="btn btn-primary">Ajouter
                                une categorie depense</button>
                        </div>


                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>statut</th>
                                            <th>Libelle</th>
                                            <th>Date creation</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data_categorie_depense as $key => $item)
                                            <tr>
                                                <td> {{ ++$key }} </td>
                                                <td>{{ $item['statut'] }}</td>
                                                <td> {{ $item['libelle'] }}</td>
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


                                                            <a href="{{ route('categorie-depense.edit', $item->id) }}"
                                                                class="dropdown-item has-icon"><i class="far fa-edit"></i>
                                                                Modifier</a>



                                                            <a href="#" role="button" data-id="{{ $item['id'] }}"
                                                                class="dropdown-item has-icon text-danger delete"><i
                                                                    class="far fa-trash-alt"></i>Delete</a>

                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            @include('admin.pages.depense.categorie-depense.position')
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
    @include('admin.pages.depense.categorie-depense.create')

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
                            url: "/admin/categorie-depense/destroy/" + Id,
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
                                            "{{ route('categorie-depense.index') }}";
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
