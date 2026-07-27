<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_produits', function (Blueprint $table) {
            $table->foreignId('plat_id')->nullable()->after('menu_jour_id')->constrained('plats')->nullOnDelete();
        });

        // Backfill : chaque nom distinct déjà saisi devient une entrée du catalogue "plats",
        // et les lignes existantes sont reliées à ce catalogue par nom.
        $noms = DB::table('menu_produits')->select('nom')->distinct()->pluck('nom');

        foreach ($noms as $nom) {
            $dernier = DB::table('menu_produits')
                ->where('nom', $nom)
                ->orderByDesc('created_at')
                ->first();

            $platId = DB::table('plats')->where('nom', $nom)->value('id');

            if (!$platId) {
                $platId = DB::table('plats')->insertGetId([
                    'nom'         => $nom,
                    'description' => $dernier->description ?? null,
                    'prix'        => $dernier->prix,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }

            DB::table('menu_produits')->where('nom', $nom)->update(['plat_id' => $platId]);
        }
    }

    public function down(): void
    {
        Schema::table('menu_produits', function (Blueprint $table) {
            $table->dropConstrainedForeignId('plat_id');
        });
    }
};
