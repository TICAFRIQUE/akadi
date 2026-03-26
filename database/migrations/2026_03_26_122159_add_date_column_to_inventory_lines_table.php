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
        Schema::table('inventory_lines', function (Blueprint $table) {
            //
            $table->dateTime('date_debut')->nullable();
            $table->dateTime('date_fin')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_lines', function (Blueprint $table) {
            //
            $table->dropColumn('date_debut');
            $table->dropColumn('date_fin');
        });
    }
};
