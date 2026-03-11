@extends('admin.layouts.app')
@section('title', 'Moyens de paiement')
@section('sub-title', 'Gestion des moyens de paiement')

@section('content')
<div class="section-body">
    <div class="row">
        <div class="col-12 col-md-4">
            <div class="card">
                <div class="card-header"><h4>Ajouter un moyen</h4></div>
                <div class="card-body">
                    <form action="{{ route('payment-method.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nom <span class="text-danger">*</span></label>
                            <input type="text" name="nom" class="form-control" required placeholder="Ex: Espèces, MTN MoMo, Orange Money...">
                        </div>
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" name="code" class="form-control" placeholder="Ex: cash, momo, orange">
                        </div>
                        <div class="form-group">
                            <label>Icône (classe FontAwesome)</label>
                            <input type="text" name="icone" class="form-control" placeholder="Ex: fa-money-bill-wave">
                        </div>
                        <div class="form-group">
                            <label>Position (ordre d'affichage)</label>
                            <input type="number" name="position" class="form-control" value="0" min="0">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-plus mr-1"></i> Ajouter
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header"><h4>Moyens de paiement</h4></div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Icône</th>
                                <th>Nom</th>
                                <th>Code</th>
                                <th>Position</th>
                                <th>Actif</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paymentMethods as $pm)
                            <tr>
                                <td>
                                    @if($pm->icone)
                                        <i class="fas {{ $pm->icone }} fa-lg text-primary"></i>
                                    @else
                                        <i class="fas fa-credit-card fa-lg text-muted"></i>
                                    @endif
                                </td>
                                <td><strong>{{ $pm->nom }}</strong></td>
                                <td><code>{{ $pm->code ?? '—' }}</code></td>
                                <td>{{ $pm->position }}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input toggle-pm"
                                            id="pm_{{ $pm->id }}"
                                            data-id="{{ $pm->id }}"
                                            {{ $pm->actif ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="pm_{{ $pm->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editPm{{ $pm->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('payment-method.destroy', $pm->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Supprimer ?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Modal édition --}}
                            <div class="modal fade" id="editPm{{ $pm->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modifier : {{ $pm->nom }}</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <form action="{{ route('payment-method.update', $pm->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Nom</label>
                                                    <input type="text" name="nom" class="form-control" required value="{{ $pm->nom }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Code</label>
                                                    <input type="text" name="code" class="form-control" value="{{ $pm->code }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Icône</label>
                                                    <input type="text" name="icone" class="form-control" value="{{ $pm->icone }}">
                                                </div>
                                                <div class="form-group">
                                                    <label>Position</label>
                                                    <input type="number" name="position" class="form-control" value="{{ $pm->position }}" min="0">
                                                </div>
                                                <div class="form-group">
                                                    <div class="custom-control custom-switch">
                                                        <input type="hidden" name="actif" value="0">
                                                        <input type="checkbox" class="custom-control-input" id="actif_{{ $pm->id }}" name="actif" value="1" {{ $pm->actif ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="actif_{{ $pm->id }}">Actif</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">Aucun moyen de paiement.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.querySelectorAll('.toggle-pm').forEach(function(el) {
    el.addEventListener('change', function() {
        const checkbox = this;
        const previousState = !checkbox.checked;
        
        fetch('{{ route('payment-method.changeState') }}?id=' + this.dataset.id, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur réseau');
            }
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                checkbox.checked = previousState;
                alert('Erreur lors du changement d\'état');
            } else {
                // Notification de succès
                const toast = document.createElement('div');
                toast.className = 'alert alert-success alert-dismissible fade show position-fixed';
                toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                toast.innerHTML = `
                    <strong>Succès!</strong> Statut mis à jour.
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                `;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 3000);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            checkbox.checked = previousState;
            alert('Erreur lors du changement d\'état. Veuillez réessayer.');
        });
    });
});
</script>
@endsection
