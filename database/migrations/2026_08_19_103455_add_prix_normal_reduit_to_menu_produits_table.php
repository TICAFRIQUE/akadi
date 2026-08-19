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
        Schema::table('menu_produits', function (Blueprint $table) {
            $table->double('prix_normal')->nullable()->after('prix');
            $table->double('prix_reduit')->nullable()->after('prix_normal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_produits', function (Blueprint $table) {
            $table->dropColumn(['prix_normal', 'prix_reduit']);
        });
    }
};
