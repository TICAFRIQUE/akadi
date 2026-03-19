@extends('admin.layouts.app')
@section('title', 'Sorties de Stock')
@section('sub-title', 'Liste des sorties de stock')

@section('content')
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Liste des sorties</h4>
                            <div class="card-header-action">
                                <a href="{{ route('sortie-stock.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Nouvelle sortie
                                </a>
                                <a href="{{ route('sortie-stock.rapport') }}" class="btn btn-info">
                                    <i class="fas fa-chart-bar"></i> Rapport
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tableExport">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Numéro</th>
                                            <th>Date</th>
                                            <th>Produit</th>
                                            <th>Quantité</th>
                                            <th>Motif</th>
                                            <th>Utilisateur</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sorties as $sortie)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td><strong>{{ $sortie->numero }}</strong></td>
                                                <td>{{ $sortie->date_sortie->format('d/m/Y') }}</td>
                                                <td>{{ $sortie->productBase->nom }}</td>
                                                <td>{{ number_format($sortie->quantite, 2) }}
                                                    {{ $sortie->productBase->unite }}</td>
                                                <td>
                                                    <span
                                                        class="badge badge-{{ $sortie->motif == 'Casse' ? 'danger' : ($sortie->motif == 'Don' ? 'success' : 'warning') }}">
                                                        {{ $sortie->motif }}
                                                    </span>
                                                </td>
                                                <td>{{ $sortie->user->name }}</td>
                                                <td>
                                                    <a href="{{ route('sortie-stock.show', $sortie) }}"
                                                        class="btn btn-sm btn-info" data-toggle="tooltip" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form action="{{ route('sortie-stock.destroy', $sortie) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Êtes-vous sûr ? Le stock sera restauré.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            data-toggle="tooltip" title="Supprimer">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
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
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Initialiser les tooltips
            $('[data-toggle="tooltip"]').tooltip();

            // Initialiser DataTable avec export
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
