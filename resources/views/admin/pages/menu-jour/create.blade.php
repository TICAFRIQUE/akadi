@extends('admin.layouts.app')
@section('title', 'Menu du jour')
@section('sub-title', 'Nouveau menu du jour')

@section('css')
    <link rel="stylesheet" href="{{ asset('admin/assets/bundles/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Nouveau menu du jour</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('menu-jour.index') }}">Menu du jour</a></div>
            <div class="breadcrumb-item">Nouveau</div>
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

                        <form action="{{ route('menu-jour.store') }}" method="POST">
                            @csrf

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Date <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="date" name="date" class="form-control" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Note</label>
                                <div class="col-sm-9">
                                    <textarea name="note" class="form-control" rows="2">{{ old('note') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Statut</label>
                                <div class="col-sm-9">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="actif" class="custom-control-input" id="actif" {{ old('actif', true) ? 'checked' : '' }}>
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
<script src="{{ asset('admin/assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
<script>
    window.platsCatalog = @json($plats);

    let platIndex = 0;

    function populatePlatSelect($select, selectedId) {
        $select.empty().append('<option></option>');
        window.platsCatalog.forEach(p => {
            const opt = new Option(p.nom, p.id, false, selectedId && p.id == selectedId);
            $select.append(opt);
        });
        $select.select2({
            width: '100%',
            placeholder: 'Choisir ou taper un nouveau plat…',
            tags: true,
            language: { noResults: () => 'Tapez pour créer un nouveau plat' },
        });
        if (selectedId) $select.trigger('change');
    }

    function applyPlatToRow($row, plat) {
        $row.find('.plat-select').val(plat.id).trigger('change');
        $row.find('.plat-nom-hidden').val(plat.nom);
        $row.find('input[name$="[description]"]').val(plat.description ?? '');
        $row.find('input[name$="[prix]"]').val(plat.prix);
    }

    // Ouvre le formulaire de création rapide d'un plat (bouton "+" ou saisie d'un nom absent du select2).
    // Retourne une promesse résolue avec le plat créé, ou null si annulé.
    function openCreatePlatDialog(prefillNom = '') {
        return Swal.fire({
            title: prefillNom ? ('Nouveau plat : ' + prefillNom) : 'Nouveau plat',
            html: `
                ${prefillNom ? '' : '<input id="swal-nom" class="swal2-input" placeholder="Nom du plat">'}
                <input id="swal-prix" type="number" min="0" step="any" class="swal2-input" placeholder="Prix (FCFA)">
                <textarea id="swal-desc" class="swal2-textarea" placeholder="Description (optionnel)"></textarea>`,
            confirmButtonText: 'Créer',
            showCancelButton: true,
            cancelButtonText: 'Annuler',
            preConfirm: () => {
                const nom = prefillNom || document.getElementById('swal-nom').value.trim();
                const prix = document.getElementById('swal-prix').value;
                if (!nom) {
                    Swal.showValidationMessage('Le nom est requis');
                    return false;
                }
                if (prix === '' || prix < 0) {
                    Swal.showValidationMessage('Le prix est requis');
                    return false;
                }
                return { nom, prix, description: document.getElementById('swal-desc').value };
            },
        }).then(result => {
            if (!result.isConfirmed) return null;

            return fetch(`{{ route('plats.store') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(result.value),
            })
            .then(r => r.json())
            .then(plat => {
                window.platsCatalog.push(plat);
                document.querySelectorAll('.plat-select').forEach(sel => {
                    if (!$(sel).find(`option[value="${plat.id}"]`).length) {
                        $(sel).append(new Option(plat.nom, plat.id));
                    }
                });
                return plat;
            });
        });
    }

    function bindPlatSelect($select) {
        $select.on('select2:select', function (e) {
            const data = e.params.data;
            const $row = $(this).closest('.plat-row');
            const $thisSelect = $(this);
            const isNew = !window.platsCatalog.some(p => String(p.id) === String(data.id));

            if (!isNew) {
                const plat = window.platsCatalog.find(p => String(p.id) === String(data.id));
                applyPlatToRow($row, plat);
                return;
            }

            openCreatePlatDialog(data.text).then(plat => {
                // Retire l'option temporaire créée par le tagging Select2 (valeur = texte tapé)
                $thisSelect.find(`option[value="${data.id}"]`).remove();
                if (plat) {
                    applyPlatToRow($row, plat);
                } else {
                    $thisSelect.val(null).trigger('change');
                }
            });
        });
    }

    function buildPlatRow(nom, description, prix) {
        const div = document.createElement('div');
        div.className = 'row plat-row align-items-start mb-2 pb-2 border-bottom';
        div.dataset.index = platIndex;
        div.innerHTML = `
            <div class="col-sm-4">
                <div class="d-flex" style="min-width:0">
                    <select class="form-control form-control-sm plat-select flex-grow-1" name="plats[${platIndex}][plat_id]" style="min-width:0"></select>
                    <button type="button" class="btn btn-sm btn-outline-success btn-new-plat ml-1" title="Créer un nouveau plat"><i class="fas fa-plus"></i></button>
                </div>
                <input type="hidden" name="plats[${platIndex}][nom]" class="plat-nom-hidden" value="${nom}">
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
        `;
        platIndex++;
        return div;
    }

    function addPlatRow(nom = '', description = '', prix = '', platId = '') {
        const container = document.getElementById('plats-body');
        const row = buildPlatRow(nom, description, prix);
        container.appendChild(row);
        const $select = $(row).find('.plat-select');
        populatePlatSelect($select, platId);
        bindPlatSelect($select);
        row.querySelector('.btn-remove-plat').addEventListener('click', function() {
            row.remove();
        });
        row.querySelector('.btn-new-plat').addEventListener('click', function() {
            openCreatePlatDialog().then(plat => {
                if (plat) applyPlatToRow($(row), plat);
            });
        });
    }

    document.getElementById('btn-add-plat').addEventListener('click', () => addPlatRow());

    // admin/assets/js/scripts.js (chargé APRÈS @stack('js') dans le layout)
    // lance $(".select2").select2(...) sur toute la page au chargement. Le
    // wrapper que notre propre select2 génère porte aussi la classe "select2"
    // (comportement interne de la librairie) : s'il existe déjà quand ce script
    // global s'exécute, il est réinitialisé une seconde fois (widget fantôme).
    // On attend donc l'évènement "load" (après l'exécution de tous les <script>)
    // avant de créer la première ligne, pour agir après ce script global.
    window.addEventListener('load', () => {
        // Une première ligne vide au chargement
        addPlatRow();
    });
</script>
@endpush
