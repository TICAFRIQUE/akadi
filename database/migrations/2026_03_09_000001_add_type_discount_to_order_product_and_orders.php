<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remise par ligne : type indique si la remise est en % ou montant fixe
        Schema::table('order_product', function (Blueprint $table) {
            $table->enum('type_discount', ['percent', 'fixed'])
                ->default('percent')
                ->nullable()
                ->after('discount');
        });

        // Remise globale : type indique si la remise globale est en % ou montant fixe
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('type_discount', ['percent', 'fixed'])
                ->default('fixed')
                ->nullable()
                ->after('discount');
        });
    }

    public function down(): void
    {
        Schema::table('order_product', function (Blueprint $table) {
            $table->dropColumn('type_discount');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('type_discount');
        });
    }
};
