<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        $subCategories = [
            // Volailles (2)
            ['name' => 'Poulet', 'type_affichage' => 'grid'],
            ['name' => 'Pintade', 'type_affichage' => 'grid'],
            
            // Viandes (2)
            ['name' => 'Bœuf', 'type_affichage' => 'grid'],
            ['name' => 'Agneau', 'type_affichage' => 'grid'],
            
            // Accompagnements (2)
            ['name' => 'Riz & Pâtes', 'type_affichage' => 'grid'],
            ['name' => 'Légumes & Frites', 'type_affichage' => 'grid'],
            
            // Boissons (2)
            ['name' => 'Sodas & Jus', 'type_affichage' => 'list'],
            ['name' => 'Boissons Chaudes', 'type_affichage' => 'list'],
            
            // Sauces & Condiments (2)
            ['name' => 'Sauces Chaudes', 'type_affichage' => 'grid'],
            ['name' => 'Sauces Froides', 'type_affichage' => 'grid'],
            
            // Salades (2)
            ['name' => 'Salades Vertes', 'type_affichage' => 'grid'],
            ['name' => 'Salades Composées', 'type_affichage' => 'grid'],
            
            // Desserts (2)
            ['name' => 'Pâtisseries', 'type_affichage' => 'list'],
            ['name' => 'Fruits & Compotes', 'type_affichage' => 'list'],
            
            // Sandwichs (2)
            ['name' => 'Sandwichs Volaille', 'type_affichage' => 'grid'],
            ['name' => 'Sandwichs Viande', 'type_affichage' => 'grid'],
            
            // Plats du Jour (2)
            ['name' => 'Spécialités Maison', 'type_affichage' => 'list'],
            ['name' => 'Grillades', 'type_affichage' => 'list'],
            
            // Snacks (2)
            ['name' => 'Beignets & Friture', 'type_affichage' => 'grid'],
            ['name' => 'Encas Divers', 'type_affichage' => 'grid'],
        ];

        $categoryIndex = 0;
        $subCategoryCount = 0;

        foreach ($subCategories as $subCategory) {
            if ($categoryIndex < $categories->count()) {
                $category = $categories[$categoryIndex];
                SubCategory::create([
                    'name' => $subCategory['name'],
                    'category_id' => $category->id,
                    'type_affichage' => $subCategory['type_affichage'],
                ]);

                $subCategoryCount++;

                // 2 sous-catégories par catégorie
                if ($subCategoryCount % 2 == 0) {
                    $categoryIndex++;
                }
            }
        }
    }
}
