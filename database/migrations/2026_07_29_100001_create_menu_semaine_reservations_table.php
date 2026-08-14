<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_semaine_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_semaine_id')->constrained('menus_semaine')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('client_name');
            $table->string('client_phone');
            $table->unsignedInteger('nombre_jours');
            $table->double('prix_unitaire_applique');
            $table->double('montant_total');
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->string('wave_session_id')->nullable()->index();
            $table->string('payment_status')->default('pending'); // pending, completed, failed, cancelled
            $table->string('statut')->default('en_attente_paiement'); // en_attente_paiement, payee, annulee
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_semaine_reservations');
    }
};
