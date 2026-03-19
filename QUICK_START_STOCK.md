# Guide Rapide de Démarrage - Gestion des Achats et Stock

## 📋 Checklist de mise en place

### ✅ Étapes complétées

- [x] Migrations exécutées
- [x] Tables créées (product_bases, achats)
- [x] Modèles créés (ProductBase, Achat)
- [x] Service de gestion du stock créé
- [x] Contrôleurs créés
- [x] Routes configurées
- [x] Logique de décrémentation automatique intégrée

### 🔲 Étapes à faire

1. **Créer les permissions**
   ```sql
   INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES 
       ('achats.produits-base', 'web', NOW(), NOW()),
       ('achats.gestion', 'web', NOW(), NOW());
   ```

2. **Attribuer les permissions aux rôles**
   ```sql
   -- Exemple: Donner toutes les permissions au rôle Admin (id=1)
   INSERT INTO role_has_permissions (permission_id, role_id) 
   SELECT id, 1 FROM permissions WHERE name LIKE 'achats.%';
   ```

3. **Créer les vues Blade** (optionnel - vous pouvez utiliser les routes API)
   - `resources/views/product-bases/index.blade.php`
   - `resources/views/product-bases/create.blade.php`
   - `resources/views/achats/index.blade.php`
   - `resources/views/achats/create.blade.php`

## 🚀 Démarrage rapide (3 étapes simples)

### Étape 1: Créer vos produits de base

Allez sur `/admin/product-bases/create` ou utilisez le code suivant:

```php
use App\Models\ProductBase;

// Créer "Poulet" comme produit de base
ProductBase::create([
    'nom' => 'Poulet',
    'stock' => 0,
    'stock_alerte' => 10,
    'unite' => 'unité',
]);
```

### Étape 2: Lier vos produits vendus aux produits de base

```php
use App\Models\Product;

// Trouver votre produit "Poulet braisé"
$pouletBraise = Product::where('title', 'Poulet braisé')->first();

// Le lier au produit de base avec un coefficient
$pouletBraise->update([
    'product_base_id' => 1, // ID du produit de base "Poulet"
    'coefficient' => 1,     // 1 poulet braisé = 1 poulet de base
]);

// Pour un demi-poulet
$demiPoulet = Product::where('title', 'Demi-poulet')->first();
$demiPoulet->update([
    'product_base_id' => 1,
    'coefficient' => 0.5,   // 1 demi-poulet = 0.5 poulet de base
]);
```

### Étape 3: Enregistrer votre premier achat

Allez sur `/admin/achats/create` ou:

```php
use App\Models\Achat;

Achat::create([
    'date_achat' => now(),
    'product_base_id' => 1, // Poulet
    'quantite' => 50,
    'prix_unitaire' => 2500,
    'fournisseur' => 'Mon Fournisseur',
    'user_id' => auth()->id(),
]);
// Le stock de poulet passe automatiquement à 50 !
```

## 🎯 Comment ça fonctionne

### Lors d'un achat
```
Achat de 50 poulets
→ Stock de poulet: 0 + 50 = 50 ✅
→ Prix d'achat moyen calculé automatiquement ✅
```

### Lors d'une vente
```
Vente de 3 poulets braisés (coefficient = 1)
→ Stock de poulet: 50 - (3 × 1) = 47 ✅

Vente de 4 demi-poulets (coefficient = 0.5)
→ Stock de poulet: 47 - (4 × 0.5) = 45 ✅

Le coefficient est sauvegardé dans la commande pour l'historique ✅
```

### Lors d'une annulation
```
Annulation de la commande
→ Stock réincrémenté automatiquement ✅
```

## 📊 Accès rapide

- **Liste des produits de base:** `/admin/product-bases`
- **Liste des achats:** `/admin/achats`
- **Rapport des achats:** `/admin/achats/rapport?date_debut=2026-01-01&date_fin=2026-12-31`
- **API produits de base:** `/admin/product-bases/api/list`

## 🔍 Vérification rapide

Après avoir tout configuré, vérifiez que ça fonctionne:

```php
// 1. Vérifier qu'un produit de base existe
$poulet = \App\Models\ProductBase::where('nom', 'Poulet')->first();
echo "Stock actuel: {$poulet->stock} {$poulet->unite}";

// 2. Vérifier qu'un produit vendu est lié
$pouletBraise = \App\Models\Product::where('title', 'Poulet braisé')->first();
echo "Lié au produit de base ID: {$pouletBraise->product_base_id}";
echo "Coefficient: {$pouletBraise->coefficient}";

// 3. Faire un test de vente (vérifier les logs après)
// Le stock devrait se décrémenter automatiquement
```

## 📝 Notes importantes

1. **Permissions requises:**
   - `achats.produits-base` pour gérer les produits de base
   - `achats.gestion` pour gérer les achats

2. **Logs:**
   - Tous les mouvements de stock sont loggés dans `storage/logs/laravel.log`
   - Cherchez "Stock décrémenté" ou "Stock insuffisant" pour suivre les opérations

3. **Coefficient:**
   - Le coefficient peut être un nombre décimal (0.5, 0.25, 1.5, etc.)
   - Il représente la quantité de produit de base consommée par unité vendue

4. **Stock négatif:**
   - Le système empêche le stock de devenir négatif
   - Un warning est loggé si le stock est insuffisant
   - La vente continue mais vous êtes alerté

## 🆘 Dépannage

### "Permission denied" lors de l'accès aux routes
→ Créez les permissions et attribuez-les à votre utilisateur

### Les stocks ne se décrément pas
→ Vérifiez que:
1. Le produit vendu a bien un `product_base_id`
2. Le produit vendu a bien un `coefficient`
3. Consultez les logs pour voir s'il y a des erreurs

### Le prix d'achat moyen ne se calcule pas
→ C'est normal, il se calcule après chaque achat automatiquement

## 📞 Support

Consultez les fichiers:
- `GESTION_ACHATS_STOCK.md` - Documentation complète
- `EXEMPLES_GESTION_STOCK.php` - Exemples de code

---

**Prêt à commencer?** Suivez les 3 étapes ci-dessus ! 🚀
