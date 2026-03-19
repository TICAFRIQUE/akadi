<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $productBases = ProductBase::with([
            'achatLignes' => function ($query) use ($dateDebut, $dateFin, $filtre) {
                if ($filtre === 'ajoute' || $filtre === 'tous') {
                    $query->whereHas('achat', function ($q) use ($dateDebut, $dateFin) {
                        $q->whereBetween('date_achat', [$dateDebut, $dateFin]);
                    });  
                }
            },
            'sorties' => function ($query) use ($dateDebut, $dateFin, $filtre) {
                if ($filtre === 'sortie' || $filtre === 'tous') {
                    $query->whereBetween('date_sortie', [$dateDebut, $dateFin]);
                }
            }
        ])->orderBy('nom')->get();

        $suiviStock = [];

        foreach ($productBases as $pb) {
            // Calculs
            $stockAjoute = $pb->achatLignes->sum('quantite');
            $stockSortie = $pb->sorties->sum('quantite');
            
            // Stock vendu (via la table pivot order_product)
            $stockVendu = 0;
            if ($filtre === 'vendu' || $filtre === 'tous') {
                $productIds = $pb->products()->pluck('id');
                $orderProductRows = DB::table('order_product')
                    ->join('orders', 'order_product.order_id', '=', 'orders.id')
                    ->whereIn('order_product.product_id', $productIds)
                    ->whereBetween('orders.created_at', [$dateDebut . ' 00:00:00', $dateFin . ' 23:59:59'])
                    ->where('orders.status', '!=', 'annulée')
                    ->select('order_product.quantity', 'order_product.coefficient')
                    ->get();

                foreach ($orderProductRows as $row) {
                    $coef = is_null($row->coefficient) ? 1 : floatval($row->coefficient);
                    $stockVendu += ($row->quantity * $coef);
                }
            }

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

            if ($inclure) {
                $suiviStock[] = [
                    'id' => $pb->id,
                    'produit' => $pb->nom,
                    'unite' => $pb->unite,
                    'stock_ajoute' => $stockAjoute,
                    'stock_vendu' => $stockVendu,
                    'stock_sortie' => $stockSortie,
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

        return view('admin.pages.suivi-stock.index', compact('suiviStock', 'dateDebut', 'dateFin', 'filtre'));
    }
}
