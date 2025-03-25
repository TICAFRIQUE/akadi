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
        Schema::create('coupons', function (Blueprint $table) {
            // $table->id();
            // $table->string('code'); // code de coupon
            // $table->double('pourcentage_coupon')->nullable();
            // $table->dateTime('date_debut_coupon')->nullable();
            // $table->dateTime('date_fin_coupon')->nullable();
            // $table->string('status_coupon')->nullable(); // en cour , terminer, bientot
            // $table->timestamps();

            $table->id();
            $table->string('nom')->unique()->nullable(); // nom du coupon ou bon de reduction
            $table->string('code')->unique()->nullable(); // code generer par le systeme
            $table->integer('quantite')->default(1)->nullable(); // quantite de coupon
            $table->integer('utilisation_max')->default(1)->nullable(); //nombre de fois que le coupon sera utilisé
            $table->enum('reduction_type', ['montant', 'pourcentage'])->nullable();
            $table->decimal('valeur_reduction', 10, 2)->nullable();
            $table->decimal('montant_min', 10, 2)->nullable(); // montant de la commande minimum
            $table->decimal('montant_max', 10, 2)->nullable(); // montant de la commande maximum
            $table->dateTime('expiration')->nullable(); // date d'expiration
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
