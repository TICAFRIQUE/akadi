<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductBase;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class SuiviStockController extends Controller
{
    /**
     * Afficher le suivi de stock
     */
    public function index(Request $request)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->input('date_fin', now()->endOfMonth()->format('Y-m-d'));
        $filtre = $request->input('filtre', 'tous'); // tous, vendu, sortie, ajoute, existant, disponible

        $productBases = ProductBase::orderBy('nom')->get();

        $suiviStock = [];

        $debutDateTime = $dateDebut . ' 00:00:00';
        $finDateTime   = $dateFin . ' 23:59:59';

        foreach ($productBases as $pb) {
            // ── Point de départ : dernier inventaire/ajustement, ou début de période ──
            // Un inventaire (ou une modification manuelle) fixe le stock à une valeur
            // connue avec certitude : il n'y a plus de raison de remonter plus loin que
            // ça. S'il a eu lieu PENDANT la période choisie, il devient le nouveau
            // "Stock début" à partir de son propre instant (peu importe la date de
            // début choisie) — tout ce qui précède ce reset est superflu.
            $dernierReset = StockMovement::where('product_base_id', $pb->id)
                ->whereIn('type', [
                    StockMovement::TYPE_CORRECTION_INVENTAIRE,
                    StockMovement::TYPE_AJUSTEMENT_MANUEL,
                ])
                ->where('created_at', '<=', $finDateTime)
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->first();

            if ($dernierReset && $dernierReset->created_at >= $debutDateTime) {
                // Le dernier inventaire/ajustement a eu lieu PENDANT la période : il
                // remplace le stock début, et on ne compte que ce qui s'est passé après lui.
                $stockInitial = (float) $dernierReset->stock_apres;
                $mouvementsPeriode = StockMovement::where('product_base_id', $pb->id)
                    ->where('id', '>', $dernierReset->id)
                    ->where('created_at', '<=', $finDateTime)
                    ->get();
            } else {
                // Aucun reset pendant la période : comportement normal, stock début =
                // dernier mouvement connu avant la date de début choisie.
                $dernierMouvementAvant = StockMovement::where('product_base_id', $pb->id)
                    ->where('created_at', '<', $debutDateTime)
                    ->orderByDesc('created_at')
                    ->orderByDesc('id')
                    ->first();

                $stockInitial = $dernierMouvementAvant ? (float) $dernierMouvementAvant->stock_apres : 0;
                $mouvementsPeriode = StockMovement::where('product_base_id', $pb->id)
                    ->whereBetween('created_at', [$debutDateTime, $finDateTime])
                    ->get();
            }

            // Tout vient de la même source (stock_movements) : "Ajouté/Vendu/Sortie"
            // ne peuvent plus jamais diverger de "Stock début"/"Stock actuel" pour des
            // raisons de sources différentes (ancien souci). Comme le point de départ
            // ci-dessus est toujours le dernier reset connu, il ne peut plus rester de
            // correction_inventaire/ajustement_manuel dans $mouvementsPeriode — seules
            // les annulations de vente sont regroupées dans "Ajustements" désormais.
            $stockAjoute      = (float) $mouvementsPeriode->where('type', StockMovement::TYPE_ACHAT)->sum('quantity');
            $stockVendu       = (float) abs($mouvementsPeriode->where('type', StockMovement::TYPE_VENTE)->sum('quantity'));
            $stockSortie      = (float) abs($mouvementsPeriode->where('type', StockMovement::TYPE_SORTIE)->sum('quantity'));
            $stockAjustements = (float) $mouvementsPeriode->where('type', StockMovement::TYPE_ANNULATION_VENTE)->sum('quantity');

            $stockActuel = $pb->stock;
            $stockMin = $pb->stock_alerte ?? 0;
            $stockDisponible = $stockActuel - $stockMin;
            $stockRestant = $stockActuel;

            // Filtrer selon le critère
            $inclure = true;
            if ($filtre === 'ajoute' && $stockAjoute == 0) $inclure = false;
            if ($filtre === 'sortie' && $stockSortie == 0) $inclure = false;
            if ($filtre === 'vendu' && $stockVendu == 0) $inclure = false;
            if ($filtre === 'existant' && $stockActuel == 0) $inclure = false;
            if ($filtre === 'disponible' && $stockDisponible <= 0) $inclure = false;

            $stockTotal     = $stockInitial + $stockAjoute;
            $stockTheorique = $stockTotal + $stockAjustements - $stockVendu - $stockSortie;
            $ecart          = $stockActuel - $stockTheorique;

            if ($inclure) {
                $suiviStock[] = [
                    'id' => $pb->id,
                    'produit' => $pb->nom,
                    'unite' => $pb->unite,
                    'stock_initial' => $stockInitial,
                    'stock_ajoute' => $stockAjoute,
                    'stock_total' => $stockTotal,
                    'stock_vendu' => $stockVendu,
                    'stock_sortie' => $stockSortie,
                    'stock_ajustements' => $stockAjustements,
                    'stock_theorique' => $stockTheorique,
                    'ecart' => $ecart,
                    'stock_actuel' => $stockActuel,
                    'stock_min' => $stockMin,
                    'stock_max' => $stockMin * 2, // Stock max = 2x le seuil d'alerte
                    'stock_disponible' => $stockDisponible,
                    'stock_restant' => $stockRestant,
                    'alerte' => $stockActuel <= $stockMin,
                    'prix_achat_moyen' => $pb->prix_achat_moyen ?? 0,
                ];
            }
        }

        // dd($suiviStock);

        return view('admin.pages.suivi-stock.index', compact('suiviStock', 'dateDebut', 'dateFin', 'filtre'));
    }

    /**
     * Détail des ventes d'un produit de base sur une période : pour chaque vente,
     * le stock disponible juste avant, la quantité vendue, et le stock résultant.
     */
    public function detail(ProductBase $productBase, Request $request)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin   = $request->input('date_fin', now()->endOfMonth()->format('Y-m-d'));

        $mouvements = StockMovement::where('product_base_id', $productBase->id)
            ->where('type', StockMovement::TYPE_VENTE)
            ->whereBetween('created_at', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
            ->orderBy('created_at')
            ->get()
            ->map(function ($m) {
                $order = $m->reference_type === 'order' ? Order::find($m->reference_id) : null;

                return [
                    'date'             => $m->created_at,
                    'commande_code'    => $order->code ?? ('#' . $m->reference_id),
                    'order_id'         => $m->reference_id,
                    'stock_avant'      => (float) $m->stock_apres - (float) $m->quantity,
                    'quantite_vendue'  => abs((float) $m->quantity),
                    'stock_apres'      => (float) $m->stock_apres,
                ];
            });

        return view('admin.pages.suivi-stock.detail', compact('productBase', 'mouvements', 'dateDebut', 'dateFin'));
    }
}
