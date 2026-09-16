<?php

namespace App\Console\Commands;

use App\Models\ProductBase;
use App\Models\StockMovement;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReconcileStockEcarts extends Command
{
    protected $signature = 'stock:reconcile-ecarts
        {--threshold=5 : Écart maximum (valeur absolue) corrigé automatiquement}
        {--dry-run : Affiche ce qui serait fait sans rien écrire en base}';

    protected $description = "Aligne le registre stock_movements sur le stock réel (product_bases.stock) pour les produits en écart, sans inventaire physique. Ne touche jamais à product_bases.stock : on considère la colonne réelle comme la valeur de référence (c'est elle qui gère les ventes), et on ajoute un mouvement de correction pour que 'Suivi de stock' ne montre plus d'écart. Les écarts au-delà du seuil sont seulement listés, pas corrigés.";

    public function handle(): void
    {
        $threshold = abs((float) $this->option('threshold'));
        $dryRun    = (bool) $this->option('dry-run');

        $debutDateTime = '2000-01-01 00:00:00';
        $finDateTime   = now()->format('Y-m-d H:i:s');

        $corrigés   = [];
        $aVerifier  = [];

        foreach (ProductBase::orderBy('nom')->get() as $pb) {
            // Même algorithme que SuiviStockController::index(), sur toute l'historique
            // disponible (pas juste le mois en cours) pour obtenir l'écart réel actuel.
            $dernierReset = StockMovement::where('product_base_id', $pb->id)
                ->whereIn('type', [
                    StockMovement::TYPE_CORRECTION_INVENTAIRE,
                    StockMovement::TYPE_AJUSTEMENT_MANUEL,
                ])
                ->where('created_at', '<=', $finDateTime)
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->first();

            if ($dernierReset) {
                $stockInitial = (float) $dernierReset->stock_apres;
                $mouvementsPeriode = StockMovement::where('product_base_id', $pb->id)
                    ->where('id', '>', $dernierReset->id)
                    ->where('created_at', '<=', $finDateTime)
                    ->get();
            } else {
                $stockInitial = 0;
                $mouvementsPeriode = StockMovement::where('product_base_id', $pb->id)
                    ->where('created_at', '<=', $finDateTime)
                    ->get();
            }

            $stockAjoute      = (float) $mouvementsPeriode->where('type', StockMovement::TYPE_ACHAT)->sum('quantity');
            $stockVendu       = (float) abs($mouvementsPeriode->where('type', StockMovement::TYPE_VENTE)->sum('quantity'));
            $stockSortie      = (float) abs($mouvementsPeriode->where('type', StockMovement::TYPE_SORTIE)->sum('quantity'));
            $stockAjustements = (float) $mouvementsPeriode->where('type', StockMovement::TYPE_ANNULATION_VENTE)->sum('quantity');

            $stockActuel    = (float) $pb->stock;
            $stockTotal     = $stockInitial + $stockAjoute;
            $stockTheorique = $stockTotal + $stockAjustements - $stockVendu - $stockSortie;
            $ecart          = $stockActuel - $stockTheorique;

            if (abs($ecart) < 0.00001) {
                continue;
            }

            if (abs($ecart) > $threshold) {
                $aVerifier[] = [$pb->id, $pb->nom, $stockTheorique, $stockActuel, $ecart];
                continue;
            }

            $corrigés[] = [$pb->id, $pb->nom, $stockTheorique, $stockActuel, $ecart];

            if (!$dryRun) {
                DB::transaction(function () use ($pb, $ecart, $stockActuel) {
                    StockMovement::create([
                        'product_base_id' => $pb->id,
                        'type'            => StockMovement::TYPE_CORRECTION_INVENTAIRE,
                        'quantity'        => $ecart,
                        'stock_apres'     => $stockActuel,
                        'reference_type'  => 'reconciliation_auto',
                        'note'            => 'Correction automatique sans comptage physique — écart aligné sur le stock réel (product_bases.stock non modifié).',
                    ]);
                });
            }
        }

        $this->line($dryRun ? '=== Aperçu (--dry-run, rien n\'a été écrit) ===' : '=== Corrections appliquées ===');
        $this->table(
            ['ID', 'Produit', 'Stock théorique', 'Stock actuel', 'Écart corrigé'],
            $corrigés
        );

        if (!empty($aVerifier)) {
            $this->warn("Écarts au-delà du seuil de {$threshold} — à vérifier manuellement, non corrigés :");
            $this->table(
                ['ID', 'Produit', 'Stock théorique', 'Stock actuel', 'Écart'],
                $aVerifier
            );
        }

        $this->info(count($corrigés) . ' produit(s) corrigé(s), ' . count($aVerifier) . ' à vérifier manuellement.');
    }
}
