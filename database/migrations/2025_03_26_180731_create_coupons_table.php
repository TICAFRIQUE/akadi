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
            $table->id();
            $table->string('nom')->nullable(); // nom du coupon ou bon de reduction
            $table->string('code')->unique()->nullable(); // code generer par le systeme
            $table->integer('quantite')->default(1)->nullable(); // quantite de coupon
            $table->integer('utilisation_max')->default(1)->nullable(); //nombre de fois que le coupon sera utilisé
            $table->enum('type_remise', ['montant', 'pourcentage'])->nullable();
            $table->double('valeur_remise')->nullable();
            $table->double('montant_min')->nullable(); // montant de la commande minimum
            $table->double('montant_max')->nullable(); // montant de la commande maximum
            $table->dateTime('expiration')->nullable(); // date d'expiration
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();
            $table->enum('status', ['en_cours', 'terminer', 'bientot'])->nullable();
            $table->enum('type_coupon', ['unique', 'groupe'])->nullable();
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
