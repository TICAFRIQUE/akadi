@extends('admin.layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Fournisseurs</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Fournisseurs</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Liste des fournisseurs</h4>
                        <div class="card-header-action">
                            <a href="{{ route('fournisseur.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Nouveau fournisseur
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="tableExport">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nom</th>
                                        <th>Téléphone</th>
                                        <th>Email</th>
                                        <th>Adresse</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fournisseurs as $fournisseur)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>{{ $fournisseur->nom }}</strong></td>
                                            <td>{{ $fournisseur->telephone ?? '-' }}</td>
                                            <td>{{ $fournisseur->email ?? '-' }}</td>
                                            <td>{{ $fournisseur->adresse ?? '-' }}</td>
                                            <td>
                                                @if($fournisseur->actif)
                                                    <span class="badge badge-success">Actif</span>
                                                @else
                                                    <span class="badge badge-danger">Inactif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('fournisseur.show', $fournisseur) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('fournisseur.edit', $fournisseur) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('fournisseur.destroy', $fournisseur) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Supprimer">
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
            "order": [[1, "asc"]],
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });
</script>
@endpush
