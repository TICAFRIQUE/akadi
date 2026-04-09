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
                'nom' => 'Poulet Fermier - 1.5kg',
                'description' => 'Poulet fermier frais, poids moyen 1.5kg',
                'stock' => 100,
                'stock_alerte' => 10,
                'unite' => 'kg',
                'prix_achat_moyen' => 450.00,
                'actif' => true,
            ],
            [
                'code' => 'PINTADE001',
                'nom' => 'Pintade - 2kg',
                'description' => 'Pintade de qualité supérieure, poids 2kg',
                'stock' => 50,
                'stock_alerte' => 5,
                'unite' => 'kg',
                'prix_achat_moyen' => 650.00,
                'actif' => true,
            ],
            [
                'code' => 'RIZ001',
                'nom' => 'Riz Blanc - 10kg',
                'description' => 'Riz blanc de qualité, sac 10kg',
                'stock' => 200,
                'stock_alerte' => 20,
                'unite' => 'kg',
                'prix_achat_moyen' => 1200.00,
                'actif' => true,
            ],
            [
                'code' => 'PATATE001',
                'nom' => 'Pommes de terre - 25kg',
                'description' => 'Pommes de terre fraîches, sac 25kg',
                'stock' => 150,
                'stock_alerte' => 15,
                'unite' => 'kg',
                'prix_achat_moyen' => 2000.00,
                'actif' => true,
            ],
            [
                'code' => 'SAUCE001',
                'nom' => 'Sauce Piquante - 5L',
                'description' => 'Sauce piquante maison, bidon 5L',
                'stock' => 80,
                'stock_alerte' => 8,
                'unite' => 'L',
                'prix_achat_moyen' => 3500.00,
                'actif' => true,
            ],
        ];

        foreach ($productBases as $productBase) {
            ProductBase::create($productBase);
        }
    }
}
