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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->double('price')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('disponibilite')->nullable()->default(1); //1: disponible 0: rupture


            $table->foreignId('collection_id')
            ->nullable()
            ->constrained('collections')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('sub_category_id')
            ->nullable()
            ->constrained('sub_categories')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->foreignId('user_id')
            ->nullable()
            ->constrained('users')
            ->onUpdate('cascade')
            ->onDelete('cascade');

            //remise
            $table->double('montant_remise')->nullable();
            $table->integer('pourcentage_remise')->nullable();
            $table->dateTime('date_debut_remise')->nullable();
            $table->dateTime('date_fin_remise')->nullable();
            $table->string('status_remise')->nullable(); // en cour , terminer, bientot

            $table->softDeletes();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
