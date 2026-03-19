# Système de Gestion des Achats et Stock de Produits de Base

## Vue d'ensemble

Le système a été mis en place avec succès ! Il permet de gérer les produits de base (matières premières) et leur stock, ainsi que la décrémentation automatique du stock lors des ventes.

## Architecture

### 1. Tables créées

#### `product_bases` - Produits de base (matières premières)
- `id` : Identifiant unique
- `code` : Code unique du produit de base
- `nom` : Nom du produit (ex: "Poulet", "Huile", etc.)
- `description` : Description
- `stock` : Quantité en stock (DECIMAL 10,2)
- `stock_alerte` : Seuil d'alerte pour stock faible
- `unite` : Unité de mesure (unité, kg, litre, etc.)
- `prix_achat_moyen` : Prix d'achat moyen calculé automatiquement
- `actif` : Statut actif/inactif
- `timestamps` et `soft_deletes`

#### `achats` - Enregistrement des achats
- `id` : Identifiant unique
- `numero` : Numéro unique de bon d'achat (auto-généré)
- `date_achat` : Date de l'achat
- `product_base_id` : Référence au produit de base
- `quantite` : Quantité achetée
- `prix_unitaire` : Prix d'achat unitaire
- `montant_total` : Total (calculé automatiquement)
- `fournisseur` : Nom du fournisseur
- `notes` : Notes supplémentaires
- `user_id` : Utilisateur qui a enregistré l'achat
- `timestamps` et `soft_deletes`

#### Modifications dans `products`
- `product_base_id` : Lien vers le produit de base utilisé
- `coefficient` : Coefficient de consommation (ex: 1, 0.5, etc.)

#### Modifications dans `order_product` (pivot)
- `coefficient` : Coefficient historique pour les rapports

## Fonctionnement

### 1. Création d'un produit de base

```php
// Exemple: Créer "Poulet" comme produit de base
ProductBase::create([
    'nom' => 'Poulet',
    'stock' => 100,
    'stock_alerte' => 10,
    'unite' => 'unité',
]);
```

### 2. Lier un produit vendu à un produit de base

```php
// Exemple: Poulet braisé consomme 1 unité de poulet
$product = Product::find($id);
$product->update([
    'product_base_id' => $poulet_base_id,
    'coefficient' => 1, // 1 poulet braisé = 1 poulet de base
]);

// Exemple: Demi-poulet consomme 0.5 unité
$demiPoulet->update([
    'product_base_id' => $poulet_base_id,
    'coefficient' => 0.5,
]);
```

### 3. Enregistrement d'un achat

Lorsqu'un achat est enregistré :
- Le stock du produit de base est **automatiquement incrémenté**
- Le prix d'achat moyen est **recalculé automatiquement**

```php
Achat::create([
    'date_achat' => '2026-03-18',
    'product_base_id' => $poulet_base_id,
    'quantite' => 50,
    'prix_unitaire' => 2500,
    'fournisseur' => 'Fournisseur ABC',
    'user_id' => auth()->id(),
]);
// → Le stock de poulet passe automatiquement de 100 à 150
```

### 4. Lors d'une vente

Quand une commande est créée avec des produits :
- Le **coefficient est sauvegardé** dans la table `order_product` pour l'historique
- Le **stock du produit de base est décrémenté** automatiquement

```php
// Exemple: Vente de 3 poulets braisés
// → Décrémente 3 × 1 = 3 unités du stock de poulet de base

// Exemple: Vente de 4 demi-poulets
// → Décrémente 4 × 0.5 = 2 unités du stock de poulet de base
```

## Routes disponibles

### Produits de base
- `GET /admin/product-bases` - Liste des produits de base
- `GET /admin/product-bases/create` - Formulaire de création
- `POST /admin/product-bases/store` - Enregistrer un nouveau produit de base
- `GET /admin/product-bases/show/{id}` - Détails d'un produit de base
- `GET /admin/product-bases/edit/{id}` - Formulaire d'édition
- `PUT /admin/product-bases/update/{id}` - Mettre à jour
- `DELETE /admin/product-bases/destroy/{id}` - Supprimer
- `GET /admin/product-bases/api/list` - API JSON pour récupérer la liste

