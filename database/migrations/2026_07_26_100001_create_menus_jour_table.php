<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus_jour', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique(); // une seule ligne "menu" par date
            $table->boolean('actif')->default(true);
            $table->longText('note')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus_jour');
    }
};
