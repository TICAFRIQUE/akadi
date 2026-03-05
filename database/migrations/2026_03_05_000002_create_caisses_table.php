<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caisses', function (Blueprint $table) {
            $table->id();
            $table->string('nom');                           // Ex: Caisse 1, Caisse principale
            $table->string('description')->nullable();
            $table->enum('statut', ['disponible', 'occupee', 'fermee'])->default('disponible');
            $table->boolean('actif')->default(true);

            // L'utilisateur qui a pris la caisse (session active)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('set null');

            $table->dateTime('prise_en_charge_at')->nullable(); // Quand la caisse a été prise
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caisses');
    }
};
