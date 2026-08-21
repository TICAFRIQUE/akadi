@extends('admin.layouts.app')
@section('title', 'Commandes Menu')
@section('sub-title', 'Réservations par menu de la semaine')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Commandes Menu</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Commandes Menu</div>
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

        @forelse($menus as $menu)
        @php
            $total = $menu->reservations_count;
        @endphp
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between flex-wrap gap-2">

                    {{-- Infos menu --}}
                    <div style="flex:1;min-width:200px;">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h5 class="mb-0 font-weight-bold">{{ $menu->titre_affiche }}</h5>
                            @if($menu->actif)
                                <span class="badge badge-success ml-2">Actif</span>
                            @else
                                <span class="badge badge-secondary ml-2">Inactif</span>
                            @endif
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            {{ $menu->date_debut->format('d/m/Y') }} → {{ $menu->date_fin->format('d/m/Y') }}
                        </small>
                    </div>

                    {{-- Nb clients + bouton --}}
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center px-3 py-2 rounded" style="background:#fff8f0;">
                            <i class="fas fa-users mr-2" style="color:#f85d05;"></i>
                            <span class="font-weight-bold" style="font-size:1.1rem;color:#f85d05;">{{ $total }}</span>
                            <span class="ml-1 text-muted" style="font-size:.82rem;">client{{ $total > 1 ? 's' : '' }}</span>
                        </div>
                        <a href="{{ route('commandes-menu.by-menu', $menu) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye mr-1"></i> Voir les commandes
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
            <div class="card">
                <div class="card-body text-center text-muted py-5">
                    <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                    Aucune commande menu de la semaine pour le moment.
                </div>
            </div>
        @endforelse

        <div class="mt-3">
            {{ $menus->links() }}
        </div>
    </div>
</section>
@endsection
