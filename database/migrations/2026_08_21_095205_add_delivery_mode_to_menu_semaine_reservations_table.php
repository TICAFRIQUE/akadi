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
        Schema::table('menu_semaine_reservations', function (Blueprint $table) {
            $table->string('mode_livraison')->nullable()->after('montant_total');
            $table->string('address_yango')->nullable()->after('mode_livraison');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu_semaine_reservations', function (Blueprint $table) {
            $table->dropColumn(['mode_livraison', 'address_yango']);
        });
    }
};
