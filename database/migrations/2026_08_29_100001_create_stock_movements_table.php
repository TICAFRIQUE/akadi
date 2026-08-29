<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Registre append-only de tous les mouvements de stock des product_bases :
     * vente, annulation de vente, achat, sortie manuelle, correction d'inventaire,
     * ajustement manuel. Permet de reconstruire le stock exact à n'importe quel
     * instant passé, sans modifier le fonctionnement actuel (colonne product_bases.stock
     * reste la valeur "courante" utilisée partout ailleurs dans l'app).
     */
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_base_id')
                ->constrained('product_bases')
                ->cascadeOnDelete();

            $table->string('type'); // vente, annulation_vente, achat, sortie, correction_inventaire, ajustement_manuel
            $table->decimal('quantity', 12, 2);    // signé : positif = entrée, négatif = sortie
            $table->decimal('stock_apres', 12, 2); // solde résultant après ce mouvement

            $table->string('reference_type')->nullable();   // 'order', 'achat_ligne', 'sortie_stock', 'inventory_line', ...
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('note')->nullable();

            $table->timestamps();

            $table->index(['product_base_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
