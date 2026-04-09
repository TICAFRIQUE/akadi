{{-- Composant : Gestion dynamique des ProductBases pour un Produit --}}
<div class="form-group row mb-4">
    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
        Produits de base
        <small class="d-block text-muted" style="font-size:.75rem">Optionnel - Multiples possible</small>
    </label>
    <div class="col-sm-12 col-md-12">
        <div id="product-bases-container"></div>

        <button type="button" id="add-product-base" class="btn btn-outline-primary btn-sm mt-2">
            <i data-feather="plus"></i> Ajouter un produit de base
        </button>

        <div class="alert alert-info mt-3" style="font-size:.85rem">
            <strong>Exemple:</strong> 1 Poulet braisé = 1 poulet de base<br>
            <strong>Exemple:</strong> 1 Demi-poulet = 0.5 poulet de base
        </div>
    </div>
</div>

<script>
    const existingProductBases = @json($productBasesData ?? []);
    const allProductBases = @json($allProductBases ?? []);

    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('product-bases-container');

        // UPDATE : pré-remplir avec les liaisons existantes
        // CREATE : container vide, l'utilisateur clique "Ajouter"
        if (existingProductBases.length > 0) {
            existingProductBases.forEach(function(pb) {
                appendProductBaseLine(pb.id, pb.coefficient, pb.unite);
            });
            updateRemoveButtonsVisibility();
            feather.replace();
        }

        // Bouton "Ajouter une ligne"
        document.getElementById('add-product-base').addEventListener('click', function() {
            appendProductBaseLine(null, null, 'unité');
            updateRemoveButtonsVisibility();
            feather.replace();
        });

        // Suppression d'une ligne (délégation)
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-product-base')) {
                e.preventDefault();
                e.target.closest('.product-base-row').remove();
                updateRemoveButtonsVisibility();
            }
        });

        // Mise à jour de l'unité quand on change le select
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('product-base-select')) {
                const row = e.target.closest('.product-base-row');
                const selectedOption = e.target.options[e.target.selectedIndex];
                const unite = selectedOption.dataset.unite || 'unité';
                row.querySelector('.unite-display span').textContent = unite;
            }
        });

        function appendProductBaseLine(selectedId, coefficient, unite) {
            unite = unite || 'unité';

            // ── Créer le select ──────────────────────────────────────
            const select = document.createElement('select');
            select.name = 'product_base_id[]';
            select.className = 'form-control product-base-select';

            const emptyOption = document.createElement('option');
            emptyOption.value = '';
            emptyOption.textContent = '-- Sélectionner --';
            select.appendChild(emptyOption);

            // Peupler les <option> avec allProductBases
            allProductBases.forEach(function(base) {
                const option = document.createElement('option');
                option.value = base.id;
                option.dataset.unite = base.unite;
                option.textContent = base.nom + ' (' + base.unite + ')';

                if (selectedId !== null && parseInt(selectedId) === parseInt(base.id)) {
                    option.selected = true;
                    unite = base.unite;
                }

                select.appendChild(option);
            });

            // ── Créer l'input coefficient ────────────────────────────
            const coeffInput = document.createElement('input');
            coeffInput.type = 'number';
            coeffInput.name = 'coefficient[]';
            coeffInput.step = '0.01';
            coeffInput.min = '0';
            coeffInput.className = 'form-control coefficient-input';
            coeffInput.placeholder = 'Ex: 1';
            if (coefficient !== null && parseFloat(coefficient) !== 0) {
                coeffInput.value = parseFloat(coefficient);
            }

            // ── Créer le bouton supprimer ────────────────────────────
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-danger btn-sm w-100 remove-product-base';
            removeBtn.innerHTML = '<i data-feather="trash-2"></i>';

            // ── Assembler la ligne ───────────────────────────────────
            const row = document.createElement('div');
            row.className = 'product-base-row mb-3 p-3 border rounded';
            row.style.backgroundColor = '#f9f9f9';

            const inner = document.createElement('div');
            inner.className = 'row align-items-end';

            // Colonne select
            const colSelect = document.createElement('div');
            colSelect.className = 'col-6';
            const labelSelect = document.createElement('label');
            labelSelect.className = 'small font-weight-bold d-block mb-1';
            labelSelect.textContent = 'Produit de base';
            colSelect.appendChild(labelSelect);
            colSelect.appendChild(select);

            // Colonne coefficient
            const colCoeff = document.createElement('div');
            colCoeff.className = 'col-4';
            const labelCoeff = document.createElement('label');
            labelCoeff.className = 'small font-weight-bold d-block mb-1';
            labelCoeff.textContent = 'Coefficient';
            const inputGroup = document.createElement('div');
            inputGroup.className = 'input-group';
            const inputAppend = document.createElement('div');
            inputAppend.className = 'input-group-append unite-display';
            const unitSpan = document.createElement('span');
            unitSpan.className = 'input-group-text';
            unitSpan.textContent = unite;
            inputAppend.appendChild(unitSpan);
            inputGroup.appendChild(coeffInput);
            inputGroup.appendChild(inputAppend);
            colCoeff.appendChild(labelCoeff);
            colCoeff.appendChild(inputGroup);

            // Colonne bouton
            const colBtn = document.createElement('div');
            colBtn.className = 'col-2';
            colBtn.appendChild(removeBtn);

            inner.appendChild(colSelect);
            inner.appendChild(colCoeff);
            inner.appendChild(colBtn);
            row.appendChild(inner);

            container.appendChild(row);
        }

        // Mettre à jour la visibilité des boutons de suppression
        function updateRemoveButtonsVisibility() {
            const rows = document.querySelectorAll('.product-base-row');
            rows.forEach(function(row, index) {
                const btn = row.querySelector('.remove-product-base');
                // if (btn) btn.style.visibility = (index === 0) ? 'hidden' : 'visible';
            });
        }
    });
</script>
