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
        Schema::table('products', function (Blueprint $table) {
            // Ajouter la colonne product_base_id
            $table->foreignId('product_base_id')
                ->nullable()
                ->after('user_id')
                ->constrained('product_bases')
                ->onUpdate('cascade')
                ->onDelete('set null');
            
            // Ajouter le coefficient de consommation
            $table->decimal('coefficient', 10, 2)
                ->nullable()
                ->after('product_base_id')
                ->comment('Quantité de produit de base consommée par unité vendue');
            
            // Ajouter stock et stock_alerte si absents
            if (!Schema::hasColumn('products', 'stock')) {
                $table->decimal('stock', 10, 2)->nullable()->after('status_remise');
            }
            
            if (!Schema::hasColumn('products', 'stock_alerte')) {
                $table->decimal('stock_alerte', 10, 2)->nullable()->after('stock');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['product_base_id']);
            $table->dropColumn(['product_base_id', 'coefficient']);
        });
    }
};
