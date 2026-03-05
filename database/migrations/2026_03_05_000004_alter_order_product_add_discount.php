<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_product', function (Blueprint $table) {
            // Réduction par ligne de produit (montant fixe en FCFA)
            $table->double('discount')->nullable()->default(0)->after('unit_price');
            // Prix après réduction = unit_price - discount
            $table->double('prix_apres_remise')->nullable()->after('discount');
        });
    }

    public function down(): void
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn(['discount', 'prix_apres_remise']);
        });
    }
};
