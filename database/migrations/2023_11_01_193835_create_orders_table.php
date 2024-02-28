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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();
            $table->integer('quantity_product')->nullable();
            $table->string('subtotal')->nullable();
            $table->string('delivery_price')->nullable(); // prix de la livraison
            $table->string('delivery_name')->nullable(); // Lieu de livraison
            $table->longText('address')->nullable(); // adresse de livraison *** preciser le lieu de livraison

            $table->double('discount')->nullable();
            $table->string('total')->nullable();
            $table->dateTime('delivery_planned')->nullable(); //date de livraison prevue
            $table->dateTime('delivery_date')->nullable(); //date de livraison
            $table->string('status')->nullable(); // attente", en cour ,livré
            $table->string('payment method')->nullable(); 
            $table->string('available_product')->default('yes')->nullable(); // disponibilite du produit
            $table->date('date_order')->nullable();
            
            $table->foreignId('user_id')
            ->nullable()
            ->constrained('users')
            ->onUpdate('cascade')
            ->onDelete('set null');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
