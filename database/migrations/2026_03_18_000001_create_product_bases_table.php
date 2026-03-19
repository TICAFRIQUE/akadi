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
        Schema::create('product_bases', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->decimal('stock', 10, 2)->default(0); // Quantité en stock
            $table->decimal('stock_alerte', 10, 2)->nullable(); // Seuil d'alerte
            $table->string('unite')->default('unité'); // unité, kg, litre, etc.
            $table->decimal('prix_achat_moyen', 10, 2)->nullable(); // Prix d'achat moyen
            $table->boolean('actif')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_bases');
    }
};
