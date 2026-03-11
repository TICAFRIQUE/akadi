<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Permissions groupées par module (= menus de la sidebar)
     */
    public static array $permissions = [

        // ── Tableau de bord ──────────────────────────────────────────────────────
        'Tableau de bord' => [
            'dashboard.voir',
        ],

        // ── Catalogue ─────────────────────────────────────────────────────────────
        'Catalogue' => [
            'catalogue.voir',
            'catalogue.categories',
            'catalogue.sous-categories',
            'catalogue.produits',
        ],

        // ── Ventes ────────────────────────────────────────────────────────────────
        'Ventes' => [
            'ventes.voir',
            'ventes.pos',          // créer / modifier une commande POS
            'ventes.commandes',    // voir la liste des commandes
            'ventes.clients',
            'ventes.coupons',
            'ventes.livraisons',
            'p-confirmation',      // confirmer / créer commandes, voir tous les statuts
            'p-cuisine',           // voir uniquement les commandes en_cuisine
            'p-livraison',         // voir uniquement les commandes cuisine_terminee
        ],

        // ── Caisse ────────────────────────────────────────────────────────────────
        'Caisse' => [
            'caisse.voir',
            'caisse.caisses',
            'caisse.moyens-paiement',
        ],

        // ── Contenu ───────────────────────────────────────────────────────────────
        'Contenu' => [
            'contenu.voir',
            'contenu.medias',
            'contenu.temoignages',
        ],

        // ── Dépenses ──────────────────────────────────────────────────────────────
        'Dépenses' => [
            'depenses.voir',
            'depenses.categories',
            'depenses.libelles',
            'depenses.saisir',
        ],

        // ── Rapports ──────────────────────────────────────────────────────────────
        'Rapports' => [
            'rapports.voir',
            'rapports.exploitation',
            'rapports.vente',
        ],

        // ── Administration ────────────────────────────────────────────────────────
        'Administration' => [
            'administration.voir',
            'administration.users',
            'administration.roles',
            'administration.permissions',
        ],
    ];

    public function run(): void
    {
        // Vider le cache des permissions Spatie
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Créer ou retrouver chaque permission
        $allPermissions = [];
        foreach (self::$permissions as $module => $perms) {
            foreach ($perms as $perm) {
                $allPermissions[] = Permission::firstOrCreate(
                    ['name' => $perm, 'guard_name' => 'web']
                );
            }
        }


    }
}
