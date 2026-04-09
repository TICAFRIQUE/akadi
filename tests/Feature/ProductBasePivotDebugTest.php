<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductBase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductBasePivotDebugTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Debug test - vérifier que les productBases sont bien attachés et récupérés
     */
    public function test_product_bases_pivot_attachment_and_retrieval()
    {
        // Créer un utilisateur
        $user = User::create([
            'name' => 'Debug User',
            'email' => 'debug@test.com',
            'password' => bcrypt('password'),
        ]);

        // Créer des productBases
        $pb1 = ProductBase::create([
            'nom' => 'Poulet Debug',
            'unite' => 'pcs',
            'stock' => 100,
            'stock_alerte' => 10,
        ]);

        $pb2 = ProductBase::create([
            'nom' => 'Riz Debug',
            'unite' => 'kg',
            'stock' => 50,
            'stock_alerte' => 5,
        ]);

        // Créer un produit
        $product = Product::create([
            'id' => '9999999999',
            'code' => 'DBG-001',
            'title' => 'Debug Product',
            'slug' => 'debug-product',
            'price' => 1000,
            'user_id' => $user->id,
        ]);

        // Attacher les productBases
        $product->productBases()->attach([
            $pb1->id => ['coefficient' => 1.5],
            $pb2->id => ['coefficient' => 2.5],
        ]);

        // Debug: Récupérer et afficher les productBases
        $productBases = $product->productBases()->get();
        echo "\n\n=== DEBUG: Product Bases Retrieved ===\n";
        echo "Count: " . $productBases->count() . "\n";
        
        foreach ($productBases as $pb) {
            echo "Base ID: " . $pb->id . ", Nom: " . $pb->nom . ", Coefficient: " . $pb->pivot->coefficient . "\n";
        }

        // Assertions
        $this->assertEquals(2, $productBases->count());
        
        foreach ($productBases as $pb) {
            $this->assertIsNotNull($pb->pivot->coefficient);
            if ($pb->id === $pb1->id) {
                $this->assertEquals(1.5, $pb->pivot->coefficient);
            }
            if ($pb->id === $pb2->id) {
                $this->assertEquals(2.5, $pb->pivot->coefficient);
            }
        }

        echo "\n=== DEBUG: Test Passed ===\n\n";
    }
}
