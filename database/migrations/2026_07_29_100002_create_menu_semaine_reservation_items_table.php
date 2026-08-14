<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Détail persistant (jour / plat / quantité) d'une réservation, créé AVANT le
     * paiement Wave. Nécessaire car le webhook Wave n'a pas accès à la session du
     * client : c'est cette table, et non le panier session, qui sert de source de
     * vérité pour générer les commandes du jour une fois le paiement confirmé.
     */
    public function up(): void
    {
        Schema::create('menu_semaine_reservation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_semaine_reservation_id');
            $table->foreign('menu_semaine_reservation_id', 'msri_reservation_id_fk')
                ->references('id')->on('menu_semaine_reservations')->cascadeOnDelete();
            $table->date('date');
            $table->foreignId('menu_produit_id')->constrained('menu_produits')->cascadeOnDelete();
            $table->unsignedInteger('quantity');
            $table->double('prix_unitaire');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_semaine_reservation_items');
    }
};
