<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Provenance de la commande
            $table->string('source')
                ->default('web')
                ->nullable()
                ->after('type_order');

            // Acompte versé par le client
            $table->double('acompte')->nullable()->default(0)->after('source');

            // Solde restant à payer (total - acompte)
            $table->double('solde_restant')->nullable()->default(0)->after('acompte');

            // Moyen de paiement (remplace le champ texte "payment method")
            $table->foreignId('payment_method_id')
                ->nullable()
                ->after('solde_restant')
                ->constrained('payment_methods')
                ->onUpdate('cascade')
                ->onDelete('set null');

            // Caisse utilisée pour cette vente
            $table->foreignId('caisse_id')
                ->nullable()
                ->after('payment_method_id')
                ->constrained('caisses')
                ->onUpdate('cascade')
                ->onDelete('set null');

            // Qui a créé la commande (agent backoffice)
            $table->foreignId('created_by')
                ->nullable()
                ->after('caisse_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('set null');

            // Numéro de téléphone du client (si commande rapide sans compte)
            $table->string('client_phone')->nullable()->after('created_by');
            $table->string('client_name')->nullable()->after('client_phone');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['payment_method_id']);
            $table->dropForeign(['caisse_id']);
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'source',
                'acompte',
                'solde_restant',
                'payment_method_id',
                'caisse_id',
                'created_by',
                'client_phone',
                'client_name',
            ]);
        });
    }
};
