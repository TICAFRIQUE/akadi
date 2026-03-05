@extends('admin.layouts.app')
@section('title', 'Caisses')
@section('sub-title', 'Gestion des caisses')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-header"><h4>Nouvelle caisse</h4></div>
                <div class="card-body">
                    <form action="{{ route('caisse.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" required placeholder="Ex: Caisse principale">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" class="form-control" placeholder="Description optionnelle">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-plus mr-1"></i> Créer la caisse
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Liste des caisses</h4>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Statut</th>
                                    <th>Agent actif</th>
                                    <th>Prise en charge</th>
                                    <th>Actif</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($caisses as $caisse)
                                <tr>
                                    <td>
                                        <strong>{{ $caisse->nom }}</strong>
                                        @if($caisse->description)
                                            <br><small class="text-muted">{{ $caisse->description }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $colors = ['disponible'=>'success','occupee'=>'warning','fermee'=>'secondary'];
                                            $labels = ['disponible'=>'Disponible','occupee'=>'Occupée','fermee'=>'Fermée'];
                                        @endphp
                                        <span class="badge badge-{{ $colors[$caisse->statut] ?? 'secondary' }}">
                                            {{ $labels[$caisse->statut] ?? $caisse->statut }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($caisse->user)
                                            <span class="text-dark"><i class="fas fa-user-circle mr-1"></i>{{ $caisse->user->name }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $caisse->prise_en_charge_at?->format('d/m/Y H:i') ?? '—' }}
                                    </td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input toggle-actif"
                                                id="actif_{{ $caisse->id }}"
                                                data-id="{{ $caisse->id }}"
                                                {{ $caisse->actif ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="actif_{{ $caisse->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('caisse.edit', $caisse->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('caisse.destroy', $caisse->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Supprimer cette caisse ?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center text-muted py-4">Aucune caisse créée.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.querySelectorAll('.toggle-actif').forEach(function(el) {
    el.addEventListener('change', function() {
        fetch('{{ route('caisse.changeState') }}?id=' + this.dataset.id)
            .then(r => r.json())
            .then(data => {
                if (!data.success) this.checked = !this.checked;
            });
    });
});
</script>
@endsection
