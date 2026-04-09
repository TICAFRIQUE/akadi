<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer tous les produits
        $products = Product::all();

        foreach ($products as $product) {
            // Récupérer la sous-catégorie du produit
            $subCategory = $product->subcategorie;

            if ($subCategory) {
                // Récupérer la catégorie parente
                $category = $subCategory->category;

                if ($category) {
                    // Attacher le produit à la catégorie via la table pivot
                    $product->categories()->attach($category->id);
                }
            }
        }
    }
}
