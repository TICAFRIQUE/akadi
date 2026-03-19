# Guide d'utilisation du système de gestion des achats

## Vues créées

### 1. Produits de Base
- ✅ **Liste** : `/admin/product-bases`
  - Affiche tous les produits de base avec leurs stocks
  - Indicateur visuel pour les stocks faibles
  - Actions : Modifier, Supprimer
  
- ✅ **Créer** : `/admin/product-bases/create`
  - Formulaire pour créer un nouveau produit de base
  - Champs : Nom, Stock initial, Stock d'alerte, Unité, Prix moyen d'achat, Description
  
- ✅ **Modifier** : `/admin/product-bases/{id}/edit`
  - Formulaire pour modifier un produit de base
  - Affiche les informations de création et le nombre de produits liés

### 2. Achats Multi-Produits
- ✅ **Liste** : `/admin/achats`
  - Liste de tous les achats avec leurs détails
  - Affiche : N° achat, Date, Fournisseur, Nb produits, Montant total
  - Actions : Voir détails, Modifier, Supprimer
  
- ✅ **Créer** : `/admin/achats/create`
  - Formulaire pour enregistrer un achat avec plusieurs produits
  - Interface dynamique pour ajouter/supprimer des lignes
  - Calcul automatique des montants
  - Champs : Date, Fournisseur, N° Facture, Notes, Liste des produits
  
- ✅ **Voir détails** : `/admin/achats/{id}`
  - Affiche tous les détails de l'achat
  - Liste des produits achetés avec quantités et montants
  - Informations sur l'impact du stock
  
- ✅ **Modifier** : `/admin/achats/{id}/edit`
  - Formulaire pour modifier un achat existant
  - Permet d'ajouter/modifier/supprimer des lignes de produits
  - Mise à jour automatique des stocks
  
- ✅ **Rapport** : `/admin/achats/rapport`
  - Statistiques globales des achats
  - Filtrage par période
  - Regroupement par produit de base
  - Export possible (Excel, PDF, etc.)

### 3. Liaison Produits <-> Produits de Base
- ✅ **Créer produit** : `/admin/products/create`
  - Nouveaux champs ajoutés :
    - **Produit de base** : Sélection du produit de base (optionnel)
    - **Coefficient** : Quantité utilisée (ex: 1, 0.5, etc.)
  - Affichage dynamique de l'unité selon le produit sélectionné
  - Exemples d'utilisation affichés
  
- ✅ **Modifier produit** : `/admin/products/{id}/edit`
  - Mêmes champs que lors de la création
  - Affichage des valeurs actuelles
  - Mise à jour possible du produit de base et coefficient

## Menu Sidebar

Une nouvelle section **"Achats"** a été ajoutée dans la sidebar avec :
- 📦 **Produits de base** : Gestion des produits de base
- 🛒 **Gestion des achats** : Liste et création d'achats
- 📊 **Rapport des achats** : Statistiques et analyses

La section se situe entre "Dépenses" et "Rapports".

## Permissions requises

Les permissions suivantes doivent être créées dans la base de données :

```sql
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('achats.voir', 'web', NOW(), NOW()),
('achats.produits-base', 'web', NOW(), NOW()),
('achats.gestion', 'web', NOW(), NOW());
```

Puis assignez-les aux rôles appropriés via la table `role_has_permissions`.

## Fonctionnalités JavaScript

### Formulaire d'achat (create/edit)
- ✅ Ajout dynamique de lignes de produits
- ✅ Suppression de lignes (minimum 1 ligne)
- ✅ Calcul automatique du montant par ligne
- ✅ Calcul automatique du montant total
- ✅ Validation avant soumission

### Liaison produit-base (products create/edit)
- ✅ Changement dynamique de l'unité affichée
- ✅ Réinitialisation du coefficient si aucun produit sélectionné

## Flux de travail

### 1. Configuration initiale
1. Créer les permissions SQL
2. Assigner les permissions aux rôles
3. Créer les produits de base (Poulet, Huile, Oignons, etc.)

### 2. Liaison des produits
1. Aller dans "Catalogue" > "Produits"
2. Modifier un produit existant ou en créer un nouveau
3. Sélectionner le **Produit de base** correspondant
4. Définir le **Coefficient** (quantité utilisée)
   - Ex: 1 Poulet braisé = 1 poulet
   - Ex: 1 Demi-poulet = 0.5 poulet
5. Enregistrer

### 3. Enregistrement d'achats
1. Aller dans "Achats" > "Gestion des achats"
2. Cliquer sur "Nouvel achat"
3. Remplir les informations générales (date, fournisseur, facture)
4. Ajouter les produits achetés :
   - Sélectionner le produit
   - Entrer la quantité
   - Entrer le prix unitaire
   - Le montant se calcule automatiquement
5. Ajouter d'autres produits si nécessaire
6. Vérifier le montant total
7. Enregistrer → **Le stock s'incrémente automatiquement**

### 4. Vérification des stocks
1. Aller dans "Achats" > "Produits de base"
2. Vérifier les stocks actuels
3. Les produits avec stock faible sont surlignés en jaune

### 5. Ventes (automatique)
- Lors d'une vente via POS ou commande en ligne
- Le système décrémente automatiquement le stock du produit de base
- Calcul basé sur le coefficient défini
- Ex: Vente de 1 Poulet braisé (coef=1) → Stock poulet -1

### 6. Rapports
1. Aller dans "Achats" > "Rapport des achats"
2. Sélectionner une période
3. Consulter les statistiques :
   - Total achats
   - Montant total dépensé
   - Montant moyen par achat
   - Détails par produit de base

## Notes importantes

### Gestion automatique des stocks
- ✅ **Achat créé** : Stock incrémenté automatiquement (via AchatLigne::created)
- ✅ **Achat modifié** : Stock ajusté automatiquement (via AchatLigne::updated)
- ✅ **Achat supprimé** : Stock décrémenté automatiquement (via AchatLigne::deleted)
- ✅ **Vente réalisée** : Stock décrémenté via StockService
- ✅ **Commande annulée** : Stock ré-incrémenté via StockService

### Prix moyen d'achat
- Calculé automatiquement à chaque achat
- Formule : `(ancien_stock × ancien_prix + nouvelle_quantité × nouveau_prix) / stock_total`

### Alertes de stock
- Produits avec stock ≤ stock d'alerte affichés en jaune
- Badge "Stock faible" visible dans la liste

## Prochaines étapes suggérées

1. **Historique des mouvements de stock**
   - Créer une table `stock_movements` pour tracer tous les mouvements
   - Afficher l'historique dans les détails du produit de base

2. **Notifications**
   - Notifier les administrateurs quand un stock est faible
   - Email/SMS lors d'alertes de stock

3. **Inventaire**
   - Fonction d'inventaire physique
   - Ajustement de stock avec justification

4. **Statistiques avancées**
   - Coût moyen par plat vendu
   - Marge bénéficiaire par produit
   - Prévisions de commandes

5. **Gestion fournisseurs**
   - Créer un module fournisseurs
   - Historique des achats par fournisseur
   - Comparaison de prix

## Support

Pour toute question ou problème :
- Vérifier les logs : `storage/logs/laravel.log`
- Vérifier les erreurs PHP dans le navigateur
- Consulter la documentation complète dans :
  - `GESTION_ACHATS_STOCK.md`
  - `ACHATS_MULTI_PRODUITS.md`
  - `EXEMPLES_ACHATS_MULTI_PRODUITS.php`
