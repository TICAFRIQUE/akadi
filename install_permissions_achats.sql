-- ==================================================
-- Script SQL pour installer les permissions
-- Système de gestion des achats et stocks
-- ==================================================

-- 1. Créer les permissions
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('achats.voir', 'web', NOW(), NOW()),
('achats.produits-base', 'web', NOW(), NOW()),
('achats.gestion', 'web', NOW(), NOW());

-- 2. Assigner les permissions au rôle 'admin' (ID à adapter selon votre base)
-- Remplacez 1 par l'ID du rôle admin dans votre base de données

-- Récupérer l'ID du rôle admin
SET @admin_role_id = (SELECT id FROM roles WHERE name = 'admin' LIMIT 1);

-- Assigner les permissions
INSERT INTO role_has_permissions (permission_id, role_id)
SELECT p.id, @admin_role_id
FROM permissions p
WHERE p.name IN ('achats.voir', 'achats.produits-base', 'achats.gestion')
AND NOT EXISTS (
    SELECT 1 FROM role_has_permissions rhp 
    WHERE rhp.permission_id = p.id AND rhp.role_id = @admin_role_id
);

-- 3. Vérification
SELECT 
    r.name AS role_name,
    p.name AS permission_name
FROM roles r
JOIN role_has_permissions rhp ON r.id = rhp.role_id
JOIN permissions p ON rhp.permission_id = p.id
WHERE p.name LIKE 'achats.%'
ORDER BY r.name, p.name;

-- ==================================================
-- Si vous avez d'autres rôles (ex: gestionnaire)
-- ==================================================

-- Pour le rôle 'gestionnaire' (à adapter)
SET @gestionnaire_role_id = (SELECT id FROM roles WHERE name = 'gestionnaire' LIMIT 1);

INSERT INTO role_has_permissions (permission_id, role_id)
SELECT p.id, @gestionnaire_role_id
FROM permissions p
WHERE p.name IN ('achats.voir', 'achats.produits-base', 'achats.gestion')
AND @gestionnaire_role_id IS NOT NULL
AND NOT EXISTS (
    SELECT 1 FROM role_has_permissions rhp 
    WHERE rhp.permission_id = p.id AND rhp.role_id = @gestionnaire_role_id
);

-- ==================================================
-- Notes d'utilisation
-- ==================================================
/*
PERMISSIONS CRÉÉES :
- achats.voir : Permet de voir la section achats dans le menu
- achats.produits-base : Permet de gérer les produits de base
- achats.gestion : Permet de gérer les achats et voir les rapports

UTILISATION DANS LES VUES :
@can('achats.voir')
    // Menu principal achats visible
@endcan

@can('achats.produits-base')
    // Accès aux produits de base
@endcan

@can('achats.gestion')
    // Accès à la gestion des achats et rapports
@endcan

ROUTES PROTÉGÉES :
Route::middleware(['auth', 'permission:achats.produits-base'])->group(function () {
    Route::resource('product-base', ProductBaseController::class);
});

Route::middleware(['auth', 'permission:achats.gestion'])->group(function () {
    Route::resource('achat', AchatController::class);
    Route::get('achat/rapport', [AchatController::class, 'rapport'])->name('achat.rapport');
});
*/
