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
        Schema::create('order_product_base', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');        // le produit vendu
            $table->unsignedBigInteger('product_base_id');   // le product_base consommé
            $table->decimal('coefficient', 10, 4);           // snapshot au moment de la vente
            $table->decimal('quantity_consumed', 10, 4);     // quantity_vendue × coefficient
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_base_id')->references('id')->on('product_bases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product_base');
    }
};
