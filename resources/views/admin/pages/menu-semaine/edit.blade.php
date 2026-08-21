@extends('admin.layouts.app')
@section('title', 'Menu de la semaine')
@section('sub-title', 'Modifier ' . $menuSemaine->titre_affiche)

@section('css')
    <link rel="stylesheet" href="{{ asset('admin/assets/bundles/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Modifier le menu de la semaine</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard.index') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('menu-semaine.index') }}">Menu de la semaine</a></div>
            <div class="breadcrumb-item">Modifier</div>
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

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Un jour ou un plat déjà vendu ne sera jamais supprimé : il est simplement désactivé/masqué si vous le retirez du formulaire.
                        </div>

                        <form action="{{ route('menu-semaine.update', $menuSemaine) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Titre <span class="text-muted">(optionnel)</span></label>
                                <div class="col-sm-9">
                                    <input type="text" name="titre" class="form-control" placeholder="Ex : Semaine du 3 au 8 août" value="{{ old('titre', $menuSemaine->titre) }}">
                                    <small class="text-muted">Laissez vide pour afficher automatiquement la période.</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Période <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ old('date_debut', $menuSemaine->date_debut->format('Y-m-d')) }}" required>
                                            <small class="text-muted">Date de début</small>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ old('date_fin', $menuSemaine->date_fin->format('Y-m-d')) }}" required>
                                            <small class="text-muted">Date de fin</small>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-1">Changer la période régénère les jours ci-dessous (les jours déjà vendus hors de la nouvelle période sont désactivés, pas supprimés).</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Seuil (jours) <span class="text-danger">*</span></label>
                                <div class="col-sm-9">
                                    <input type="number" name="seuil_jours" class="form-control" min="1" value="{{ old('seuil_jours', $menuSemaine->seuil_jours) }}" required>
                                    <small class="text-muted">À partir de ce nombre de jours commandés, le prix réduit de chaque plat s'applique automatiquement.</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Statut</label>
                                <div class="col-sm-9">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="actif" class="custom-control-input" id="actif" {{ old('actif', $menuSemaine->actif) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="actif">Actif</label>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <h5 class="mb-3">Plats par jour</h5>

                            <div id="jours-container">
                                <p class="text-muted" id="jours-placeholder">Chargement des jours…</p>
                            </div>

                            <div class="form-group row mt-3">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Enregistrer les modifications
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

    @php
        // Données existantes : { "2026-08-24": { actif: true, plats: [{menu_produit_id, plat_id, nom, description, prix_normal, prix_reduit}, ...] }, ... }
        $joursExistantsPhp = $menuSemaine->menusJour->mapWithKeys(function ($j) {
            $plats = $j->menuProduits->map(function ($p) {
                return [
                    'menu_produit_id' => $p->id,
                    'plat_id'         => $p->plat_id,
                    'nom'             => $p->nom,
                    'description'     => $p->description ?? '',
                    'prix_normal'     => $p->prix_normal,
                    'prix_reduit'     => $p->prix_reduit,
                ];
            })->values();

            return [$j->date->format('Y-m-d') => ['actif' => (bool) $j->actif, 'plats' => $plats]];
        });
    @endphp
    const joursExistants = @json($joursExistantsPhp);

    const platIndexParJour = {};

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
        $row.find('input[name$="[prix_normal]"]').val(plat.prix);
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

    function buildPlatRow(dateKey, menuProduitId, platId, nom, description, prixNormal, prixReduit) {
        const index = platIndexParJour[dateKey]++;
        const div = document.createElement('div');
        div.className = 'row plat-row align-items-start mb-2 pb-2 border-bottom';
        div.dataset.index = index;
        div.innerHTML = `
            <div class="col-sm-3">
                <div class="d-flex" style="min-width:0">
                    <select class="form-control form-control-sm plat-select flex-grow-1" name="jours[${dateKey}][plats][${index}][plat_id]" style="min-width:0"></select>
                    <button type="button" class="btn btn-sm btn-outline-success btn-new-plat ml-1" title="Créer un nouveau plat"><i class="fas fa-plus"></i></button>
                </div>
                <input type="hidden" name="jours[${dateKey}][plats][${index}][nom]" class="plat-nom-hidden" value="${nom}">
                <input type="hidden" name="jours[${dateKey}][plats][${index}][menu_produit_id]" value="${menuProduitId ?? ''}">
            </div>
            <div class="col-sm-3">
                <input type="text" name="jours[${dateKey}][plats][${index}][description]" class="form-control form-control-sm" placeholder="Description (optionnel)" value="${description}">
            </div>
            <div class="col-sm-2">
                <input type="number" name="jours[${dateKey}][plats][${index}][prix_normal]" class="form-control form-control-sm" placeholder="Prix normal" value="${prixNormal}" min="0" step="any" required>
            </div>
            <div class="col-sm-2">
                <input type="number" name="jours[${dateKey}][plats][${index}][prix_reduit]" class="form-control form-control-sm" placeholder="Prix réduit" value="${prixReduit}" min="0" step="any" required>
            </div>
            <div class="col-sm-1">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-plat"><i class="fas fa-times"></i></button>
            </div>
        `;
        return div;
    }

    function addPlatRow(dateKey, menuProduitId = null, platId = '', nom = '', description = '', prixNormal = '', prixReduit = '') {
        const container = document.getElementById('plats-body-' + dateKey);
        if (!container) return;
        const row = buildPlatRow(dateKey, menuProduitId, platId, nom, description, prixNormal, prixReduit);
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

    // ── Génération des blocs "jour" à partir de l'intervalle date_debut → date_fin ──
    function buildJourCard(dateKey, label, checked) {
        const div = document.createElement('div');
        div.className = 'card mb-3 jour-card';
        div.dataset.date = dateKey;
        div.innerHTML = `
            <div class="card-header d-flex align-items-center justify-content-between">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="jours[${dateKey}][actif]" value="1"
                        class="custom-control-input jour-actif" id="jour-actif-${dateKey}"
                        data-date="${dateKey}" ${checked ? 'checked' : ''}>
                    <label class="custom-control-label" for="jour-actif-${dateKey}">
                        <strong>${label}</strong>
                    </label>
                </div>
            </div>
            <div class="card-body jour-body" id="jour-body-${dateKey}">
                <div id="plats-body-${dateKey}"></div>
                <button type="button" class="btn btn-outline-primary btn-sm btn-add-plat" data-date="${dateKey}">
                    <i class="fas fa-plus"></i> Ajouter un plat
                </button>
            </div>
        `;
        return div;
    }

    function toIsoDate(d) {
        return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
    }

    function formatLabel(d) {
        const label = d.toLocaleDateString('fr-FR', { weekday: 'long', day: '2-digit', month: '2-digit', year: 'numeric' });
        return label.charAt(0).toUpperCase() + label.slice(1);
    }

    // Régénère les jours à partir de zéro (utilisé quand l'admin change la période) : un jour vide par date.
    function rebuildJours() {
        const debutVal = document.getElementById('date_debut').value;
        const finVal = document.getElementById('date_fin').value;
        const container = document.getElementById('jours-container');

        if (!debutVal || !finVal) {
            container.innerHTML = '<p class="text-muted" id="jours-placeholder">Choisissez une période ci-dessus pour afficher les jours.</p>';
            return;
        }

        const debut = new Date(debutVal + 'T00:00:00');
        const fin = new Date(finVal + 'T00:00:00');

        if (fin < debut) {
            container.innerHTML = '<p class="text-danger">La date de fin doit être postérieure ou égale à la date de début.</p>';
            return;
        }

        container.innerHTML = '';
        const cur = new Date(debut);
        while (cur <= fin) {
            const dateKey = toIsoDate(cur);
            container.appendChild(buildJourCard(dateKey, formatLabel(cur), true));
            platIndexParJour[dateKey] = 0;
            addPlatRow(dateKey);
            cur.setDate(cur.getDate() + 1);
        }

        document.querySelectorAll('.btn-add-plat').forEach(btn => {
            btn.addEventListener('click', () => addPlatRow(btn.dataset.date));
        });
    }

    // Rendu initial (chargement de la page) : réutilise les jours/plats déjà enregistrés dans l'intervalle actuel.
    function renderInitialJours() {
        const debutVal = document.getElementById('date_debut').value;
        const finVal = document.getElementById('date_fin').value;
        const container = document.getElementById('jours-container');

        if (!debutVal || !finVal) {
            container.innerHTML = '<p class="text-muted" id="jours-placeholder">Choisissez une période ci-dessus pour afficher les jours.</p>';
            return;
        }

        const debut = new Date(debutVal + 'T00:00:00');
        const fin = new Date(finVal + 'T00:00:00');
        container.innerHTML = '';

        const cur = new Date(debut);
        while (cur <= fin) {
            const dateKey = toIsoDate(cur);
            const existant = joursExistants[dateKey];
            const checked = existant ? existant.actif : true;
            container.appendChild(buildJourCard(dateKey, formatLabel(cur), checked));
            platIndexParJour[dateKey] = 0;

            if (existant && existant.plats.length > 0) {
                existant.plats.forEach(p => {
                    addPlatRow(dateKey, p.menu_produit_id, p.plat_id ?? '', p.nom, p.description, p.prix_normal, p.prix_reduit);
                });
            } else {
                addPlatRow(dateKey);
            }
            cur.setDate(cur.getDate() + 1);
        }

        document.querySelectorAll('.btn-add-plat').forEach(btn => {
            btn.addEventListener('click', () => addPlatRow(btn.dataset.date));
        });
    }

    document.getElementById('date_debut').addEventListener('change', rebuildJours);
    document.getElementById('date_fin').addEventListener('change', rebuildJours);

    // Voir commentaire équivalent dans create.blade.php : on attend "load" pour agir
    // après le script global admin/assets/js/scripts.js (ré-init select2 sinon en double).
    window.addEventListener('load', () => {
        renderInitialJours();
    });
</script>
@endpush
