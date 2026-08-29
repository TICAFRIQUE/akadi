<?php

namespace App\Console\Commands;

use App\Models\StockMovement;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillStockMovements extends Command
{
    protected $signature = 'stock:backfill-movements {--force : Réinsérer même si des mouvements existent déjà}';

    protected $description = "Reconstitue le registre stock_movements à partir de l'historique existant (ventes, achats, sorties). Commande à exécuter une seule fois après la migration.";

    public function handle(): void
    {
        $typesBackfillables = [
            StockMovement::TYPE_VENTE,
            StockMovement::TYPE_ACHAT,
            StockMovement::TYPE_SORTIE,
        ];

        $dejaPresent = StockMovement::whereIn('type', $typesBackfillables)->exists();

        if ($dejaPresent && !$this->option('force')) {
            $this->error("Des mouvements 'vente/achat/sortie' existent déjà dans stock_movements. Relancez avec --force pour les remplacer (les corrections d'inventaire et ajustements manuels déjà enregistrés ne sont pas touchés).");
            return;
        }

        if ($dejaPresent) {
            $this->warn('Suppression des anciens mouvements vente/achat/sortie avant réinsertion...');
            StockMovement::whereIn('type', $typesBackfillables)->delete();
        }

        // ── 1. Collecter tous les mouvements connus, tous produits confondus ──────
        $ventes = DB::table('order_product_base')
            ->join('orders', 'order_product_base.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'annulée')
            ->select(
                'order_product_base.product_base_id',
                'order_product_base.quantity_consumed as quantite',
                'orders.created_at as date',
                'order_product_base.order_id as reference_id'
            )
            ->get()
            ->map(fn($row) => [
                'product_base_id' => $row->product_base_id,
                'type'            => StockMovement::TYPE_VENTE,
                'quantity'        => -abs((float) $row->quantite),
                'date'            => $row->date,
                'reference_type'  => 'order',
                'reference_id'    => $row->reference_id,
            ]);

        $achats = DB::table('achat_lignes')
            ->join('achats', 'achat_lignes.achat_id', '=', 'achats.id')
            ->whereNull('achats.deleted_at')
            ->select(
                'achat_lignes.product_base_id',
                'achat_lignes.quantite',
                'achats.date_achat as date',
                'achat_lignes.id as reference_id'
            )
            ->get()
            ->map(fn($row) => [
                'product_base_id' => $row->product_base_id,
                'type'            => StockMovement::TYPE_ACHAT,
                'quantity'        => abs((float) $row->quantite),
                'date'            => $row->date . ' 00:00:00',
                'reference_type'  => 'achat_ligne',
                'reference_id'    => $row->reference_id,
            ]);

        $sorties = DB::table('sortie_stocks')
            ->select('product_base_id', 'quantite', 'date_sortie as date', 'id as reference_id')
            ->get()
            ->map(fn($row) => [
                'product_base_id' => $row->product_base_id,
                'type'            => StockMovement::TYPE_SORTIE,
                'quantity'        => -abs((float) $row->quantite),
                'date'            => $row->date . ' 00:00:00',
                'reference_type'  => 'sortie_stock',
                'reference_id'    => $row->reference_id,
            ]);

        $tousLesMouvements = $ventes->concat($achats)->concat($sorties)
            ->groupBy('product_base_id');

        // ── 2. Pour chaque produit de base, rejouer les mouvements dans l'ordre ───
        $totalInseres = 0;
        $now = now();

        foreach ($tousLesMouvements as $productBaseId => $mouvements) {
            $tries = $mouvements->sortBy('date')->values();
            $solde = 0;
            $lignes = [];

            foreach ($tries as $m) {
                $solde += $m['quantity'];
                $lignes[] = [
                    'product_base_id' => $productBaseId,
                    'type'            => $m['type'],
                    'quantity'        => $m['quantity'],
                    'stock_apres'     => $solde,
                    'reference_type'  => $m['reference_type'],
                    'reference_id'    => $m['reference_id'],
                    'user_id'         => null,
                    'note'            => 'Reconstitué automatiquement (backfill historique)',
                    'created_at'      => $m['date'],
                    'updated_at'      => $now,
                ];
            }

            foreach (array_chunk($lignes, 500) as $chunk) {
                DB::table('stock_movements')->insert($chunk);
            }

            $totalInseres += count($lignes);
        }

        $this->info("Terminé : {$totalInseres} mouvement(s) reconstitué(s) pour " . $tousLesMouvements->count() . ' produit(s) de base.');
        $this->warn("Rappel : les corrections d'inventaire et modifications manuelles de stock antérieures à aujourd'hui n'ont laissé aucune trace et ne peuvent pas être reconstituées — l'historique backfillé est donc approximatif jusqu'à la première correction d'inventaire effectuée après ce déploiement.");
    }
}
