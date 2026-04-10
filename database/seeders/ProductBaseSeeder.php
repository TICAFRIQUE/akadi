<?php

namespace Database\Seeders;

use App\Models\ProductBase;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productBases = [
            [
                'code' => 'POULET001',
                'nom' => 'Poulet - 1.5kg',
                'description' => 'Poulet fermier frais, poids moyen 1.5kg',
                'stock' => 0,
                'stock_alerte' => 10,
                'unite' => 'unité',
                'prix_achat_moyen' => 450.00,
                'actif' => true,
            ],
            [
                'code' => 'PINTADE001',
                'nom' => 'Pintade - 2kg',
                'description' => 'Pintade de qualité supérieure, poids 2kg',
                'stock' => 0,
                'stock_alerte' => 5,
                'unite' => 'unité',
                'prix_achat_moyen' => 650.00,
                'actif' => true,
            ],
            [
                'code' => 'RIZ001',
                'nom' => 'Riz Blanc - 10kg',
                'description' => 'Riz blanc de qualité, sac 10kg',
                'stock' => 0,
                'stock_alerte' => 20,
                'unite' => 'kg',
                'prix_achat_moyen' => 1200.00,
                'actif' => true,
            ],
            [
                'code' => 'MAYO001',
                'nom' => 'Mayonnaise - 5kg',
                'description' => 'Mayonnaise fraîche',
                'stock' => 0,
                'stock_alerte' => 15,
                'unite' => 'unité',
                'prix_achat_moyen' => 50.00,
                'actif' => true,
            ],
            [
                'code' => 'BOISS001',
                'nom' => 'Boisson - 1L',
                'description' => 'Boisson rafraîchissante, bouteille 1L',
                'stock' => 0,
                'stock_alerte' => 8,
                'unite' => 'L',
                'prix_achat_moyen' => 500.00,
                'actif' => true,
            ],
        ];

        foreach ($productBases as $productBase) {
            ProductBase::create($productBase);
        }
    }
}
