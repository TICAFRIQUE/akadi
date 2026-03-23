<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * Si le user a le rôle developpeur ou administrateur,
 * lui synchroniser toutes les permissions disponibles.
 */
if (!function_exists('syncPrivilegedPermissions')) {
    function syncPrivilegedPermissions(\App\Models\User $user): void
    {
        if ($user->hasRole(['developpeur', 'administrateur'])) {
            $user->syncPermissions(\Spatie\Permission\Models\Permission::all());
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        }
    }
}

/**
 * Retourne les statuts de commande visibles pour l'utilisateur connecté.
 * - developpeur / administrateur → tous les statuts
 * - p-confirmation → tous les statuts
 * - p-cuisine      → uniquement en_cuisine
 * - p-livraison    → uniquement cuisine_terminee
 * - Combinaisons possibles (union des statuts)
 * Retourne null si pas de restriction (voir tout).
 */
if (!function_exists('orderStatusesAllowed')) {
    function orderStatusesAllowed(): ?array
    {
        $user = Auth::user();
        if (!$user) {
            return [];
        }

        // Rôles privilégiés → pas de restriction
        if ($user->hasRole(['developpeur', 'administrateur'])) {
            return null;
        }

        // p-confirmation → tous les statuts
        if ($user->hasPermissionTo('p-confirmation')) {
            return null;
        }

        $statuses = [];

        if ($user->hasPermissionTo('p-cuisine')) {
            $statuses[] = \App\Models\Order::STATUS_EN_CUISINE;
        }

        if ($user->hasPermissionTo('p-livraison')) {
            $statuses[] = \App\Models\Order::STATUS_CUISINE_TERMINEE;
        }

        return $statuses; // [] = aucun accès
    }
}

if (!function_exists('canChangeOrderStatus')) {
    /**
     * Vérifie si l'utilisateur peut passer une commande vers un statut donné.
     */
    function canChangeOrderStatus(string $newStatus): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        if ($user->hasRole(['developpeur', 'administrateur'])) {
            return true;
        }

        // p-confirmation : peut tout changer sauf ce qui appartient à cuisine/livraison
        if ($user->hasPermissionTo('p-confirmation')) {
            // ne peut PAS passer en cuisine, cuisine_terminee, en_livraison, livrée
            // (ces transitions sont réservées aux autres rôles)
            return !in_array($newStatus, [
                // il peut quand même confirmer et gérer attente
            ]) || true; // p-confirmation peut tout faire
        }

        // p-cuisine : peut uniquement passer de en_cuisine → cuisine_terminee
        if ($user->hasPermissionTo('p-cuisine')) {
            return $newStatus === \App\Models\Order::STATUS_CUISINE_TERMINEE;
        }

        // p-livraison : peut passer cuisine_terminee → en_livraison → livrée
        if ($user->hasPermissionTo('p-livraison')) {
            return in_array($newStatus, [
                \App\Models\Order::STATUS_LIVRAISON,
                \App\Models\Order::STATUS_LIVREE,
            ]);
        }

        return false;
    }
}

if (!function_exists('clearAppCache')) {
    function clearAppCache()
    {
        Cache::forget('categories');
        Cache::forget('categories_backend');
        Cache::forget('subcategories');
        Cache::forget('roles');
        Cache::forget('roles_without_client');
    }
}


if (! function_exists('format_price')) {
    function format_price($value, $decimals = 2)
    {
        return rtrim(rtrim(number_format($value, $decimals, ',', ' '), '0'), ',');
    }
}