@extends('admin.layouts.app')
@section('title', 'Top plats commandés')
@section('sub-title', 'Classement des plats les plus commandés')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Top Plats Commandés</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('rapport.vente') }}">Rapports</a></div>
            <div class="breadcrumb-item">Top Plats</div>
        </div>
    </div>

    <div class="section-body">

        {{-- ── Filtre dates ── --}}
        <div class="card mb-4">
            <div class="card-body py-3">
                <form method="GET" class="form-inline gap-2 flex-wrap">
                    <label class="mr-2 font-weight-600">Période :</label>
                    <input type="date" name="date_debut" class="form-control form-control-sm mr-2"
                        value="{{ $dateDebut ? $dateDebut->format('Y-m-d') : '' }}">
                    <span class="mr-2 text-muted">→</span>
                    <input type="date" name="date_fin" class="form-control form-control-sm mr-2"
                        value="{{ $dateFin ? $dateFin->format('Y-m-d') : '' }}">
                    <button type="submit" class="btn btn-primary btn-sm mr-2">
                        <i class="fas fa-filter mr-1"></i> Filtrer
                    </button>
                    <a href="{{ route('rapport.top-produits') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times mr-1"></i> Réinitialiser
                    </a>
                    @if($dateDebut || $dateFin)
                        <span class="badge badge-info ml-2">
                            {{ $dateDebut ? $dateDebut->format('d/m/Y') : '...' }}
                            →
                            {{ $dateFin ? $dateFin->format('d/m/Y') : '...' }}
                        </span>
                    @else
                        <span class="badge badge-secondary ml-2">Toutes les périodes</span>
                    @endif
                </form>
            </div>
        </div>

        <div class="row">

            {{-- ── TOP PRODUITS CATALOGUE ── --}}
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-utensils mr-2" style="color:#f85d05;"></i>
                            Top produits catalogue
                        </h4>
                        <small class="text-muted">Commandes classiques (Pos, site web)</small>
                    </div>
                    <div class="card-body">
                        @forelse($topProduits as $i => $p)
                        @php $pct = $maxQteCatalogue > 0 ? round($p['qte'] / $maxQteCatalogue * 100) : 0; @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge badge-{{ $i === 0 ? 'warning' : ($i === 1 ? 'secondary' : ($i === 2 ? 'danger' : 'light text-dark')) }}"
                                          style="min-width:26px;font-size:.8rem;">
                                        {{ $i + 1 }}
                                    </span>
                                    <strong style="font-size:.88rem;">{{ $p['nom'] }}</strong>
                                </div>
                                <div class="text-right" style="white-space:nowrap;">
                                    <span class="font-weight-bold" style="color:#f85d05;">{{ $p['qte'] }}</span>
                                    <span class="text-muted" style="font-size:.75rem;"> cmd</span>
                                    <br>
                                    <small class="text-muted">{{ number_format($p['ca'], 0, ',', ' ') }} F</small>
                                </div>
                            </div>
                            <div class="progress" style="height:6px;border-radius:4px;">
                                <div class="progress-bar" role="progressbar"
                                     style="width:{{ $pct }}%;background:{{ $i === 0 ? '#f85d05' : ($i < 3 ? '#eb0029' : '#6366f1') }};border-radius:4px;"
                                     aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        @empty
                            <p class="text-muted text-center py-4">
                                <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                                Aucune vente sur cette période.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ── TOP PLATS MENU SEMAINE ── --}}
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h4>
                            <i class="fas fa-calendar-week mr-2" style="color:#6366f1;"></i>
                            Top plats menu semaine
                        </h4>
                        <small class="text-muted">Réservations menu de la semaine</small>
                    </div>
                    <div class="card-body">
                        @forelse($topPlatsMenu as $i => $p)
                        @php $pct = $maxQteMenu > 0 ? round($p['qte'] / $maxQteMenu * 100) : 0; @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge badge-{{ $i === 0 ? 'warning' : ($i === 1 ? 'secondary' : ($i === 2 ? 'danger' : 'light text-dark')) }}"
                                          style="min-width:26px;font-size:.8rem;">
                                        {{ $i + 1 }}
                                    </span>
                                    <strong style="font-size:.88rem;">{{ $p['nom'] }}</strong>
                                </div>
                                <div class="text-right" style="white-space:nowrap;">
                                    <span class="font-weight-bold" style="color:#6366f1;">{{ $p['qte'] }}</span>
                                    <span class="text-muted" style="font-size:.75rem;"> cmd</span>
                                    <br>
                                    <small class="text-muted">{{ number_format($p['ca'], 0, ',', ' ') }} F</small>
                                </div>
                            </div>
                            <div class="progress" style="height:6px;border-radius:4px;">
                                <div class="progress-bar" role="progressbar"
                                     style="width:{{ $pct }}%;background:{{ $i === 0 ? '#6366f1' : ($i < 3 ? '#8b5cf6' : '#a78bfa') }};border-radius:4px;"
                                     aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        @empty
                            <p class="text-muted text-center py-4">
                                <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                                Aucun plat menu sur cette période.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        {{-- ── Tableau combiné export ── --}}
        @if($topProduits->count() > 0 || $topPlatsMenu->count() > 0)
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-table mr-2"></i> Tableau détaillé</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="tbl-top">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Plat / Produit</th>
                                <th>Catégorie</th>
                                <th>Qté commandée</th>
                                <th>CA généré</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProduits as $i => $p)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <span class="badge badge-light mr-1" style="font-size:.7rem;">Catalogue</span>
                                    <strong>{{ $p['nom'] }}</strong>
                                </td>
                                <td><span class="text-muted">—</span></td>
                                <td><strong style="color:#f85d05;">{{ $p['qte'] }}</strong></td>
                                <td>{{ number_format($p['ca'], 0, ',', ' ') }} FCFA</td>
                            </tr>
                            @endforeach
                            @foreach($topPlatsMenu as $i => $p)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <span class="badge badge-primary mr-1" style="font-size:.7rem;background:#6366f1;">Menu</span>
                                    <strong>{{ $p['nom'] }}</strong>
                                </td>
                                <td><span class="text-muted">Menu semaine</span></td>
                                <td><strong style="color:#6366f1;">{{ $p['qte'] }}</strong></td>
                                <td>{{ number_format($p['ca'], 0, ',', ' ') }} FCFA</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </div>
</section>
@endsection
