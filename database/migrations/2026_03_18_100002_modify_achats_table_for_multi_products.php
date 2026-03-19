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
        Schema::table('achats', function (Blueprint $table) {
            // Rendre product_base_id nullable car maintenant les produits sont dans achat_lignes
            $table->dropForeign(['product_base_id']);
            $table->dropColumn(['product_base_id', 'quantite', 'prix_unitaire']);
            
            // montant_total reste pour le total général de l'achat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('achats', function (Blueprint $table) {
            $table->foreignId('product_base_id')
                ->after('date_achat')
                ->constrained('product_bases')
                ->onUpdate('cascade')
                ->onDelete('restrict');
            
            $table->decimal('quantite', 10, 2)->after('product_base_id');
            $table->decimal('prix_unitaire', 10, 2)->after('quantite');
        });
    }
};
