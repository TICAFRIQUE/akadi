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
        Schema::table('menus_semaine', function (Blueprint $table) {
            $table->string('titre')->nullable()->change();
            $table->dropColumn(['prix_normal', 'prix_reduit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus_semaine', function (Blueprint $table) {
            $table->string('titre')->nullable(false)->change();
            $table->double('prix_normal')->default(0);
            $table->double('prix_reduit')->default(0);
        });
    }
};
