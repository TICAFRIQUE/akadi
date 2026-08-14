@extends('admin.layouts.app')
@section('title', 'Menu de la semaine')
@section('sub-title', $menuSemaine->titre)

@section('content')
<section class="section">
    <div class="section-header">
        <h1>{{ $menuSemaine->titre }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('menu-semaine.index') }}">Menu de la semaine</a></div>
            <div class="breadcrumb-item">{{ $menuSemaine->titre }}</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Carte menu partageable</h4>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <input type="text" id="lien-carte-menu" class="form-control" readonly
                                value="{{ route('carte-menu', $menuSemaine->lien_token) }}">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary" type="button" id="btn-copier-lien">
                                    <i class="fas fa-copy"></i> Copier
                                </button>
                                <a href="{{ route('carte-menu', $menuSemaine->lien_token) }}" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-external-link-alt"></i> Ouvrir
                                </a>
                            </div>
                        </div>
                        <small class="text-muted">Partagez ce lien (WhatsApp, réseaux sociaux...) pour que vos clients consultent la carte menu de la semaine.</small>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4>Informations</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Période :</strong><br>
                                {{ \Carbon\Carbon::parse($menuSemaine->date_debut)->format('d/m/Y') }}
                                → {{ \Carbon\Carbon::parse($menuSemaine->date_fin)->format('d/m/Y') }}
                            </div>
                            <div class="col-md-3">
                                <strong>Prix normal :</strong><br>
                                {{ number_format($menuSemaine->prix_normal, 0, ',', ' ') }} FCFA / plat
                            </div>
                            <div class="col-md-3">
                                <strong>Prix réduit :</strong><br>
                                {{ number_format($menuSemaine->prix_reduit, 0, ',', ' ') }} FCFA / plat
                                <span class="text-muted">(dès {{ $menuSemaine->seuil_jours }} jours)</span>
                            </div>
                            <div class="col-md-3">
                                <strong>Statut :</strong><br>
                                @if($menuSemaine->actif)
                                    <span class="badge badge-success">Actif</span>
                                @else
                                    <span class="badge badge-secondary">Inactif</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @foreach ($menuSemaine->menusJour->sortBy('date') as $menuJour)
                    <div class="card">
                        <div class="card-header">
                            <h4>{{ \Carbon\Carbon::parse($menuJour->date)->translatedFormat('l d F Y') }}</h4>
                        </div>
                        <div class="card-body">
                            @if($menuJour->menuProduits->count() === 0)
                                <p class="text-muted mb-0">Aucun plat pour ce jour.</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th>Plat</th>
                                                <th>Description</th>
                                                <th>Statut</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($menuJour->menuProduits as $plat)
                                                <tr>
                                                    <td>{{ $plat->nom }}</td>
                                                    <td>{{ $plat->description }}</td>
                                                    <td>
                                                        @if($plat->disponible)
                                                            <span class="badge badge-success">Disponible</span>
                                                        @else
                                                            <span class="badge badge-secondary">Indisponible</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach

                <a href="{{ route('menu-semaine.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script>
    document.getElementById('btn-copier-lien').addEventListener('click', function() {
        const input = document.getElementById('lien-carte-menu');
        input.select();
        input.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(input.value).then(() => {
            const btn = document.getElementById('btn-copier-lien');
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Copié !';
            setTimeout(() => { btn.innerHTML = original; }, 1500);
        });
    });
</script>
@endpush