### Achats
- `GET /admin/achats` - Liste des achats
- `GET /admin/achats/create` - Formulaire d'achat
- `POST /admin/achats/store` - Enregistrer un achat
- `GET /admin/achats/show/{id}` - Détails d'un achat
- `GET /admin/achats/edit/{id}` - Formulaire d'édition
- `PUT /admin/achats/update/{id}` - Mettre à jour
- `DELETE /admin/achats/destroy/{id}` - Supprimer
- `GET /admin/achats/rapport` - Rapport des achats par période

## Permissions

Les routes utilisent les permissions suivantes :
- `achats.produits-base` - Gestion des produits de base
- `achats.gestion` - Gestion des achats

**Note:** Ces permissions doivent être créées dans votre système de permissions.

## Fichiers créés/modifiés

### Migrations
- `2026_03_18_000001_create_product_bases_table.php`
- `2026_03_18_000002_create_achats_table.php`
- `2026_03_18_000003_add_product_base_to_products_table.php`
- `2026_03_18_000004_add_coefficient_to_order_product_table.php`

### Modèles
- `app/Models/ProductBase.php` - Modèle produit de base
- `app/Models/Achat.php` - Modèle achat avec gestion automatique du stock
- `app/Models/Product.php` - Modifié pour ajouter la relation

### Contrôleurs
- `app/Http/Controllers/ProductBaseController.php`
- `app/Http/Controllers/AchatController.php`

### Services
- `app/Services/StockService.php` - Service centralisé pour la gestion des stocks
- `app/Observers/OrderObserver.php` - Observer pour les commandes (optionnel)

### Contrôleurs modifiés
- `app/Http/Controllers/site/PaymentController.php` - Intégration du StockService
- `app/Http/Controllers/admin/PosController.php` - Intégration du StockService
- `app/Http/Controllers/api_frontend/OrderController.php` - Intégration du StockService

## Prochaines étapes

### 1. Créer les permissions
```sql
INSERT INTO permissions (name, guard_name) VALUES 
    ('achats.produits-base', 'web'),
    ('achats.gestion', 'web');
```

### 2. Créer les vues (facultatif)
Vous pouvez créer les vues Blade pour :
- `resources/views/product-bases/index.blade.php`
- `resources/views/product-bases/create.blade.php`
- `resources/views/product-bases/edit.blade.php`
- `resources/views/product-bases/show.blade.php`
- `resources/views/achats/index.blade.php`
- `resources/views/achats/create.blade.php`
- `resources/views/achats/edit.blade.php`
- `resources/views/achats/show.blade.php`
- `resources/views/achats/rapport.blade.php`

### 3. Ajouter des produits de base
Créez vos produits de base (poulet, huile, épices, etc.)

### 4. Configurer les coefficients
Pour chaque produit vendu, ajoutez:
- Le `product_base_id`
- Le `coefficient` approprié

### 5. Enregistrer les achats
Commencez à enregistrer vos achats de matières premières

## Logs et surveillance

Le système génère des logs détaillés :
- Décrémentation du stock lors des ventes
- Stock insuffisant (warnings)
- Réincrémentation lors d'annulation de commande

Consultez les logs dans `storage/logs/laravel.log`

## Rapports disponibles

### Stock faible
```php
$stocksFaibles = ProductBase::whereColumn('stock', '<=', 'stock_alerte')
    ->where('actif', true)
    ->get();
```

### Achats par période
Utilisez la route `/admin/achats/rapport` avec les paramètres `date_debut` et `date_fin`

## Support et maintenance

Le système est conçu pour être robuste et automatique :
- ✅ Incrémentation automatique du stock lors des achats
- ✅ Décrémentation automatique lors des ventes
- ✅ Calcul automatique du prix d'achat moyen
- ✅ Historique des coefficients pour rapports précis
- ✅ Gestion des annulations de commande
- ✅ Logs détaillés pour debugging

---

**Date de mise en place:** 18 mars 2026
**Version:** 1.0
