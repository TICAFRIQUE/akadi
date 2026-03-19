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
        Schema::table('order_product', function (Blueprint $table) {
            // Ajouter le coefficient pour tracer la valeur au moment de la vente
            $table->decimal('coefficient', 10, 2)
                ->nullable()
                ->after('quantity')
                ->comment('Coefficient de consommation du produit de base au moment de la vente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('coefficient');
        });
    }
};
