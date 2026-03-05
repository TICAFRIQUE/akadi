<?php

namespace Database\Seeders;

use App\Models\Caisse;
use Illuminate\Database\Seeder;

class CaisseSeeder extends Seeder
{
    public function run(): void
    {
        $caisses = [
            ['nom' => 'Caisse principale', 'description' => 'Caisse principale du magasin', 'actif' => true],
            ['nom' => 'Caisse 2',          'description' => null,                            'actif' => true],
        ];

        foreach ($caisses as $c) {
            Caisse::firstOrCreate(['nom' => $c['nom']], $c);
        }
    }
}
