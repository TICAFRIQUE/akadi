<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductBase;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MultipleProductBasesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test de création d'un produit avec multiples productBases
     */
    public function test_create_product_with_multiple_product_bases()
    {
        // Créer un utilisateur
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        // Créer tests productBases
        $pb1 = ProductBase::create([
            'nom' => 'Poulet fermier',
            'unite' => 'pcs',
            'stock' => 100,
            'stock_alerte' => 10,
        ]);

        $pb2 = ProductBase::create([
            'nom' => 'Fécula',
            'unite' => 'kg',
            'stock' => 50,
            'stock_alerte' => 5,
        ]);

        // Créer un produit
        $product = Product::create([
            'id' => '123456789',
            'code' => 'CODE-001',
            'title' => 'Poulet braisé avec fécula',
            'slug' => 'poulet-braise-avec-fecula',
            'price' => 5000,
            'user_id' => $user->id,
        ]);

        // Attacher les productBases via la pivot
        $product->productBases()->attach([
            $pb1->id => ['coefficient' => 1],
            $pb2->id => ['coefficient' => 0.5],
        ]);

        // Vérifications
        $this->assertEquals(2, $product->productBases()->count());
        $this->assertTrue($product->productBases()->where('product_base_id', $pb1->id)->exists());
        $this->assertTrue($product->productBases()->where('product_base_id', $pb2->id)->exists());

        // Vérifier les coefficients
        $pb1_relation = $product->productBases()->where('product_base_id', $pb1->id)->first();
        $pb2_relation = $product->productBases()->where('product_base_id', $pb2->id)->first();

        $this->assertEquals(1, $pb1_relation->pivot->coefficient);
        $this->assertEquals(0.5, $pb2_relation->pivot->coefficient);
    }

    /**
     * Test de décrémentation de stock pour multiples productBases
     */
    public function test_stock_decrement_for_multiple_product_bases()
    {
        // Créer un utilisateur
        $user = User::create([
            'name' => 'Test User 2',
            'email' => 'test2@test.com',
            'password' => bcrypt('password'),
        ]);

        // Créer des productBases
        $pb1 = ProductBase::create([
            'nom' => 'Poulet',
            'unite' => 'pcs',
            'stock' => 100,
            'stock_alerte' => 10,
        ]);

        $pb2 = ProductBase::create([
            'nom' => 'Riz',
            'unite' => 'kg',
            'stock' => 50,
            'stock_alerte' => 5,
        ]);

        // Créer un produit
        $product = Product::create([
            'id' => '123456790',
            'code' => 'CODE-002',
            'title' => 'Assiette Poulet Riz',
            'slug' => 'assiette-poulet-riz',
            'price' => 3000,
            'user_id' => $user->id,
        ]);

        // Attacher les productBases
        $product->productBases()->attach([
            $pb1->id => ['coefficient' => 1],    // 1 poulet
            $pb2->id => ['coefficient' => 0.3],  // 0.3 kg de riz
        ]);

        // Vérifier que les productBases sont bien attachés
        $this->assertEquals(2, $product->productBases()->count());

        // Créer una commande
        $order = Order::create([
            'numero' => 'ORD-001',
            'user_id' => $user->id,
            'status' => 'attente',
            'montant_total' => 6000,
        ]);

        // Attacher le produit à la commande
        $order->products()->attach($product->id, [
            'quantity' => 2,
            'unit_price' => 3000,
            'total' => 6000,
        ]);

        // Vérifier que le produit est attaché à la commande
        $this->assertEquals(1, $order->products()->count());

        // Récupérer les stocks AVANT via fresh
        $pb1_before = ProductBase::find($pb1->id)->stock;
        $pb2_before = ProductBase::find($pb2->id)->stock;

        // Forcer l'Observer en réenregistrant l'ordre
        $order->touch(); // déclenche l'événement updated
        $order->save();

        // Attendre un peu (si asynchrone)
        sleep(1);

        // Récupérer les stocks APRÈS via fresh
        $pb1_after = ProductBase::find($pb1->id)->stock;
        $pb2_after = ProductBase::find($pb2->id)->stock;

        // Affichage de debug
        echo "\n\n=== Stock Decrement Debug ===\n";
        echo "PB1 Before: $pb1_before, After: $pb1_after (expected: " . ($pb1_before - 2) . ")\n";
        echo "PB2 Before: $pb2_before, After: $pb2_after (expected: " . ($pb2_before - 0.6) . ")\n\n";

        // Vérifications
        $this->assertEquals($pb1_before - 2, $pb1_after, "Poulet stock should be decremented by 2");
        $this->assertEquals($pb2_before - 0.6, $pb2_after, "Riz stock should be decremented by 0.6");
    }
}
