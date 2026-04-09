<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_product_base', function (Blueprint $table) {
            $table->id();
            
            // Clés étrangères
            $table->foreignId('product_id')
                ->constrained('products')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            $table->foreignId('product_base_id')
                ->constrained('product_bases')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            // Coefficient de consommation
            $table->decimal('coefficient', 10, 2)
                ->comment('Quantité de produit de base consommée par unité vendue');
            
            // Timestamps
            $table->timestamps();
            
            // Unique constraint pour éviter les doublons
            $table->unique(['product_id', 'product_base_id']);
            
            // Index pour les recherches
            $table->index('product_id');
            $table->index('product_base_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_product_base');
    }
};
