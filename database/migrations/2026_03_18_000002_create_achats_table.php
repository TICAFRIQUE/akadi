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
        Schema::create('achats', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique(); // Numéro de bon d'achat
            $table->date('date_achat');
            $table->foreignId('product_base_id')
                ->constrained('product_bases')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            
            $table->decimal('quantite', 10, 2); // Quantité achetée
            $table->decimal('prix_unitaire', 10, 2); // Prix d'achat unitaire
            $table->decimal('montant_total', 10, 2); // Total = quantité * prix_unitaire
            
            $table->string('fournisseur')->nullable(); // Nom du fournisseur
            $table->text('notes')->nullable(); // Notes supplémentaires
            
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('set null');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achats');
    }
};
