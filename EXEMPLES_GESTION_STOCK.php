<?php

/**
 * EXEMPLES D'UTILISATION DU SYSTÈME DE GESTION DES ACHATS ET STOCK
 * 
 * Ce fichier contient des exemples pratiques d'utilisation du système.
 * NE PAS EXÉCUTER CE FICHIER DIRECTEMENT - Ce sont juste des exemples de code.
 */

use App\Models\ProductBase;
use App\Models\Achat;
use App\Models\Product;
use App\Models\Order;
use App\Services\StockService;

// ============================================================================
// 1. CRÉER UN PRODUIT DE BASE
// ============================================================================

// Exemple: Créer "Poulet" comme produit de base
$poulet = ProductBase::create([
    'nom' => 'Poulet',
    'description' => 'Poulet entier pour préparations',
    'stock' => 0, // Stock initial
    'stock_alerte' => 10, // Alerte quand stock <= 10
    'unite' => 'unité',
]);

// Exemple: Créer d'autres produits de base
$huile = ProductBase::create([
    'nom' => 'Huile de palme',
    'stock' => 0,
    'stock_alerte' => 5,
    'unite' => 'litre',
]);

$oignons = ProductBase::create([
    'nom' => 'Oignons',
    'stock' => 0,
    'stock_alerte' => 2,
    'unite' => 'kg',
]);

// ============================================================================
// 2. ENREGISTRER UN ACHAT (LE STOCK S'INCRÉMENTE AUTOMATIQUEMENT)
// ============================================================================

// Acheter 50 poulets à 2500 FCFA l'unité
$achat1 = Achat::create([
    'date_achat' => now(),
    'product_base_id' => $poulet->id,
    'quantite' => 50,
    'prix_unitaire' => 2500,
    'fournisseur' => 'Ferme Avicole ABC',
    'notes' => 'Poulets de qualité supérieure',
    'user_id' => auth()->id(),
]);
// → Le stock de poulet passe automatiquement à 50
// → Le prix d'achat moyen est calculé automatiquement

// Acheter 20 litres d'huile
$achat2 = Achat::create([
    'date_achat' => now(),
    'product_base_id' => $huile->id,
    'quantite' => 20,
    'prix_unitaire' => 1500,
    'fournisseur' => 'Distributeur XYZ',
    'user_id' => auth()->id(),
]);
// → Le stock d'huile passe à 20 litres

// ============================================================================
// 3. LIER UN PRODUIT VENDU À UN PRODUIT DE BASE
// ============================================================================

// Exemple 1: Poulet braisé consomme 1 poulet entier
$pouletBraise = Product::where('title', 'Poulet braisé')->first();
$pouletBraise->update([
    'product_base_id' => $poulet->id,
    'coefficient' => 1, // 1 poulet braisé = 1 poulet de base
]);

// Exemple 2: Demi-poulet consomme 0.5 poulet
$demiPoulet = Product::where('title', 'Demi-poulet braisé')->first();
$demiPoulet->update([
    'product_base_id' => $poulet->id,
    'coefficient' => 0.5, // 1 demi-poulet = 0.5 poulet de base
]);

// Exemple 3: Quart de poulet
$quartPoulet = Product::where('title', 'Quart de poulet')->first();
$quartPoulet->update([
    'product_base_id' => $poulet->id,
    'coefficient' => 0.25, // 1 quart = 0.25 poulet de base
]);

// Exemple 4: Poulet fumé (utilise aussi du poulet de base)
$pouletFume = Product::where('title', 'Poulet fumé')->first();
$pouletFume->update([
    'product_base_id' => $poulet->id,
    'coefficient' => 1,
]);

// ============================================================================
// 4. VÉRIFIER LE STOCK DISPONIBLE AVANT UNE VENTE
// ============================================================================

$stockService = app(StockService::class);

$panier = [
    1 => ['quantity' => 3, 'price' => 5000], // 3 poulets braisés
    2 => ['quantity' => 2, 'price' => 3000], // 2 demi-poulets
];

$verification = $stockService->checkStockAvailability($panier);

if (!$verification['success']) {
    // Stock insuffisant
    foreach ($verification['products_insuffisants'] as $produit) {
        echo "Stock insuffisant pour {$produit['product_name']}\n";
        echo "Stock disponible: {$produit['stock_disponible']} {$produit['unite']}\n";
        echo "Quantité nécessaire: {$produit['quantite_necessaire']} {$produit['unite']}\n";
    }
}

// ============================================================================
// 5. CRÉER UNE COMMANDE (LA DÉCRÉMENTATION EST AUTOMATIQUE)
// ============================================================================

// Méthode manuelle (non recommandée)
$order = Order::create([
    'user_id' => auth()->id(),
    'total' => 16000,
    // ... autres champs
]);

// Attacher les produits AVEC décrémentation automatique du stock
$stockService->attachCartAndDecrementStock($order, $panier);

