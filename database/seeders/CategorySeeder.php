<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Volailles', 'type' => 'product', 'type_affichage' => 'grid', 'is_active' => true],
            ['name' => 'Viandes', 'type' => 'product', 'type_affichage' => 'grid', 'is_active' => true],
            ['name' => 'Accompagnements', 'type' => 'product', 'type_affichage' => 'grid', 'is_active' => true],
            ['name' => 'Boissons', 'type' => 'product', 'type_affichage' => 'list', 'is_active' => true],
            ['name' => 'Sauces & Condiments', 'type' => 'product', 'type_affichage' => 'grid', 'is_active' => true],
            ['name' => 'Salades', 'type' => 'product', 'type_affichage' => 'grid', 'is_active' => true],
            ['name' => 'Desserts', 'type' => 'product', 'type_affichage' => 'list', 'is_active' => true],
            ['name' => 'Sandwichs', 'type' => 'product', 'type_affichage' => 'grid', 'is_active' => true],
            ['name' => 'Plats du Jour', 'type' => 'product', 'type_affichage' => 'list', 'is_active' => true],
            ['name' => 'Snacks', 'type' => 'product', 'type_affichage' => 'grid', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
