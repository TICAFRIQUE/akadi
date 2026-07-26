@extends('admin.layouts.app')
@section('title', 'Menu du jour')
@section('sub-title', 'Modifier le menu du jour')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Modifier le menu du jour</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('menu-jour.index') }}">Menu du jour</a></div>
            <div class="breadcrumb-item">Modifier</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Informations du menu</h4>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    <strong>Erreurs de validation :</strong>
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('menu-jour.update', $menuJour) }}" method="POST">
                            @csrf

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Date <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="date" name="date" class="form-control" value="{{ old('date', \Illuminate\Support\Carbon::parse($menuJour->date)->format('Y-m-d')) }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Note</label>
                                <div class="col-sm-9">
                                    <textarea name="note" class="form-control" rows="2">{{ old('note', $menuJour->note) }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Statut</label>
                                <div class="col-sm-9">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="actif" class="custom-control-input" id="actif" {{ old('actif', $menuJour->actif) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="actif">Actif</label>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h5 class="mb-3">Plats du menu</h5>

                            <div id="plats-body">
                                {{-- Les lignes sont ajoutées ici en JS --}}
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-sm mb-3" id="btn-add-plat">
                                <i class="fas fa-plus"></i> Ajouter un plat
                            </button>

                            <div class="form-group row mt-3">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Enregistrer
                                    </button>
                                    <a href="{{ route('menu-jour.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Annuler
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script>
    let platIndex = 0;

    function buildPlatRow(id, nom, description, prix, disponible) {
        const div = document.createElement('div');
        div.className = 'row plat-row align-items-start mb-2 pb-2 border-bottom';
        div.dataset.index = platIndex;
        const idInput = id ? `<input type="hidden" name="plats[${platIndex}][id]" value="${id}">` : '';
        const dispoBadge = (id && !disponible) ? '<span class="badge badge-secondary ml-1" title="Désactivé (déjà vendu, retiré du menu)">Désactivé</span>' : '';
        div.innerHTML = `
            ${idInput}
            <div class="col-sm-4">
                <input type="text" name="plats[${platIndex}][nom]" class="form-control form-control-sm" placeholder="Nom du plat" value="${nom}" required>
            </div>
            <div class="col-sm-4">
                <input type="text" name="plats[${platIndex}][description]" class="form-control form-control-sm" placeholder="Description (optionnel)" value="${description}">
            </div>
            <div class="col-sm-3">
                <input type="number" name="plats[${platIndex}][prix]" class="form-control form-control-sm" placeholder="Prix (FCFA)" value="${prix}" min="0" step="any" required>
            </div>
            <div class="col-sm-1">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-plat"><i class="fas fa-times"></i></button>
            </div>
            <div class="col-12">${dispoBadge}</div>
        `;
        platIndex++;
        return div;
    }

    function addPlatRow(id = '', nom = '', description = '', prix = '', disponible = true) {
        const container = document.getElementById('plats-body');
        const row = buildPlatRow(id, nom, description, prix, disponible);
        container.appendChild(row);
        row.querySelector('.btn-remove-plat').addEventListener('click', function() {
            row.remove();
        });
    }

    document.getElementById('btn-add-plat').addEventListener('click', () => addPlatRow());

    document.addEventListener('DOMContentLoaded', () => {
        @if(old('plats'))
            const oldPlats = @json(old('plats'));
            oldPlats.forEach(p => addPlatRow(p.id ?? '', p.nom ?? '', p.description ?? '', p.prix ?? '', true));
        @else
            const existingPlats = @json($menuJour->menuProduits->map(fn($p) => [
                'id' => $p->id,
                'nom' => $p->nom,
                'description' => $p->description,
                'prix' => $p->prix,
                'disponible' => $p->disponible,
            ]));
            existingPlats.forEach(p => addPlatRow(p.id, p.nom, p.description ?? '', p.prix, p.disponible));
            if (existingPlats.length === 0) addPlatRow();
        @endif
    });
</script>
@endpush
