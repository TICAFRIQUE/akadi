@extends('admin.layouts.app')
@section('title', 'achats')
@section('sub-title', 'Liste des achats')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Achats de produits de base</h4>
                            <div class="card-header-action">
                                <a href="{{ route('achat.rapport') }}" class="btn btn-info mr-2">
                                    <i class="fas fa-chart-bar"></i> Rapport
                                </a>
                                <a href="{{ route('achat.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Nouvel achat
                                </a>
                            </div>
                        </div>

                        @include('admin.components.validationMessage')

                        <div class="card-body">
                            {{-- Filtre de dates --}}
                            <form method="GET" action="{{ route('achat.index') }}" class="mb-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="date_debut">Date de début</label>
                                            <input type="date" class="form-control" id="date_debut" name="date_debut"
                                                value="{{ $dateDebut }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="date_fin">Date de fin</label>
                                            <input type="date" class="form-control" id="date_fin" name="date_fin"
                                                value="{{ $dateFin }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary w75 mt-4">
                                            <i class="fas fa-filter"></i> Filtrer
                                        </button>
                                        <a href="{{ route('achat.index') }}" class="btn btn-secondary mt-4">
                                            <i class="fas fa-undo"></i> Réinitialiser
                                        </a>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-striped" id="tableExport">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>N° Achat</th>
                                            <th>Date</th>
                                            <th>Fournisseur</th>
                                            <th>Nb produits</th>
                                            <th>Montant total</th>
                                            <th>Utilisateur</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($achats as $achat)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>
                                                    <strong>{{ $achat->numero }}</strong>
                                                </td>
                                                <td data-order="{{ $achat->date_achat->format('Y-m-d') }} {{ $achat->created_at->format('H:i:s') }}">
                                                    {{ $achat->date_achat->format('d/m/Y') }}</td>
                                                <td>{{ $achat->fournisseur ?? '-' }}</td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        {{ $achat->lignes->count() }} produit(s)
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong>{{ format_price($achat->montant_total) }}
                                                        FCFA</strong>
                                                </td>
                                                <td>{{ $achat->user->name ?? '-' }}</td>
                                                <td>
                                                    <a href="{{ route('achat.show', $achat->id) }}"
                                                        class="btn btn-info btn-sm" data-toggle="tooltip"
                                                        title="Voir détails">
                                                        <i class="fa fa-eye"></i>
                                                    </a>

                                                    @role('developpeur')
                                                    <!--edit achat-->
                                                    <a href="{{ route('achat.edit', $achat->id) }}"
                                                        class="btn btn-primary btn-sm" data-toggle="tooltip"
                                                        title="Modifier">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <!--end edit achat-->

                                                    <!--delete achat-->
                                                    <form action="{{ route('achat.destroy', $achat->id) }}" method="POST"
                                                        style="display: inline-block;"
                                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet achat ? Le stock sera décrémenté.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm"
                                                            data-toggle="tooltip" title="Supprimer">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    <!--end delete achat-->
                                                    @endrole
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">
                                                    <p class="text-muted">Aucun achat trouvé</p>
                                                </td>
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
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();

            $('#tableExport').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
                },
                "order": [
                    [2, "desc"]
                ],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>
@endpush
