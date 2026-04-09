 <script>
        let cartItems = {};
        let currentTotal = 0;

        $(document).ready(function() {
            // ── Select2 produit ──────────────────────────────────────────────────────
            $("#product-select").on("change", function() {
                var val = $(this).val();
                if (!val) return;
                var opt = $(this).find('option[value="' + val + '"]');
                var rawStock = opt.attr("data-stock");
                var stockInfo = [];
                try {
                    stockInfo = JSON.parse(opt.attr("data-stock-info") || "[]");
                } catch (e) {}

                var p = {
                    id: val,
                    title: opt.attr("data-title") || opt.text().split("—")[0].trim(),
                    price: parseFloat(opt.attr("data-price")) || 0,
                    stock: rawStock !== "" && rawStock !== undefined && !isNaN(rawStock) ?
                        parseInt(rawStock) :
                        null,
                    stockInsuffisant: opt.attr("data-stock-insuffisant") === "1",
                    stockInfo: stockInfo,
                    img: opt.attr("data-img") || null,
                };

                $(this).select2("close");
                $(this).val(null).trigger("change");

                addProduct(p);
            });
        }); // end document.ready

        // ── Ajouter un produit au tableau ────────────────────────────────────────────
        function addProduct(p) {
            // Si déjà présent → incrémenter (sauf si ligne rupture)
            if (cartItems[p.id]) {
                const row = document.getElementById("row-" + p.id);
                if (row && row.classList.contains("table-danger")) return;
                const qtyInput = row.querySelector(".qty-input");
                let qty = parseInt(qtyInput.value) + 1;
                if (p.stock !== null && qty > p.stock) qty = p.stock;
                qtyInput.value = qty;
                recalcRow(p.id);
                return;
            }

            cartItems[p.id] = p;

            const emptyRow = document.getElementById("empty-row");
            if (emptyRow) emptyRow.remove();

            const tbody = document.getElementById("items-body");
            const tr = document.createElement("tr");
            tr.id = "row-" + p.id;

            // Ligne en rupture → rouge + inputs désactivés
            const isRupture = p.stockInsuffisant || (p.stock !== null && p.stock <= 0);
            if (isRupture) {
                tr.classList.add("table-danger");
            }

            // Badge stock
            let stockBadge = "";
            if (p.stock === null) {
                stockBadge = '<span class="badge badge-light border">∞</span>';
            } else if (p.stock <= 0) {
                stockBadge = '<span class="badge badge-danger">0</span>';
            } else if (p.stock <= 5) {
                stockBadge = `<span class="badge badge-warning">${p.stock}</span>`;
            } else {
                stockBadge = `<span class="badge badge-light border">${p.stock}</span>`;
            }

            // Bouton détail stock bases
            const btnDetail =
                p.stockInfo && p.stockInfo.length > 0 ?
                `
            <br>
            <button type="button" class="btn btn-xs btn-outline-info mt-1"
                onclick="showStockDetail('${p.id}')"
                title="Détail stock bases">
                <i class="fas fa-layer-group" style="font-size:.7rem"></i>
            </button>
        ` :
                "";

            // Label rupture
            const ruptureLabel = isRupture ?
                `<br><small class="text-danger font-weight-bold">Stock insuffisant</small>` :
                "";

            tr.innerHTML = `
            <td>
                <span class="font-weight-600">${p.title}</span>
                ${ruptureLabel}
                <input type="hidden" name="products[${p.id}][product_id]" value="${p.id}">
            </td>
            <td class="text-center">
                ${stockBadge}
                ${btnDetail}
            </td>
            <td style="width:110px">
                <input type="number" name="products[${p.id}][unit_price]"
                    class="form-control form-control-sm unit-price-input bg-light text-center"
                    style="width:100px" value="${p.price}"
                    readonly ${isRupture ? "disabled" : ""}>
            </td>
            <td style="width:100px; padding-left:20px">
                <div class="input-group input-group-sm" style="width:96px;flex-wrap:nowrap">
                    <div class="input-group-prepend">
                        <button type="button" class="btn btn-sm btn-outline-secondary px-1"
                            onclick="changeQty('${p.id}', -1)" ${isRupture ? "disabled" : ""}>−</button>
                    </div>
                    <input type="number" name="products[${p.id}][quantity]"
                        class="form-control form-control-sm qty-input text-center"
                        style="min-width:56px"
                        value="1" min="1" ${p.stock !== null ? 'max="' + p.stock + '"' : ""}
                        onchange="recalcRow('${p.id}')"
                        ${isRupture ? "disabled" : ""}>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-sm btn-outline-secondary px-1"
                            onclick="changeQty('${p.id}', 1)" ${isRupture ? "disabled" : ""}>+</button>
                    </div>
                </div>
            </td>
            <td style="width:150px; padding-left:35px">
                <div class="input-group input-group-sm" style="width:140px;flex-wrap:nowrap">
                    <input type="number" name="products[${p.id}][discount]"
                        class="form-control form-control-sm discount-input text-center"
                        style="min-width:100px"
                        value="0" min="0" max="100" step="1"
                        onchange="recalcRow('${p.id}')"
                        ${isRupture ? "disabled" : ""}>
                    <div class="input-group-append">
                        <button type="button" class="btn type-disc-btn type-disc-pct active-pct"
                            onclick="setDiscountType('${p.id}', 'percent', this)"
                            ${isRupture ? "disabled" : ""}>%</button>
                        <button type="button" class="btn type-disc-btn type-disc-fixed"
                            onclick="setDiscountType('${p.id}', 'fixed', this)"
                            ${isRupture ? "disabled" : ""}>FCFA</button>
                    </div>
                    <input type="hidden" name="products[${p.id}][type_discount]" class="type-discount-input" value="percent">
                </div>
            </td>
            <td class="text-right">
                <span id="total-${p.id}" class="font-weight-bold">
                    ${isRupture ? '<span class="text-danger">— (rupture)</span>' : formatMoney(p.price) + " FCFA"}
                </span>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row"
                    onclick="removeRow('${p.id}')">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;

            tbody.appendChild(tr);

            if (!isRupture) {
                recalcRow(p.id.toString());
            }
        }

        // ── Changer quantité via boutons ─────────────────────────────────────────────
        function changeQty(pid, delta) {
            const row = document.getElementById("row-" + pid);
            const input = row.querySelector(".qty-input");
            let val = parseInt(input.value) || 1;
            val = Math.max(1, val + delta);
            const stock = cartItems[pid].stock;
            if (stock !== null && val > stock) val = stock;
            input.value = val;
            recalcRow(pid);
        }

        // ── Changer type de remise par ligne ─────────────────────────────────────────
        function setDiscountType(pid, type, btn) {
            const row = document.getElementById("row-" + pid);
            const discountInput = row.querySelector(".discount-input");
            const typeInput = row.querySelector(".type-discount-input");

            row.querySelectorAll(".type-disc-btn").forEach((b) =>
                b.classList.remove("active-pct", "active-fixed"),
            );

            if (type === "percent") {
                row.querySelector(".type-disc-pct").classList.add("active-pct");
                discountInput.max = 100;
                discountInput.placeholder = "0–100";
            } else {
                row.querySelector(".type-disc-fixed").classList.add("active-fixed");
                const unitPrice =
                    parseFloat(row.querySelector(".unit-price-input").value) || 0;
                discountInput.max = unitPrice;
                discountInput.placeholder = "0";
            }

            typeInput.value = type;
            discountInput.value = 0;
            recalcRow(pid);
        }

        // ── Changer type de remise globale ───────────────────────────────────────────
        function setGlobalDiscountType(type, btn) {
            document
                .querySelectorAll(".global-disc-btn")
                .forEach((b) => b.classList.remove("active-pct", "active-fixed"));

            if (type === "percent") {
                document.getElementById("global-disc-pct").classList.add("active-pct");
                document.getElementById("global-disc-unit").textContent = "%";
                document.getElementById("discount").max = 100;
            } else {
                document
                    .getElementById("global-disc-fixed")
                    .classList.add("active-fixed");
                document.getElementById("global-disc-unit").textContent = "FCFA";
                document.getElementById("discount").removeAttribute("max");
            }

            document.getElementById("type_discount").value = type;
            document.getElementById("discount").value = 0;
            recalcTotals();
        }

        // ── Recalcul ligne ───────────────────────────────────────────────────────────
        function recalcRow(pid) {
            const row = document.getElementById("row-" + pid);
            if (!row) return;
            if (row.classList.contains("table-danger")) return; // rupture → exclure

            const p = cartItems[pid];
            const qtyInput = row.querySelector(".qty-input");
            let qty = parseInt(qtyInput.value) || 0;
            const unitPrice =
                parseFloat(row.querySelector(".unit-price-input").value) || 0;
            let discountVal =
                parseFloat(row.querySelector(".discount-input").value) || 0;
            const typeDiscount =
                row.querySelector(".type-discount-input").value || "percent";

            // Bornes remise
            if (discountVal < 0) {
                discountVal = 0;
                row.querySelector(".discount-input").value = 0;
            }
            if (typeDiscount === "percent" && discountVal > 100) {
                discountVal = 100;
                row.querySelector(".discount-input").value = 100;
            }
            if (typeDiscount === "fixed" && discountVal > unitPrice) {
                discountVal = unitPrice;
                row.querySelector(".discount-input").value = unitPrice;
            }

            // Vérif stock
            if (p && p.stock !== null && qty > p.stock) {
                qty = p.stock;
                qtyInput.value = p.stock;
                Swal.fire({
                    icon: "warning",
                    title: "Stock insuffisant",
                    text: `Stock disponible : ${p.stock}`,
                    timer: 2000,
                    showConfirmButton: false,
                });
            }

            const prixApres =
                typeDiscount === "percent" ?
                unitPrice * (1 - discountVal / 100) :
                unitPrice - discountVal;

            const total = Math.max(0, prixApres) * qty;
            document.getElementById("total-" + pid).textContent =
                formatMoney(total) + " FCFA";
            recalcTotals();
        }

        // ── Supprimer une ligne ──────────────────────────────────────────────────────
        function removeRow(pid) {
            document.getElementById("row-" + pid)?.remove();
            delete cartItems[pid];

            if (!Object.keys(cartItems).length) {
                document.getElementById("items-body").innerHTML = `
                <tr id="empty-row">
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                        Aucun produit ajouté — utilisez la recherche ci-dessus.
                    </td>
                </tr>`;
            }
            recalcTotals();
        }

        // ── Recalcul totaux globaux ──────────────────────────────────────────────────
        function recalcTotals() {
            let subtotal = 0;

            Object.keys(cartItems).forEach((pid) => {
                const row = document.getElementById("row-" + pid);
                if (!row) return;
                if (row.classList.contains("table-danger")) return; // exclure ruptures

                const qty = parseInt(row.querySelector(".qty-input").value) || 0;
                const unitPrice =
                    parseFloat(row.querySelector(".unit-price-input").value) || 0;
                const discountVal =
                    parseFloat(row.querySelector(".discount-input").value) || 0;
                const typeDiscount =
                    row.querySelector(".type-discount-input").value || "percent";

                const prixApres =
                    typeDiscount === "percent" ?
                    unitPrice * (1 - discountVal / 100) :
                    unitPrice - discountVal;

                subtotal += Math.max(0, prixApres) * qty;
            });

            const globalDiscountVal =
                parseFloat(document.getElementById("discount").value) || 0;
            const globalDiscountType =
                document.getElementById("type_discount").value || "percent";
            const globalDiscountAmount =
                globalDiscountType === "percent" ?
                (subtotal * globalDiscountVal) / 100 :
                globalDiscountVal;

            const delivery =
                parseFloat(document.getElementById("delivery_price").value) || 0;
            const acompte = parseFloat(document.getElementById("acompte").value) || 0;
            const total = Math.max(0, subtotal - globalDiscountAmount + delivery);
            const solde = Math.max(0, total - acompte);

            currentTotal = total;
            document.getElementById("display-subtotal").textContent =
                formatMoney(subtotal) + " FCFA";
            document.getElementById("display-total").textContent =
                formatMoney(total) + " FCFA";
            document.getElementById("display-solde").textContent =
                formatMoney(solde) + " FCFA";
        }

        document.getElementById("discount").addEventListener("input", recalcTotals);
        document
            .getElementById("delivery_price")
            .addEventListener("input", recalcTotals);
        document.getElementById("acompte").addEventListener("input", recalcTotals);

        // ── Détail stock produits de base (popup) ────────────────────────────────────
        function showStockDetail(pid) {
            const p = cartItems[pid];
            if (!p || !p.stockInfo || !p.stockInfo.length) return;

            const lignes = p.stockInfo
                .map(
                    (b) => `
            <tr>
                <td><strong>${b.nom}</strong></td>
                <td class="text-center">
                    <span class="badge badge-${b.stock <= 0 ? "danger" : b.stock <= 5 ? "warning" : "success"}">
                        ${b.stock}
                    </span>
                </td>
                <td class="text-center">${b.coefficient}</td>
                <td class="text-center">
                    <span class="badge badge-${b.possible <= 0 ? "danger" : b.possible <= 5 ? "warning" : "info"}">
                        ${b.possible}
                    </span>
                </td>
                <td class="text-center">
                    ${
                        b.insuffisant
                            ? '<span class="badge badge-danger">Rupture</span>'
                            : '<span class="badge badge-success">OK</span>'
                    }
                </td>
            </tr>
        `,
                )
                .join("");

            Swal.fire({
                title: `<i class="fas fa-layer-group mr-2"></i>${p.title}`,
                html: `
                <div class="table-responsive">
                    <table class="table table-sm table-bordered mb-0" style="font-size:.85rem">
                        <thead class="thead-light">
                            <tr>
                                <th>Produit de base</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center">Coefficient</th>
                                <th class="text-center">Vendable</th>
                                <th class="text-center">Statut</th>
                            </tr>
                        </thead>
                        <tbody>${lignes}</tbody>
                    </table>
                </div>
            `,
                width: 600,
                showConfirmButton: true,
                confirmButtonText: "Fermer",
                confirmButtonColor: "#6c757d",
                customClass: {
                    htmlContainer: "text-left"
                },
            });
        }

        // ── Recherche client (AJAX) ──────────────────────────────────────────────────
        const clientSearch = document.getElementById("client-search");
        const clientResults = document.getElementById("client-results");

        let clientTimer;
        clientSearch.addEventListener("input", function() {
            clearTimeout(clientTimer);
            const q = this.value.trim();
            if (q.length < 2) {
                clientResults.style.display = "none";
                return;
            }
            clientTimer = setTimeout(() => {
                fetch(`{{ route('pos.searchClient') }}?q=${encodeURIComponent(q)}`)
                    .then((r) => r.json())
                    .then((users) => {
                        if (!users.length) {
                            clientResults.style.display = "none";
                            return;
                        }
                        clientResults.innerHTML = users
                            .map(
                                (u) => `
                        <a href="#" class="list-group-item list-group-item-action"
                            onclick="selectClient(event, ${u.id}, '${u.name.replace(/'/g, "\\'")}', '${(u.phone || "").replace(/'/g, "\\'")}', '${(u.email || "").replace(/'/g, "\\'")}')">
                            <strong>${u.name}</strong><br>
                            <small class="text-muted">${u.phone ?? ""} ${u.email ? "| " + u.email : ""}</small>
                        </a>
                    `,
                            )
                            .join("");
                        clientResults.style.display = "block";
                    });
            }, 300);
        });

        function selectClient(e, id, name, phone, email) {
            e.preventDefault();
            clientResults.style.display = "none";
            clientSearch.value = name + " — " + phone;
            document.getElementById("user_id").value = id;
            document.getElementById("cf-name").textContent = name;
            document.getElementById("cf-phone").textContent = phone;
            document.getElementById("cf-email").textContent = email;
            document.getElementById("client-found-box").classList.remove("d-none");
            document.getElementById("new-client-box").classList.add("d-none");
        }

        document
            .getElementById("btn-clear-client")
            .addEventListener("click", function() {
                document.getElementById("user_id").value = "";
                document.getElementById("client-found-box").classList.add("d-none");
                document.getElementById("new-client-box").classList.remove("d-none");
                clientSearch.value = "";
            });

        document.addEventListener("click", (e) => {
            if (
                !e.target.closest("#client-search") &&
                !e.target.closest("#client-results")
            ) {
                clientResults.style.display = "none";
            }
        });

        // ── Sélection source ─────────────────────────────────────────────────────────
        function selectSource(el) {
            document
                .querySelectorAll(".source-btn")
                .forEach((b) => b.classList.remove("active"));
            el.classList.add("active");
            document.getElementById("source-input").value = el.dataset.value;
        }

        // ── Validation avant soumission ──────────────────────────────────────────────
        document.getElementById("pos-form").addEventListener("submit", function(e) {
            e.preventDefault();

            const errors = [];
            const status = document.querySelector('[name="status"]').value;
            const isAttente = [
                "attente",
                "precommande",
                "en_attente_acompte",
                "annulée",
            ].includes(status);
            const isLivree = status === "livrée";

            // 1. Panier non vide (lignes non-rupture uniquement)
            const lignesActives = Object.keys(cartItems).filter((pid) => {
                const row = document.getElementById("row-" + pid);
                return row && !row.classList.contains("table-danger");
            });
            if (!lignesActives.length) {
                errors.push(
                    "Le panier est vide. Ajoutez au moins un produit disponible.",
                );
            }

            // 2. Téléphone client
            const userId = document.getElementById("user_id").value;
            const phone = document.querySelector('[name="client_phone"]').value.trim();
            if (!userId && phone.length < 8) {
                errors.push(
                    "Le téléphone du client est obligatoire (8 chiffres minimum).",
                );
            }

            // 3. Champs requis hors "en attente"
            if (!isAttente) {
                const acompte =
                    parseFloat(document.getElementById("acompte").value) || 0;
                if (isLivree) {
                    if (Math.round(acompte) !== Math.round(currentTotal)) {
                        errors.push(
                            `Pour une commande livrée, l'acompte (${formatMoney(acompte)} FCFA) doit être égal au total (${formatMoney(currentTotal)} FCFA).`,
                        );
                    }
                } else if (acompte <= 0) {
                    errors.push("L'acompte doit être supérieur à 0 pour ce statut.");
                }
                const paiement = document.querySelector(
                    '[name="payment_method_id"]',
                ).value;
                if (!paiement) {
                    errors.push("Le moyen de paiement est obligatoire pour ce statut.");
                }
            }

            if (errors.length > 0) {
                Swal.fire({
                    icon: "warning",
                    title: "Vérifiez le formulaire",
                    html: '<ul class="text-left mb-0">' +
                        errors.map((err) => `<li>${err}</li>`).join("") +
                        "</ul>",
                    confirmButtonText: "Corriger",
                    confirmButtonColor: "#4e73df",
                });
                return;
            }

            // ── Supprimer les inputs des lignes rupture avant envoi ──
            document.querySelectorAll("tr.table-danger").forEach(function(row) {
                row.querySelectorAll("input[name], select[name]").forEach(
                    function(el) {
                        el.remove();
                    },
                );
            });

            this.submit();
        });

        // ── Helper formatage ─────────────────────────────────────────────────────────
        function formatMoney(n) {
            return Math.round(n).toLocaleString("fr-FR");
        }
    </script>