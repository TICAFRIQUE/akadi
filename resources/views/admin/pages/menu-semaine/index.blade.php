@extends('admin.layouts.app')
@section('title', 'Menu de la semaine')
@section('sub-title', 'Liste des menus de la semaine')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Menu de la semaine</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Menu de la semaine</div>
        </div>
    </div>

    <div class="section-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Liste des menus de la semaine</h4>
                        <div class="card-header-action">
                            <a href="{{ route('menu-semaine.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Nouveau menu de la semaine
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Titre</th>
                                        <th>Période</th>
                                        <th>Seuil tarif réduit</th>
                                        <th>Jours</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($menusSemaine as $menuSemaine)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>{{ $menuSemaine->titre_affiche }}</strong></td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($menuSemaine->date_debut)->format('d/m/Y') }}
                                                →
                                                {{ \Carbon\Carbon::parse($menuSemaine->date_fin)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                <span class="text-muted">Dès {{ $menuSemaine->seuil_jours }} jour(s) commandés</span>
                                            </td>
                                            <td>{{ $menuSemaine->menus_jour_count }} jour(s)</td>
                                            <td>
                                                @if($menuSemaine->actif)
                                                    <span class="badge badge-success">Actif</span>
                                                @else
                                                    <span class="badge badge-secondary">Inactif</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('menu-semaine.show', $menuSemaine) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('carte-menu', $menuSemaine->lien_token) }}" target="_blank" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Ouvrir la carte menu partageable">
                                                    <i class="fas fa-share-alt"></i>
                                                </a>
                                                <form action="{{ route('menu-semaine.destroy', $menuSemaine) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce menu de la semaine ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">Aucun menu de la semaine créé pour le moment.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $menusSemaine->links() }}
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
    });
</script>
@endpush
