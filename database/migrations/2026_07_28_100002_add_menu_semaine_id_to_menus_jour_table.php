<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menus_jour', function (Blueprint $table) {
            $table->foreignId('menu_semaine_id')->nullable()->after('id')
                ->constrained('menus_semaine')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('menus_jour', function (Blueprint $table) {
            $table->dropConstrainedForeignId('menu_semaine_id');
        });
    }
};
