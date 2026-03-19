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
        Schema::create('achat_lignes', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('achat_id')
                ->constrained('achats')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            $table->foreignId('product_base_id')
                ->constrained('product_bases')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            
            $table->decimal('quantite', 10, 2); // Quantité de ce produit
            $table->decimal('prix_unitaire', 10, 2); // Prix unitaire de ce produit
            $table->decimal('montant_ligne', 10, 2); // Total ligne = quantité × prix_unitaire
            
            $table->text('notes')->nullable(); // Notes spécifiques à cette ligne
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achat_lignes');
    }
};
