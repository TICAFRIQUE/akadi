<?php
namespace App\Http\Controllers;

use App\Models\Inventaire;
use App\Models\InventoryLine;
use App\Models\ProductBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

    public function create()
    {
        // Générer les lignes d'inventaire pour chaque produit de base
        $produits = ProductBase::all();
        $lignes = [];
        foreach ($produits as $produit) {
            // Chercher le dernier inventaire pour ce produit
            $dernierInventaire = InventoryLine::where('product_base_id', $produit->id)
                ->orderByDesc('created_at')->first();
            $dateDebut = $dernierInventaire ? $dernierInventaire->created_at : $produit->created_at;
            $stockDernier = $dernierInventaire ? $dernierInventaire->stock_physique : 0;

            // Stock ajouté (achats/entrées) depuis le dernier inventaire
            $stockAjoute = $produit->achats()
                ->where('achats.created_at', '>', $dateDebut)
                ->sum('achat_lignes.quantite');

            // Stock vendu depuis le dernier inventaire
            $stockVendu = $produit->ventes()
                ->where('order_product.created_at', '>', $dateDebut)
                ->sum('order_product.quantity');

            // Stock sorti (sorties diverses) depuis le dernier inventaire
            $stockSortie = $produit->sorties()
                ->where('sortie_stocks.created_at', '>', $dateDebut)
                ->sum('quantite');

            $stockTotal = $stockDernier + $stockAjoute;
            $stockRestant = $stockTotal - ($stockVendu + $stockSortie);

            $lignes[] = [
                'product_base_id' => $produit->id,
                'produit' => $produit,
                'stock_dernier_inventaire' => $stockDernier,
                'stock_ajoute' => $stockAjoute,
                'stock_total' => $stockTotal,
                'stock_vendu' => $stockVendu,
                'stock_sortie' => $stockSortie,
                'stock_restant' => $stockRestant,
                'stock_physique' => $produit->stock_physique,
                'ecart' => null,
            ];
        }
        return view('admin.pages.inventaire.create', compact('lignes'));
    }

    public function store(Request $request)
    {
        $inventaire = Inventaire::create([
            'date_inventaire' => Carbon::now(),
            'user_id' => Auth::id(),
            'resultat' => $request->input('resultat'),
        ]);
        foreach ($request->lignes as $ligne) {
            $stock_physique = floatval($ligne['stock_physique']);
            $stock_restant = floatval($ligne['stock_restant']);
            $ecart = $stock_physique - $stock_restant;
            $resultat = null;
            if ($stock_restant == 0 && $stock_physique == 0) {
                $resultat = 'rupture';
            } elseif ($stock_physique > $stock_restant) {
                $resultat = 'surplus';
            } elseif (abs($ecart) < 0.00001) {
                $resultat = 'conforme';
            } elseif ($ecart < 0) {
                $resultat = 'perte';
            } elseif ($ecart > 0) {
                $resultat = 'rupture';
            }
            InventoryLine::create([
                'inventaire_id' => $inventaire->id,
                'product_base_id' => $ligne['product_base_id'],
                'stock_dernier_inventaire' => $ligne['stock_dernier_inventaire'],
                'stock_ajoute' => $ligne['stock_ajoute'],
                'stock_total' => $ligne['stock_total'],
                'stock_vendu' => $ligne['stock_vendu'],
                'stock_sortie' => $ligne['stock_sortie'],
                'stock_restant' => $stock_restant,
                'stock_physique' => $stock_physique,
                'ecart' => $ecart,
                'resultat' => $resultat,
            ]);
            // Mettre à jour le stock_physique du produit
            $produit = ProductBase::find($ligne['product_base_id']);
            $produit->stock_physique = $stock_physique;
            $produit->save();
        }
        return redirect()->route('inventaire.show', $inventaire->id)->with('success', 'Inventaire enregistré');
    }
}
