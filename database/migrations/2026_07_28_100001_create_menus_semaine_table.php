<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus_semaine', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->boolean('actif')->default(true);
            $table->double('prix_normal');
            $table->double('prix_reduit');
            $table->unsignedInteger('seuil_jours')->default(3);
            $table->string('lien_token', 32)->unique();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus_semaine');
    }
};
