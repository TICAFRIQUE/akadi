<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            ['nom' => 'Espèces',        'code' => 'cash',   'icone' => 'fas fa-money-bill-wave', 'position' => 1, 'actif' => true],
            ['nom' => 'MTN MoMo',       'code' => 'momo',   'icone' => 'fas fa-mobile-alt',      'position' => 2, 'actif' => true],
            ['nom' => 'Orange Money',   'code' => 'orange', 'icone' => 'fas fa-sim-card',        'position' => 3, 'actif' => true],
            ['nom' => 'Wave',           'code' => 'wave',   'icone' => 'fas fa-water',           'position' => 4, 'actif' => true],
            ['nom' => 'Carte bancaire', 'code' => 'card',   'icone' => 'fas fa-credit-card',     'position' => 5, 'actif' => true],
        ];

        foreach ($methods as $m) {
            PaymentMethod::firstOrCreate(['code' => $m['code']], $m);
        }
    }
}
