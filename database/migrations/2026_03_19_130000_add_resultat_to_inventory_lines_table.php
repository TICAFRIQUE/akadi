<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inventory_lines', function (Blueprint $table) {
            $table->string('resultat')->nullable()->after('ecart');
        });
    }

    public function down()
    {
        Schema::table('inventory_lines', function (Blueprint $table) {
            $table->dropColumn('resultat');
        });
    }
};
