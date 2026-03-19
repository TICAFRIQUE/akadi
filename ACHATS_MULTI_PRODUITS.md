# Achats Multi-Produits - Documentation

## 🎯 Nouveau fonctionnement

Le système d'achats a été **amélioré** pour permettre l'enregistrement de **plusieurs produits dans un seul achat**, comme un bon d'achat classique.

## 📊 Structure de données

### Table `achats` (En-tête)
```
- id
- numero (ex: ACH-20260318123456-789)
- date_achat
- fournisseur
- notes (générales)
- montant_total (calculé à partir des lignes)
- user_id
- timestamps
```

### Table `achat_lignes` (Détails)
```
- id
- achat_id (FK vers achats)
- product_base_id (FK vers product_bases)
- quantite
- prix_unitaire
- montant_ligne (calculé automatiquement)
- notes (spécifiques à cette ligne)
- timestamps
```

## 🔄 Migration depuis l'ancien système

L'ancien système stockait un seul produit par achat dans la table `achats`. Le nouveau système utilise une structure relationnelle :
- **1 achat** peut avoir **plusieurs lignes d'achat**
- Chaque **ligne d'achat** contient **1 produit de base**

## 💻 Utilisation du système

### Créer un achat avec plusieurs produits

```php
use App\Models\Achat;
use Illuminate\Support\Facades\DB;

DB::beginTransaction();
try {
    // 1. Créer l'achat
    $achat = Achat::create([
        'date_achat' => '2026-03-18',
        'fournisseur' => 'Fournisseur ABC',
        'notes' => 'Achat de la semaine',
        'montant_total' => 0, // Sera calculé
        'user_id' => auth()->id(),
    ]);

    // 2. Ajouter les lignes (produits)
    $lignes = [
        [
            'product_base_id' => 1, // Poulet
            'quantite' => 50,
            'prix_unitaire' => 2500,
        ],
        [
            'product_base_id' => 2, // Huile
            'quantite' => 20,
            'prix_unitaire' => 1500,
        ],
        [
            'product_base_id' => 3, // Oignons
            'quantite' => 10,
            'prix_unitaire' => 500,
        ],
    ];

    $montantTotal = 0;
    foreach ($lignes as $ligneData) {
        $ligne = $achat->lignes()->create($ligneData);
        $montantTotal += $ligne->montant_ligne;
    }

    // 3. Mettre à jour le montant total
    $achat->update(['montant_total' => $montantTotal]);

    DB::commit();
    
    // Résultat :
    // - Stock Poulet : +50 unités
    // - Stock Huile : +20 litres
    // - Stock Oignons : +10 kg
    // - Montant total : (50 × 2500) + (20 × 1500) + (10 × 500) = 155 000 FCFA
    
} catch (\Exception $e) {
    DB::rollBack();
    // Gérer l'erreur
}
```

### Via le contrôleur (formulaire)

Le contrôleur attend les données dans ce format :

```php
// POST /admin/achats/store
$request = [
    'date_achat' => '2026-03-18',
    'fournisseur' => 'Fournisseur ABC',
    'notes' => 'Notes générales',
    'lignes' => [
        [
            'product_base_id' => 1,
            'quantite' => 50,
            'prix_unitaire' => 2500,
            'notes' => 'Notes pour le poulet',
        ],
        [
            'product_base_id' => 2,
            'quantite' => 20,
            'prix_unitaire' => 1500,
            'notes' => null,
        ],
        [
            'product_base_id' => 3,
            'quantite' => 10,
            'prix_unitaire' => 500,
            'notes' => null,
        ],
    ],
];
```

### Mise à jour d'un achat existant

```php
// PUT /admin/achats/update/{id}
$request = [
    'date_achat' => '2026-03-18',
    'fournisseur' => 'Fournisseur ABC',
    'notes' => 'Notes mises à jour',
    'lignes' => [
        [
            'id' => 1, // Ligne existante - sera mise à jour
            'product_base_id' => 1,
            'quantite' => 60, // Modifié de 50 à 60
            'prix_unitaire' => 2500,
        ],
        [
            // Pas d'id - nouvelle ligne ajoutée
            'product_base_id' => 4,
            'quantite' => 5,
            'prix_unitaire' => 3000,
        ],
        // La ligne 2 et 3 seront supprimées si absentes
    ],
];
```

## 🔧 Gestion automatique du stock

### Lors de la création d'une ligne
```
Nouvelle ligne : 50 Poulets à 2500 FCFA
→ Stock Poulet : +50 unités ✅
→ Prix achat moyen recalculé ✅
```

### Lors de la modification d'une ligne
```
Ligne modifiée : 50 → 60 Poulets
→ Stock Poulet : +10 unités (différence) ✅
→ Prix achat moyen recalculé ✅
```

### Lors de la suppression d'une ligne
```
Ligne supprimée : 20 Litres d'huile
→ Stock Huile : -20 litres ✅
→ Prix achat moyen recalculé ✅
```

### Lors de la suppression d'un achat complet
```
Achat supprimé avec 3 lignes
→ Stock de chaque produit décrémenté ✅
→ Prix achat moyen recalculé pour chaque produit ✅
```

## 📋 Exemple de formulaire HTML

