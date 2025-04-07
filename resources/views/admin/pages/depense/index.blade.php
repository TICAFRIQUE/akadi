@extends('admin.layouts.app')

@section('title', 'depense')
@section('sub-title', 'Liste des depenses')



@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div>
                        @include('admin.components.validationMessage');
                    </div>
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>Depenses

                                @if (request()->has('categorie') && request('categorie') != null)
                                    -
                                    <strong>{{ ucfirst(App\Models\CategorieDepense::find(request('categorie'))->libelle) }}</strong>
                                @endif

                                @if (request()->has('periode') && request('periode') != null)
                                    -
                                    <strong>{{ request('periode') }}</strong>
                                @endif

                                @if (request('date_debut') || request('date_fin'))
                                    du
                                    @if (request('date_debut'))
                                        {{ \Carbon\Carbon::parse(request('date_debut'))->format('d/m/Y') }}
                                    @endif
                                    @if (request('date_fin'))
                                        au {{ \Carbon\Carbon::parse(request('date_fin'))->format('d/m/Y') }}
                                    @endif
                                @endif
                            </h4>
                            <a href="{{route('depense.create')}}"  class="btn btn-primary">Ajouter
                                une depense</a>



                        </div>



                        <div class="card-body">
                            <form action="{{ route('depense.index') }}" method="GET">
                                @csrf
                                <div class="row mx-3">
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="statut" class="form-label">Categorie</label>
                                            <select class="form-control" id="categorie" name="categorie">
                                                <option value="">Toutes les depenses</option>
                                                @foreach (App\Models\CategorieDepense::get() as $key => $value)
                                                    <option value="{{ $value->id }}"
                                                        {{ request('categorie') == $value->id ? 'selected' : '' }}>
                                                        {{ $value->libelle }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="date_debut" class="form-label">Date de début</label>
                                            <input type="date" class="form-control" id="date_debut" name="date_debut"
                                                value="{{ request('date_debut') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label for="date_fin" class="form-label">Date de fin</label>
                                            <input type="date" class="form-control" id="date_fin" name="date_fin"
                                                value="{{ request('date_fin') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="statut" class="form-label">Periodes</label>
                                            <select class="form-control" id="periode" name="periode">
                                                <option value="">Toutes les periodes</option>
                                                @foreach (['jour' => 'Jour', 'semaine' => 'Semaine', 'mois' => 'Mois', 'annee' => 'Année'] as $key => $value)
                                                    <option value="{{ $key }}"
                                                        {{ request('periode') == $key ? 'selected' : '' }}>
                                                        {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mt-4">
                                        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                                    </div>

                                </div>

                            </form>
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                        <tr>
                                            <th>#</th>
                                            <th>Catégorie</th>
                                            <th>Libellé</th>
                                            <th>Montant</th>
                                            <th>Créé par</th>
                                            <th>Date de création</th>
                                            <th>Actions</th>
                                        </tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data_depense as $key => $item)
                                            <tr>
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $item['categorie_depense']['libelle'] ?? '' }}</td>
                                                <td>{{ $item['libelle_depense']['libelle'] ?? $item['categorie_depense']['libelle'] }}
                                                </td>
                                                <td>{{ number_format($item['montant'], 0, ',', ' ') }}</td>
                                                <td>{{ $item['user']['name'] ?? '' }}</td>
                                                <td>{{ $item['created_at']->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" data-toggle="dropdown"
                                                            class="btn btn-warning dropdown-toggle">Options</a>
                                                        <div class="dropdown-menu">


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
                            url: "/admin/depense/destroy/" + Id,
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
                                            "{{ route('depense.index') }}";
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
