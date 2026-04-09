<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subCategories = SubCategory::all();
        $user = User::first();

        $products = [
            [
                'title' => 'Poulet Rôti Complet',
                'price' => 2500.00,
                'description' => 'Poulet fermier rôti entier avec croûte dorée, servi avec riz et sauce',
                'disponibilite' => true,
                'stock' => 50,
                'stock_alerte' => 5,
            ],
            [
                'title' => 'Pintade Rôtie - 1/2',
                'price' => 4500.00,
                'description' => 'Demi pintade rôtie accompagnée de pommes de terre et légumes',
                'disponibilite' => true,
                'stock' => 30,
                'stock_alerte' => 3,
            ],
            [
                'title' => 'Poulet Fumé Premium',
                'price' => 3200.00,
                'description' => 'Poulet fermier fumé artisanalement avec sauce épicée',
                'disponibilite' => true,
                'stock' => 40,
                'stock_alerte' => 4,
            ],
            [
                'title' => 'Repas Poulet - Complet',
                'price' => 3500.00,
                'description' => 'Quart de poulet rôti + riz blanc + frites + sauce + boisson',
                'disponibilite' => true,
                'stock' => 60,
                'stock_alerte' => 6,
            ],
            [
                'title' => 'Repas Pintade - Famille',
                'price' => 8000.00,
                'description' => 'Pintade rôtie complète + riz + légumes + sauces variées',
                'disponibilite' => true,
                'stock' => 20,
                'stock_alerte' => 2,
            ],
            [
                'title' => 'Sandwich Poulet Grillé',
                'price' => 1500.00,
                'description' => 'Sandwich frais avec poulet grillé, salade et sauce maison',
                'disponibilite' => true,
                'stock' => 80,
                'stock_alerte' => 8,
            ],
            [
                'title' => 'Assiette Pintade Spéciale',
                'price' => 5500.00,
                'description' => 'Pintade rôtie avec garniture spéciale et sauces assortis',
                'disponibilite' => true,
                'stock' => 25,
                'stock_alerte' => 3,
            ],
            [
                'title' => 'Poulet Piquant Épicé',
                'price' => 2800.00,
                'description' => 'Poulet rôti accompagné de sauce piquante maison très relevée',
                'disponibilite' => true,
                'stock' => 45,
                'stock_alerte' => 5,
            ],
            [
                'title' => 'Plateau Mixte Volailles',
                'price' => 6500.00,
                'description' => 'Assortiment poulet & pintade avec riz, légumes et 3 sauces',
                'disponibilite' => true,
                'stock' => 30,
                'stock_alerte' => 3,
            ],
            [
                'title' => 'Menu Enfant Poulet',
                'price' => 1800.00,
                'description' => 'Aile de poulet + frites + compote + jus de fruit',
                'disponibilite' => true,
                'stock' => 70,
                'stock_alerte' => 7,
            ],
        ];

        foreach ($products as $index => $product) {
            $subCategory = $subCategories->random();
            $product['sub_category_id'] = $subCategory->id;
            $product['user_id'] = $user->id ?? 1;

            Product::create($product);
        }
    }
}