// Résultat:
// - 3 poulets braisés × coefficient 1 = 3 poulets décrementés
// - 2 demi-poulets × coefficient 0.5 = 1 poulet décrémenté
// → Total décrémenté du stock de poulet: 4 unités
// → Stock de poulet passe de 50 à 46

// ============================================================================
// 6. CONSULTER L'ÉTAT DES STOCKS
// ============================================================================

// Tous les produits de base
$produitsBases = ProductBase::all();

foreach ($produitsBases as $pb) {
    echo "{$pb->nom}: {$pb->stock} {$pb->unite}\n";
    
    if ($pb->isStockFaible()) {
        echo "⚠️ ALERTE STOCK FAIBLE!\n";
    }
}

// Produits avec stock faible
$stocksFaibles = ProductBase::whereColumn('stock', '<=', 'stock_alerte')
    ->where('actif', true)
    ->get();

foreach ($stocksFaibles as $pb) {
    echo "⚠️ {$pb->nom}: {$pb->stock} {$pb->unite} (Seuil: {$pb->stock_alerte})\n";
}

// ============================================================================
// 7. CONSULTER L'HISTORIQUE DES ACHATS
// ============================================================================

// Tous les achats d'un produit de base
$achats = Achat::where('product_base_id', $poulet->id)
    ->orderBy('date_achat', 'desc')
    ->get();

foreach ($achats as $achat) {
    echo "Date: {$achat->date_achat}\n";
    echo "Quantité: {$achat->quantite}\n";
    echo "Prix unitaire: {$achat->prix_unitaire} FCFA\n";
    echo "Montant total: {$achat->montant_total} FCFA\n";
    echo "Fournisseur: {$achat->fournisseur}\n";
    echo "---\n";
}

// Achats par période
$debut = '2026-03-01';
$fin = '2026-03-31';

$achats = Achat::whereBetween('date_achat', [$debut, $fin])
    ->with('productBase')
    ->get();

$montantTotal = $achats->sum('montant_total');
echo "Total des achats de mars 2026: {$montantTotal} FCFA\n";

// ============================================================================
// 8. CONSULTER LES PRODUITS VENDUS LIÉS À UN PRODUIT DE BASE
// ============================================================================

// Tous les produits vendus qui utilisent du poulet
$produitsAvecPoulet = Product::where('product_base_id', $poulet->id)
    ->where('disponibilite', 1)
    ->get();

foreach ($produitsAvecPoulet as $product) {
    echo "{$product->title} - Coefficient: {$product->coefficient}\n";
}

// ============================================================================
// 9. CALCUL PRÉVISIONNEL DES ACHATS
// ============================================================================

// Calculer combien de poulets il faut acheter pour satisfaire des ventes prévues
$ventesPrevisionnelles = [
    'Poulet braisé' => 100, // 100 ventes prévues
    'Demi-poulet braisé' => 50,
    'Quart de poulet' => 40,
];

$poulets_necessaires = 0;
$poulets_necessaires += $ventesPrevisionnelles['Poulet braisé'] * 1;      // 100 × 1 = 100
$poulets_necessaires += $ventesPrevisionnelles['Demi-poulet braisé'] * 0.5; // 50 × 0.5 = 25
$poulets_necessaires += $ventesPrevisionnelles['Quart de poulet'] * 0.25;   // 40 × 0.25 = 10

echo "Poulets nécessaires: {$poulets_necessaires} unités\n";
echo "Stock actuel: {$poulet->stock} unités\n";
echo "À acheter: " . max(0, $poulets_necessaires - $poulet->stock) . " unités\n";

// ============================================================================
// 10. RAPPORT DE CONSOMMATION
// ============================================================================

// Consommation d'un produit de base sur une période
$commandes = Order::whereBetween('date_order', [$debut, $fin])
    ->with('products.productBase')
    ->get();

$consommationParProduitBase = [];

foreach ($commandes as $order) {
    foreach ($order->products as $product) {
        if ($product->productBase) {
            $pbId = $product->productBase->id;
            $pbNom = $product->productBase->nom;
            
            if (!isset($consommationParProduitBase[$pbId])) {
                $consommationParProduitBase[$pbId] = [
                    'nom' => $pbNom,
                    'quantite' => 0,
                    'unite' => $product->productBase->unite,
                ];
            }
            
            // Utiliser le coefficient historique de la commande
            $coefficient = $product->pivot->coefficient ?? $product->coefficient;
            $consommationParProduitBase[$pbId]['quantite'] += $product->pivot->quantity * $coefficient;
        }
    }
}

foreach ($consommationParProduitBase as $data) {
    echo "{$data['nom']}: {$data['quantite']} {$data['unite']} consommés\n";
}

// ============================================================================
// 11. GESTION DES ANNULATIONS
// ============================================================================

// Quand une commande est annulée, le stock est automatiquement réincrémenté
$order = Order::find($orderId);
$order->update(['status' => Order::STATUS_ANNULEE]);
// → Le stock est automatiquement réincrémenté avec les bonnes quantités

// Ou en utilisant le service
$stockService->reincrementStockOnCancellation($order);
