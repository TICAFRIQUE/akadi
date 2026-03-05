<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('nom');                       // Ex: Espèces, Mobile Money, Carte, etc.
            $table->string('code')->unique()->nullable(); // Ex: cash, momo, card
            $table->boolean('actif')->default(true);
            $table->string('icone')->nullable();          // Classe CSS ou nom d'icône
            $table->integer('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
