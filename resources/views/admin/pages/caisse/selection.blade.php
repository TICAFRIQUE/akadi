@extends('admin.layouts.app')
@section('title', 'Sélection de caisse')
@section('sub-title', 'Choisissez votre caisse')

@section('css')
<style>
    .caisse-card {
        border: 2px solid #e9ecef;
        border-radius: 16px;
        padding: 28px 20px;
        text-align: center;
        cursor: pointer;
        transition: all .25s ease;
        background: #fff;
    }
    .caisse-card:hover, .caisse-card.selected {
        border-color: #4e73df;
        background: #f0f4ff;
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(78,115,223,.15);
    }
    .caisse-card .icon {
        font-size: 2.5rem;
        color: #4e73df;
        margin-bottom: 10px;
    }
    .caisse-card h5 { font-weight: 700; margin-bottom: 4px; }
    .caisse-card .badge-statut {
        display: inline-block;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 600;
    }
    .selection-wrap {
        min-height: 70vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection

@section('content')
<div class="section-body">
    <div class="selection-wrap">
        <div class="text-center mb-4">
            <i class="fas fa-cash-register fa-3x text-primary mb-3"></i>
            <h3 class="font-weight-bold">Sélectionnez votre caisse</h3>
            <p class="text-muted">Choisissez une caisse disponible pour démarrer votre session de vente.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger w-100" style="max-width:700px">{{ session('error') }}</div>
        @endif

        @if($caisses->isEmpty())
            <div class="alert alert-warning text-center" style="max-width:500px">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Aucune caisse disponible pour le moment.<br>
                <small>Contactez un administrateur pour créer ou libérer une caisse.</small>
            </div>
        @else
        <form action="{{ route('caisse.prendreEnCharge') }}" method="POST" id="form-caisse" style="width:100%;max-width:800px">
            @csrf
            <input type="hidden" name="caisse_id" id="caisse_id_input">

            <div class="row justify-content-center">
                @foreach($caisses as $caisse)
                <div class="col-12 col-sm-6 col-md-4 mb-4">
                    <div class="caisse-card" onclick="selectCaisse({{ $caisse->id }}, '{{ addslashes($caisse->nom) }}')">
                        <div class="icon"><i class="fas fa-cash-register"></i></div>
                        <h5>{{ $caisse->nom }}</h5>
                        @if($caisse->description)
                            <p class="text-muted small mb-2">{{ $caisse->description }}</p>
                        @endif
                        <span class="badge-statut" style="background:#d4edda;color:#155724;">
                            <i class="fas fa-circle fa-xs mr-1"></i> Disponible
                        </span>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-2">
                <button type="submit" id="btn-valider" class="btn btn-primary btn-lg px-5" disabled>
                    <i class="fas fa-sign-in-alt mr-2"></i> Prendre la caisse
                </button>
            </div>
        </form>
        @endif

        <div class="mt-4 text-center">
            <small class="text-muted">Connecté en tant que <strong>{{ Auth::user()->name }}</strong></small><br>
            <a href="{{ route('user.logout') }}" class="text-danger small mt-1 d-inline-block">
                <i class="fas fa-sign-out-alt mr-1"></i>Se déconnecter
            </a>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
function selectCaisse(id, nom) {
    document.querySelectorAll('.caisse-card').forEach(c => c.classList.remove('selected'));
    event.currentTarget.classList.add('selected');
    document.getElementById('caisse_id_input').value = id;
    const btn = document.getElementById('btn-valider');
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-sign-in-alt mr-2"></i> Prendre la caisse : ' + nom;
}
</script>
@endsection
