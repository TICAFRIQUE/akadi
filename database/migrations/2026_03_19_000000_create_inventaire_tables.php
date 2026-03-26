<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ajout du champ stock_physique à product_bases
        Schema::table('product_bases', function (Blueprint $table) {
            $table->decimal('stock_physique', 15, 2)->default(0)->after('stock');
        });

        // Table inventaires
        Schema::create('inventaires', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_inventaire');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });

        // Table inventory_lines
        Schema::create('inventory_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventaire_id');
            $table->unsignedBigInteger('product_base_id');
            // $table->dateTime('date_debut')->nullable();
            // $table->dateTime('date_fin')->nullable();
            $table->decimal('stock_dernier_inventaire', 15, 2);
            $table->decimal('stock_ajoute', 15, 2);
            $table->decimal('stock_total', 15, 2);
            $table->decimal('stock_vendu', 15, 2);
            $table->decimal('stock_sortie', 15, 2);
            $table->decimal('stock_restant', 15, 2);
            $table->decimal('stock_physique', 15, 2);
            $table->decimal('ecart', 15, 2)->nullable();
            $table->timestamps();

            $table->foreign('inventaire_id')->references('id')->on('inventaires')->onDelete('cascade');
            $table->foreign('product_base_id')->references('id')->on('product_bases');
        });
    }

    public function down()
    {
        Schema::table('product_bases', function (Blueprint $table) {
            $table->dropColumn('stock_physique');
        });
        Schema::dropIfExists('inventory_lines');
        Schema::dropIfExists('inventaires');
    }
};
