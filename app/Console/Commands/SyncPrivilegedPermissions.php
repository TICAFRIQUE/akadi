<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;

class SyncPrivilegedPermissions extends Command
{
    protected $signature   = 'permissions:sync-privileged';
    protected $description = 'Assigner toutes les permissions aux users developpeur et administrateur';

    public function handle(): void
    {
        $allPerms = Permission::all();
        $users    = User::role(['developpeur', 'administrateur'])->get();

        if ($users->isEmpty()) {
            $this->warn('Aucun user developpeur/administrateur trouvé.');
            return;
        }

        foreach ($users as $user) {
            $user->syncPermissions($allPerms);
            $this->info(
                '✔ ' . $user->name .
                ' (' . $user->roles->pluck('name')->implode(', ') . ')' .
                ' → ' . $allPerms->count() . ' permissions assignées'
            );
        }

        $this->info('Total : ' . $users->count() . ' user(s) mis à jour.');
    }
}
