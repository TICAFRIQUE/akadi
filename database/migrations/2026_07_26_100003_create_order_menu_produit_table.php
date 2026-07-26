<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Pivot commande <-> plat du menu du jour, miroir de order_product
        // (sans les colonnes liées au stock catalogue : coefficient/options/available)
        Schema::create('order_menu_produit', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            $table->foreignId('menu_produit_id')
                ->constrained('menu_produits')
                ->cascadeOnDelete();

            $table->integer('quantity');
            $table->double('unit_price');
            $table->double('discount')->default(0);
            $table->enum('type_discount', ['percent', 'fixed'])->default('percent');
            $table->double('prix_apres_remise')->nullable();
            $table->double('total');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_menu_produit');
    }
};
