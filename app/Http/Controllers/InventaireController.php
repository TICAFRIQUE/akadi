<?php

namespace App\Http\Controllers;

use App\Models\Inventaire;
use App\Models\InventoryLine;
use App\Models\ProductBase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventaireController extends Controller
{
    public function index()
    {
        // Liste des inventaires
        $inventaires = Inventaire::with('user')->orderByDesc('date_inventaire')->get();
        return view('admin.pages.inventaire.index', compact('inventaires'));
    }

    public function show($id)
    {
        $inventaire = Inventaire::with(['lignes.productBase', 'user'])->findOrFail($id);
        return view('admin.pages.inventaire.show', compact('inventaire'));
    }

    // public function create()
    // {
    //     // Générer les lignes d'inventaire pour chaque produit de base
    //     $produits = ProductBase::all();
    //     $lignes = [];
    //     foreach ($produits as $produit) {
    //         // Chercher le dernier inventaire pour ce produit
    //         $dernierInventaire = InventoryLine::where('product_base_id', $produit->id)
    //             ->orderByDesc('created_at')->first();
    //         $dateDebut = $dernierInventaire ? $dernierInventaire->created_at : $produit->created_at;
    //         $stockDernier = $dernierInventaire ? $dernierInventaire->stock_physique : 0;

    //         // Stock ajouté (achats/entrées) depuis le dernier inventaire
    //         $stockAjoute = $produit->achats()
    //             ->where('achats.created_at', '>', $dateDebut)
    //             ->sum('achat_lignes.quantite');

    //         // Stock vendu depuis le dernier inventaire
    //         $stockVendu = $produit->ventes()
    //             ->where('order_product.created_at', '>', $dateDebut)
    //             ->sum('order_product.quantity');

    //         // Stock sorti (sorties diverses) depuis le dernier inventaire
    //         $stockSortie = $produit->sorties()
    //             ->where('sortie_stocks.created_at', '>', $dateDebut)
    //             ->sum('quantite');

    //         $stockTotal = $stockDernier + $stockAjoute;
    //         $stockRestant = $stockTotal - ($stockVendu + $stockSortie);

    //         $lignes[] = [
    //             'product_base_id' => $produit->id,
    //             'produit' => $produit,
    //             'stock_dernier_inventaire' => $stockDernier,
    //             'stock_ajoute' => $stockAjoute,
    //             'stock_total' => $stockTotal,
    //             'stock_vendu' => $stockVendu,
    //             'stock_sortie' => $stockSortie,
    //             'stock_restant' => $stockRestant,
    //             'stock_physique' => $produit->stock_physique,
    //             'ecart' => null,
    //         ];
    //     }
    //     return view('admin.pages.inventaire.create', compact('lignes'));
    // }

    //version 2 : on affiche la date du dernier inventaire, et on calcule les mouvements depuis cette date (et pas depuis le début du temps)
    public function create()
    {
        $produits = ProductBase::all();
        $lignes   = [];

        foreach ($produits as $produit) {

            $dernierInventaire = InventoryLine::where('product_base_id', $produit->id)
                ->orderByDesc('created_at')
                ->first();

            if ($dernierInventaire) {
                // 👇 Depuis le dernier inventaire
                $dateDebut   = $dernierInventaire->created_at;
                $stockDepart = $dernierInventaire->stock_physique; // ce qui a été constaté physiquement
            } else {
                // 👇 Jamais inventorié → on part de la création du produit, stock = 0
                $dateDebut   = $produit->created_at;
                $stockDepart = 0;
            }

            // Mouvements DEPUIS la date du dernier inventaire
            $stockAjoute = $produit->achats()
                ->where('achat_lignes.created_at', '>', $dateDebut)
                ->sum('achat_lignes.quantite');

            $stockVendu = $produit->ventes()
                ->where('order_product.created_at', '>', $dateDebut)
                ->sum('order_product.quantity');

            $stockSortie = $produit->sorties()
                ->where('sortie_stocks.created_at', '>', $dateDebut)
                ->sum('quantite');

            // Stock théorique = ce qu'on devrait avoir
            $stockTheorique = $stockDepart + $stockAjoute - $stockVendu - $stockSortie;

            $lignes[] = [
                'product_base_id'          => $produit->id,
                'produit'                  => $produit,
                'date_debut'               => $dernierInventaire ? $dateDebut : $produit->created_at, // 👈
                'date_fin'                 => now(),                                                   // 👈
                'date_dernier_inventaire'  => $dernierInventaire ? $dateDebut : null,
                'stock_dernier_inventaire' => $stockDepart,
                'stock_ajoute'             => $stockAjoute,
                'stock_vendu'              => $stockVendu,
                'stock_sortie'             => $stockSortie,
                'stock_theorique'          => $stockTheorique,
                'stock_restant'            => $stockTheorique,
                'stock_total'              => $stockDepart + $stockAjoute,
                'stock_physique'           => 0,
                'ecart'                    => null,
            ];
        }

        return view('admin.pages.inventaire.create', compact('lignes'));
    }



    // public function store(Request $request)
    // {
    //     $inventaire = Inventaire::create([
    //         'date_inventaire' => Carbon::now(),
    //         'user_id' => Auth::id(),
    //         'resultat' => $request->input('resultat'),
    //     ]);
    //     foreach ($request->lignes as $ligne) {
    //         $stock_physique = floatval($ligne['stock_physique']);
    //         $stock_restant = floatval($ligne['stock_restant']);
    //         $ecart = $stock_physique - $stock_restant;
    //         $resultat = null;
    //         if ($stock_restant == 0 && $stock_physique == 0) {
    //             $resultat = 'rupture';
    //         } elseif ($stock_physique > $stock_restant) {
    //             $resultat = 'surplus';
    //         } elseif (abs($ecart) < 0.00001) {
    //             $resultat = 'conforme';
    //         } elseif ($ecart < 0) {
    //             $resultat = 'perte';
    //         } elseif ($ecart > 0) {
    //             $resultat = 'rupture';
    //         }
    //         InventoryLine::create([
    //             'inventaire_id' => $inventaire->id,
    //             'product_base_id' => $ligne['product_base_id'],
    //             'stock_dernier_inventaire' => $ligne['stock_dernier_inventaire'],
    //             'stock_ajoute' => $ligne['stock_ajoute'],
    //             'stock_total' => $ligne['stock_total'],
    //             'stock_vendu' => $ligne['stock_vendu'],
    //             'stock_sortie' => $ligne['stock_sortie'],
    //             'stock_restant' => $stock_restant,
    //             'stock_physique' => $stock_physique,
    //             'ecart' => $ecart,
    //             'resultat' => $resultat,
    //         ]);
    //         // Mettre à jour le stock_physique du produit
    //         $produit = ProductBase::find($ligne['product_base_id']);
    //         $produit->stock_physique = $stock_physique;
    //         $produit->save();
    //     }
    //     return redirect()->route('inventaire.show', $inventaire->id)->with('success', 'Inventaire enregistré');
    // }

//version 2 : on ajoute une transaction pour éviter les problèmes de données incohérentes en cas d'erreur
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $inventaire = Inventaire::create([
                'date_inventaire' => Carbon::now(),
                'user_id'         => Auth::id(),
                'resultat'        => $request->input('resultat'),
            ]);

            foreach ($request->lignes as $ligne) {
                $stock_physique = floatval($ligne['stock_physique']);
                $stock_restant  = floatval($ligne['stock_restant']);
                $ecart          = $stock_physique - $stock_restant;

                // Calcul du résultat
                if ($stock_restant == 0 && $stock_physique == 0) {
                    $resultat = 'rupture';
                } elseif (abs($ecart) < 0.00001) {
                    $resultat = 'conforme';
                } elseif ($ecart > 0) {
                    $resultat = 'surplus';
                } else {
                    $resultat = 'perte';
                }

                InventoryLine::create([
                    'inventaire_id'            => $inventaire->id,
                    'product_base_id'          => $ligne['product_base_id'],
                    'date_debut'               => $ligne['date_debut'],   // 👈 depuis le hidden input
                    'date_fin'                 => $ligne['date_fin'],     // 👈 depuis le hidden input
                    'stock_dernier_inventaire' => $ligne['stock_dernier_inventaire'],
                    'stock_ajoute'             => $ligne['stock_ajoute'],
                    'stock_total'              => $ligne['stock_total'],
                    'stock_vendu'              => $ligne['stock_vendu'],
                    'stock_sortie'             => $ligne['stock_sortie'],
                    'stock_restant'            => $stock_restant,
                    'stock_physique'           => $stock_physique,
                    'ecart'                    => $ecart,
                    'resultat'                 => $resultat,
                ]);

                // Mettre à jour le stock et stock_physique du produit
                ProductBase::where('id', $ligne['product_base_id'])
                    ->update(['stock' => $stock_physique, 'stock_physique' => $stock_physique]); // 👈 update direct, pas besoin de find()
            }

            DB::commit();
            return redirect()->route('inventaire.show', $inventaire->id)
                ->with('success', 'Inventaire enregistré avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'enregistrement : ' . $e->getMessage());
        }
    }
}
