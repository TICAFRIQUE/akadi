<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Sous-total des lignes "menu du jour" (après remise ligne, avant remise globale/livraison)
            // Le sous-total "produits normaux" se déduit à l'affichage : subtotal - total_menu
            $table->double('total_menu')->default(0)->after('subtotal');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('total_menu');
        });
    }
};