```html
<form action="{{ route('achats.store') }}" method="POST">
    @csrf
    
    <!-- En-tête de l'achat -->
    <div>
        <label>Date d'achat</label>
        <input type="date" name="date_achat" required>
    </div>
    
    <div>
        <label>Fournisseur</label>
        <input type="text" name="fournisseur">
    </div>
    
    <div>
        <label>Notes</label>
        <textarea name="notes"></textarea>
    </div>
    
    <!-- Lignes d'achat (produits) -->
    <div id="lignes-container">
        <h3>Produits</h3>
        
        <div class="ligne-achat">
            <select name="lignes[0][product_base_id]" required>
                <option value="">Sélectionner un produit</option>
                @foreach($productBases as $pb)
                    <option value="{{ $pb->id }}">{{ $pb->nom }}</option>
                @endforeach
            </select>
            
            <input type="number" name="lignes[0][quantite]" 
                   step="0.01" placeholder="Quantité" required>
            
            <input type="number" name="lignes[0][prix_unitaire]" 
                   step="0.01" placeholder="Prix unitaire" required>
            
            <input type="text" name="lignes[0][notes]" 
                   placeholder="Notes (optionnel)">
        </div>
        
        <!-- Ajouter d'autres lignes avec un bouton JavaScript -->
    </div>
    
    <button type="button" onclick="ajouterLigne()">+ Ajouter un produit</button>
    
    <button type="submit">Enregistrer l'achat</button>
</form>

<script>
let ligneIndex = 1;

function ajouterLigne() {
    const container = document.getElementById('lignes-container');
    const newLigne = `
        <div class="ligne-achat">
            <select name="lignes[${ligneIndex}][product_base_id]" required>
                <option value="">Sélectionner un produit</option>
                @foreach($productBases as $pb)
                    <option value="{{ $pb->id }}">{{ $pb->nom }}</option>
                @endforeach
            </select>
            
            <input type="number" name="lignes[${ligneIndex}][quantite]" 
                   step="0.01" placeholder="Quantité" required>
            
            <input type="number" name="lignes[${ligneIndex}][prix_unitaire]" 
                   step="0.01" placeholder="Prix unitaire" required>
            
            <input type="text" name="lignes[${ligneIndex}][notes]" 
                   placeholder="Notes (optionnel)">
                   
            <button type="button" onclick="supprimerLigne(this)">Supprimer</button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', newLigne);
    ligneIndex++;
}

function supprimerLigne(button) {
    button.closest('.ligne-achat').remove();
}
</script>
```

## 📊 Exemple de requête API/AJAX

```javascript
// Enregistrer un achat via AJAX
fetch('/admin/achats/store', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
        date_achat: '2026-03-18',
        fournisseur: 'Fournisseur ABC',
        notes: 'Notes générales',
        lignes: [
            {
                product_base_id: 1,
                quantite: 50,
                prix_unitaire: 2500,
                notes: null
            },
            {
                product_base_id: 2,
                quantite: 20,
                prix_unitaire: 1500
            }
        ]
    })
})
.then(response => response.json())
.then(data => console.log('Achat créé !', data))
.catch(error => console.error('Erreur:', error));
```

## 🔍 Consulter les achats

```php
// Récupérer un achat avec ses lignes
$achat = Achat::with('lignes.productBase')->find($id);

echo "Achat N° {$achat->numero}";
echo "Date : {$achat->date_achat}";
echo "Fournisseur : {$achat->fournisseur}";
echo "Montant total : {$achat->montant_total} FCFA";

foreach ($achat->lignes as $ligne) {
    echo "{$ligne->productBase->nom} : {$ligne->quantite} × {$ligne->prix_unitaire} = {$ligne->montant_ligne} FCFA";
}
```

## 📈 Rapports

Le rapport par période regroupe maintenant les lignes d'achat par produit :

```php
// GET /admin/achats/rapport?date_debut=2026-03-01&date_fin=2026-03-31

// Résultat :
[
    'par_produit' => [
        1 => [
            'produit' => 'Poulet',
            'quantite_totale' => 150,
            'montant_total' => 375000,
            'nombre_achats' => 5,
            'unite' => 'unité'
        ],
        2 => [
            'produit' => 'Huile',
            'quantite_totale' => 60,
            'montant_total' => 90000,
            'nombre_achats' => 3,
            'unite' => 'litre'
        ],
        // ...
    ]
]
```

## ⚠️ Points importants

1. **Transactions SQL** : Toujours utiliser `DB::beginTransaction()` lors de la création/mise à jour d'achats avec plusieurs lignes

2. **Validation** : Le tableau `lignes` doit contenir au moins 1 élément

3. **Gestion du stock** : Le stock est géré automatiquement par les événements du modèle `AchatLigne`

4. **Prix d'achat moyen** : Calculé automatiquement après chaque création/modification/suppression de ligne

5. **Suppression en cascade** : Si un achat est supprimé, toutes ses lignes sont supprimées automatiquement

## 🆚 Comparaison Ancien vs Nouveau

### Ancien système
```
1 Achat = 1 Produit
Si vous achetez 3 produits différents → 3 achats séparés
```

### Nouveau système
```
1 Achat = Plusieurs Produits (lignes)
Si vous achetez 3 produits différents → 1 achat avec 3 lignes
```

## ✅ Avantages

- ✅ Plus proche de la réalité (bon d'achat avec plusieurs produits)
- ✅ Meilleure traçabilité (un bon d'achat = un numéro)
- ✅ Gestion simplifiée des achats groupés
- ✅ Rapports plus précis par fournisseur
- ✅ Stock géré automatiquement pour chaque ligne

---

**Date de mise à jour :** 18 mars 2026  
**Version :** 2.0
