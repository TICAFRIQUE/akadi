<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_produits', function (Blueprint $table) {
            $table->id();

            $table->foreignId('menu_jour_id')
                ->constrained('menus_jour')
                ->cascadeOnDelete();

            $table->string('nom');
            $table->longText('description')->nullable();
            $table->double('prix');
            $table->boolean('disponible')->default(true);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_produits');
    }
};
