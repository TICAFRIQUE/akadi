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
        Schema::create('sortie_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->date('date_sortie');
            $table->foreignId('product_base_id')->constrained('product_bases')->cascadeOnDelete();
            $table->decimal('quantite', 10, 2);
            $table->string('motif')->nullable(); // Perte, Casse, Don, Autre
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sortie_stocks');
    }
};
