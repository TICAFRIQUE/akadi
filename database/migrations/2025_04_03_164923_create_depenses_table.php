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
        Schema::create('depenses', function (Blueprint $table) {
            $table->id();
            $table->string('libelle')->nullable();
            $table->double('montant')->nullable();
            $table->longText('description')->nullable();
            $table->foreignId('categorie_depense_id')
                ->nullable()
                ->constrained('categorie_depenses')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('libelle_depense_id') // sous categorie depenses
                ->nullable()
                ->constrained('libelle_depenses')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');


            $table->date('date_depense')->nullable();

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depenses');
    }
};
