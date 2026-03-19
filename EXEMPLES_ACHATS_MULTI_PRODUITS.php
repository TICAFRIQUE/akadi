<?php

/**
 * EXEMPLES D'UTILISATION - ACHATS MULTI-PRODUITS
 * 
 * Ce fichier contient des exemples pratiques d'utilisation du système d'achats
 * avec plusieurs produits par achat.
 * 
 * NE PAS EXÉCUTER CE FICHIER DIRECTEMENT - Ce sont juste des exemples de code.
 */

use App\Models\Achat;
use App\Models\AchatLigne;
use App\Models\ProductBase;
use Illuminate\Support\Facades\DB;

// ============================================================================
// 1. CRÉER UN ACHAT AVEC PLUSIEURS PRODUITS
// ============================================================================

DB::beginTransaction();
try {
    // Créer l'achat (en-tête)
    $achat = Achat::create([
        'date_achat' => '2026-03-18',
        'fournisseur' => 'Fournisseur ABC',
        'notes' => 'Achat hebdomadaire',
        'montant_total' => 0, // Sera calculé automatiquement
        'user_id' => auth()->id(),
    ]);

    // Ajouter les lignes (produits)
    $lignes = [
        [
            'product_base_id' => 1, // Poulet
            'quantite' => 50,
            'prix_unitaire' => 2500,
            'notes' => 'Poulets frais de qualité',
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
        
        // Le stock est automatiquement incrémenté :
        // - Poulet : +50 unités
        // - Huile : +20 litres
        // - Oignons : +10 kg
    }

    // Mettre à jour le montant total
    $achat->update(['montant_total' => $montantTotal]);
    // Total = (50 × 2500) + (20 × 1500) + (10 × 500) = 155 000 FCFA

    DB::commit();
    
    echo "Achat créé avec succès !\n";
    echo "Numéro : {$achat->numero}\n";
    echo "Montant total : {$achat->montant_total} FCFA\n";
    echo "Nombre de lignes : " . $achat->lignes->count() . "\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "Erreur : " . $e->getMessage();
}

// ============================================================================
// 2. CONSULTER UN ACHAT AVEC SES LIGNES
// ============================================================================

$achat = Achat::with('lignes.productBase')->find($achatId);

echo "=== ACHAT N° {$achat->numero} ===\n";
echo "Date : {$achat->date_achat->format('d/m/Y')}\n";
echo "Fournisseur : {$achat->fournisseur}\n";
echo "\n--- Détails ---\n";

foreach ($achat->lignes as $ligne) {
    echo "{$ligne->productBase->nom} : ";
    echo "{$ligne->quantite} {$ligne->productBase->unite} × ";
    echo "{$ligne->prix_unitaire} FCFA = ";
    echo "{$ligne->montant_ligne} FCFA\n";
}

echo "\nMontant total : {$achat->montant_total} FCFA\n";

// Exemple de sortie :
// === ACHAT N° ACH-20260318123456-789 ===
// Date : 18/03/2026
// Fournisseur : Fournisseur ABC
//
// --- Détails ---
// Poulet : 50 unité × 2500 FCFA = 125000 FCFA
// Huile : 20 litre × 1500 FCFA = 30000 FCFA
// Oignons : 10 kg × 500 FCFA = 5000 FCFA
//
// Montant total : 160000 FCFA

// ============================================================================
// 3. METTRE À JOUR UN ACHAT
// ============================================================================

DB::beginTransaction();
try {
    $achat = Achat::find($achatId);

    // Mettre à jour l'en-tête
    $achat->update([
        'date_achat' => '2026-03-19',
        'fournisseur' => 'Nouveau Fournisseur',
        'notes' => 'Notes mises à jour',
    ]);

    // Récupérer les IDs des lignes à conserver
    $lignesAGarder = [1, 2]; // IDs des lignes existantes

    // Supprimer les lignes qui ne sont plus dans la liste
    $achat->lignes()->whereNotIn('id', $lignesAGarder)->delete();
    // → Stock automatiquement décrémenté pour les lignes supprimées

    // Mettre à jour une ligne existante
    $ligne1 = $achat->lignes()->find(1);
    $ligne1->update([
        'quantite' => 60, // Changé de 50 à 60
        'prix_unitaire' => 2600,
    ]);
    // → Stock Poulet : +10 unités (différence)

    // Ajouter une nouvelle ligne
    $nouvelleLigne = $achat->lignes()->create([
        'product_base_id' => 4,
        'quantite' => 15,
        'prix_unitaire' => 800,
    ]);
    // → Stock du produit 4 : +15 unités

    // Recalculer le montant total
    $achat->calculerMontantTotal();

    DB::commit();
    
    echo "Achat mis à jour avec succès !\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "Erreur : " . $e->getMessage();
}

// ============================================================================
// 4. SUPPRIMER UNE LIGNE D'ACHAT
// ============================================================================

$ligne = AchatLigne::find($ligneId);
$productBaseName = $ligne->productBase->nom;
$quantite = $ligne->quantite;

$ligne->delete();
// → Stock automatiquement décrémenté
// → Prix d'achat moyen recalculé

echo "Ligne supprimée : {$quantite} {$productBaseName}\n";

// ============================================================================
// 5. SUPPRIMER UN ACHAT COMPLET
// ============================================================================

$achat = Achat::find($achatId);
$numeroAchat = $achat->numero;

$achat->delete();
// → Toutes les lignes sont supprimées (cascade)
// → Stock décrémenté pour chaque ligne
// → Prix d'achat moyen recalculé pour chaque produit

echo "Achat {$numeroAchat} supprimé avec toutes ses lignes\n";

// ============================================================================
// 6. RÉCUPÉRER TOUS LES ACHATS D'UN PRODUIT DE BASE
// ============================================================================

$poulet = ProductBase::find(1);

// Via les lignes d'achat
$lignesAchat = $poulet->achatLignes()
    ->with('achat')
    ->orderBy('created_at', 'desc')
    ->get();

foreach ($lignesAchat as $ligne) {
    echo "Achat {$ligne->achat->numero} - ";
    echo "{$ligne->achat->date_achat->format('d/m/Y')} : ";
    echo "{$ligne->quantite} unités à {$ligne->prix_unitaire} FCFA\n";
}

// Via les achats (relation hasManyThrough)
$achats = $poulet->achats()
    ->orderBy('date_achat', 'desc')
    ->get();

echo "\nNombre d'achats contenant du poulet : " . $achats->count() . "\n";

// ============================================================================
// 7. RAPPORT PAR FOURNISSEUR
// ============================================================================

$fournisseur = 'Fournisseur ABC';
$dateDebut = '2026-03-01';
$dateFin = '2026-03-31';

$achats = Achat::where('fournisseur', $fournisseur)
    ->whereBetween('date_achat', [$dateDebut, $dateFin])
    ->with('lignes.productBase')
    ->get();

echo "=== RAPPORT FOURNISSEUR : {$fournisseur} ===\n";
echo "Période : {$dateDebut} au {$dateFin}\n\n";

$totalGeneral = 0;
$produitsAchetes = [];

foreach ($achats as $achat) {
    echo "Achat {$achat->numero} - {$achat->date_achat->format('d/m/Y')}\n";
    
    foreach ($achat->lignes as $ligne) {
        $produit = $ligne->productBase->nom;
        
        if (!isset($produitsAchetes[$produit])) {
            $produitsAchetes[$produit] = [
                'quantite' => 0,
                'montant' => 0,
                'unite' => $ligne->productBase->unite,
            ];
        }
        
        $produitsAchetes[$produit]['quantite'] += $ligne->quantite;
        $produitsAchetes[$produit]['montant'] += $ligne->montant_ligne;
    }
    
    $totalGeneral += $achat->montant_total;
}

echo "\n--- Résumé par produit ---\n";
foreach ($produitsAchetes as $produit => $data) {
    echo "{$produit} : {$data['quantite']} {$data['unite']} - {$data['montant']} FCFA\n";
}

echo "\nMontant total : {$totalGeneral} FCFA\n";

// ============================================================================
// 8. CALCULER LE PRIX D'ACHAT MOYEN D'UN PRODUIT
// ============================================================================

$poulet = ProductBase::find(1);

$prixMoyen = $poulet->calculerPrixAchatMoyen();

echo "Prix d'achat moyen du poulet : {$prixMoyen} FCFA\n";
echo "Stock actuel : {$poulet->stock} {$poulet->unite}\n";
echo "Valeur du stock : " . ($poulet->stock * $prixMoyen) . " FCFA\n";

// ============================================================================
// 9. CRÉER UN ACHAT DEPUIS UN FORMULAIRE (EXEMPLE)
// ============================================================================

// Données reçues du formulaire
$formData = [
    'date_achat' => '2026-03-18',
    'fournisseur' => 'Fournisseur ABC',
    'notes' => 'Notes',
    'lignes' => [
        [
            'product_base_id' => '1',
            'quantite' => '50',
            'prix_unitaire' => '2500',
            'notes' => '',
        ],
        [
            'product_base_id' => '2',
            'quantite' => '20',
            'prix_unitaire' => '1500',
            'notes' => 'Huile de qualité',
        ],
    ],
];

DB::beginTransaction();
try {
    $achat = Achat::create([
        'date_achat' => $formData['date_achat'],
        'fournisseur' => $formData['fournisseur'],
        'notes' => $formData['notes'],
        'montant_total' => 0,
        'user_id' => auth()->id(),
    ]);

    $montantTotal = 0;
    foreach ($formData['lignes'] as $ligneData) {
        // Filtrer les champs vides
        $ligneData = array_filter($ligneData);
        
        $ligne = $achat->lignes()->create([
            'product_base_id' => $ligneData['product_base_id'],
            'quantite' => $ligneData['quantite'],
            'prix_unitaire' => $ligneData['prix_unitaire'],
            'notes' => $ligneData['notes'] ?? null,
        ]);
        
        $montantTotal += $ligne->montant_ligne;
    }

    $achat->update(['montant_total' => $montantTotal]);

    DB::commit();
    
    return redirect()->route('achats.index')->with('success', 'Achat créé !');
    
} catch (\Exception $e) {
    DB::rollBack();
    return back()->with('error', $e->getMessage())->withInput();
}

// ============================================================================
// 10. VÉRIFIER LES STOCKS APRÈS ACHATS
// ============================================================================

// Créer un rapport de vérification
$productBases = ProductBase::with('achatLignes')->get();

echo "=== RAPPORT DE STOCK ===\n\n";

foreach ($productBases as $pb) {
    echo "{$pb->nom} :\n";
    echo "  Stock actuel : {$pb->stock} {$pb->unite}\n";
    echo "  Prix achat moyen : {$pb->prix_achat_moyen} FCFA\n";
    echo "  Nombre d'achats : " . $pb->achatLignes->count() . "\n";
    echo "  Valeur du stock : " . ($pb->stock * $pb->prix_achat_moyen) . " FCFA\n";
    
    if ($pb->isStockFaible()) {
        echo "  ⚠️ ALERTE STOCK FAIBLE (Seuil : {$pb->stock_alerte})\n";
    }
    
    echo "\n";
}
