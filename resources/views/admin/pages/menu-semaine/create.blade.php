@extends('admin.layouts.app')
@section('title', 'Menu de la semaine')
@section('sub-title', 'Nouveau menu de la semaine')

@section('css')
    <link rel="stylesheet" href="{{ asset('admin/assets/bundles/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Nouveau menu de la semaine</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('menu-semaine.index') }}">Menu de la semaine</a></div>
            <div class="breadcrumb-item">Nouveau</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Informations de la semaine</h4>
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

                        <form action="{{ route('menu-semaine.store') }}" method="POST">
                            @csrf

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Titre <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="titre" class="form-control" placeholder="Ex : Semaine du 3 au 8 août" value="{{ old('titre') }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Lundi de la semaine <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ old('date_debut', now()->startOfWeek()->format('Y-m-d')) }}" required>
                                    <small class="text-muted">Sert de référence pour calculer les dates de chaque jour ci-dessous.</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Prix normal / plat <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" name="prix_normal" class="form-control" min="0" step="any" value="{{ old('prix_normal') }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Prix réduit / plat <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" name="prix_reduit" class="form-control" min="0" step="any" value="{{ old('prix_reduit') }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Seuil (jours) <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" name="seuil_jours" class="form-control" min="1" max="7" value="{{ old('seuil_jours', 3) }}" required>
                                    <small class="text-muted">À partir de ce nombre de jours commandés, le prix réduit s'applique automatiquement à tous les plats de la commande.</small>
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
                            <h5 class="mb-3">Plats par jour</h5>

                            <div id="jours-container">
                                @foreach ($joursLabels as $offset => $label)
                                    <div class="card mb-3 jour-card" data-offset="{{ $offset }}">
                                        <div class="card-header d-flex align-items-center justify-content-between">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="jours[{{ $offset }}][actif]" value="1"
                                                    class="custom-control-input jour-actif" id="jour-actif-{{ $offset }}"
                                                    data-offset="{{ $offset }}" {{ $offset < 6 ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="jour-actif-{{ $offset }}">
                                                    <strong>{{ $label }}</strong>
                                                    <span class="text-muted jour-date" id="jour-date-{{ $offset }}"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="card-body jour-body" id="jour-body-{{ $offset }}" style="{{ $offset < 6 ? '' : 'display:none' }}">
                                            <div id="plats-body-{{ $offset }}">
                                                {{-- Les lignes sont ajoutées ici en JS --}}
                                            </div>
                                            <button type="button" class="btn btn-outline-primary btn-sm btn-add-plat" data-offset="{{ $offset }}">
                                                <i class="fas fa-plus"></i> Ajouter un plat
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="form-group row mt-3">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Enregistrer
                                    </button>
                                    <a href="{{ route('menu-semaine.index') }}" class="btn btn-secondary">
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

    const platIndexParJour = {};
    @foreach ($joursLabels as $offset => $label)
        platIndexParJour[{{ $offset }}] = 0;
    @endforeach

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
                $thisSelect.find(`option[value="${data.id}"]`).remove();
                if (plat) {
                    applyPlatToRow($row, plat);
                } else {
                    $thisSelect.val(null).trigger('change');
                }
            });
        });
    }

    function buildPlatRow(offset, nom, description, prix) {
        const index = platIndexParJour[offset]++;
        const div = document.createElement('div');
        div.className = 'row plat-row align-items-start mb-2 pb-2 border-bottom';
        div.dataset.index = index;
        div.innerHTML = `
            <div class="col-sm-4">
                <div class="d-flex" style="min-width:0">
                    <select class="form-control form-control-sm plat-select flex-grow-1" name="jours[${offset}][plats][${index}][plat_id]" style="min-width:0"></select>
                    <button type="button" class="btn btn-sm btn-outline-success btn-new-plat ml-1" title="Créer un nouveau plat"><i class="fas fa-plus"></i></button>
                </div>
                <input type="hidden" name="jours[${offset}][plats][${index}][nom]" class="plat-nom-hidden" value="${nom}">
            </div>
            <div class="col-sm-4">
                <input type="text" name="jours[${offset}][plats][${index}][description]" class="form-control form-control-sm" placeholder="Description (optionnel)" value="${description}">
            </div>
            <div class="col-sm-3">
                <input type="number" name="jours[${offset}][plats][${index}][prix]" class="form-control form-control-sm" placeholder="Prix (FCFA)" value="${prix}" min="0" step="any" required>
            </div>
            <div class="col-sm-1">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-plat"><i class="fas fa-times"></i></button>
            </div>
        `;
        return div;
    }

    function addPlatRow(offset, nom = '', description = '', prix = '') {
        const container = document.getElementById('plats-body-' + offset);
        const row = buildPlatRow(offset, nom, description, prix);
        container.appendChild(row);
        const $select = $(row).find('.plat-select');
        populatePlatSelect($select, '');
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

    document.querySelectorAll('.btn-add-plat').forEach(btn => {
        btn.addEventListener('click', () => addPlatRow(btn.dataset.offset));
    });

    // Affiche/masque la section plats du jour selon la case "actif"
    document.querySelectorAll('.jour-actif').forEach(chk => {
        chk.addEventListener('change', function() {
            document.getElementById('jour-body-' + this.dataset.offset).style.display = this.checked ? '' : 'none';
        });
    });

    // Calcule et affiche la date de chaque jour à partir du "Lundi de la semaine"
    const joursNoms = @json($joursLabels);
    function updateJoursDates() {
        const dateDebut = document.getElementById('date_debut').value;
        if (!dateDebut) return;
        const base = new Date(dateDebut + 'T00:00:00');
        joursNoms.forEach((label, offset) => {
            const d = new Date(base);
            d.setDate(d.getDate() + offset);
            const formatted = d.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' });
            const el = document.getElementById('jour-date-' + offset);
            if (el) el.textContent = ' — ' + formatted;
        });
    }
    document.getElementById('date_debut').addEventListener('change', updateJoursDates);
    updateJoursDates();

    // admin/assets/js/scripts.js (chargé APRÈS @stack('js') dans le layout)
    // lance $(".select2").select2(...) sur toute la page au chargement. Le
    // wrapper que notre propre select2 génère porte aussi la classe "select2"
    // (comportement interne de la librairie) : s'il existe déjà quand ce script
    // global s'exécute, il est réinitialisé une seconde fois (widget fantôme).
    // On attend donc l'évènement "load" (après l'exécution de tous les <script>)
    // avant de créer les premières lignes, pour agir après ce script global.
    window.addEventListener('load', () => {
        joursNoms.forEach((label, offset) => {
            // Une première ligne vide pour les jours actifs par défaut (lundi à samedi)
            if (offset < 6) addPlatRow(offset);
        });
    });
</script>
@endpush
