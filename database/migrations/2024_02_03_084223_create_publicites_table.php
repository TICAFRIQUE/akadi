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
        Schema::create('publicites', function (Blueprint $table) {
            $table->id();
            $table->longText('texte');
            $table->string('type')->nullable(); //banniere, publicité
            $table->string('url')->nullable();
            $table->string('button_name')->nullable();
            $table->integer('discount')->nullable();
            $table->string('statut')->nullable()->default('active'); // ,
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publicites');
    }
};
