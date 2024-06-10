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
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity')->nullable(); // quantite du produit
            $table->double('unit_price')->nullable(); //prix  unitaire
            $table->double('total')->nullable(); // total quantite * prix unitaire
            $table->string('options')->nullable(); // options size ...
            $table->string('available')->nullable(); // disponibilité de produit ##yes or no ...


            $table->foreignId('order_id')
            ->nullable()
            ->constrained('orders')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('product_id')
            ->nullable()
            ->constrained('products')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};
