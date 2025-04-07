<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Depense;
use Illuminate\Http\Request;
use App\Models\CategorieDepense;
use Illuminate\Support\Facades\DB;

class RapportController extends Controller
{
    //compte d'expoitation
    public function exploitation(Request $request)
    {
        try {
            // 1. Récupération des catégories de dépense
            $categories_depense = CategorieDepense::with('libelleDepenses')->orderBy('libelle')->get(); //categorie pour recuperer les libelles de cat_depense selectionner
            $categories = CategorieDepense::with('libelleDepenses')->orderBy('libelle')->get(); // pour le filtre des cat_depense



            $venteQuery = Order::query(); // toutes les orders
            $depenseQuery = Depense::query(); // toutes les depenses



            // Vérifier si aucune période ou date spécifique n'a été fournie
            if (!$request->filled('periode') && !$request->filled('date_debut') && !$request->filled('date_fin')) {
                $venteQuery->whereMonth('orders.created_at', Carbon::now()->month)
                    ->whereYear('orders.created_at', Carbon::now()->year);
                $depenseQuery->whereMonth('date_depense', Carbon::now()->month)
                    ->whereYear('date_depense', Carbon::now()->year);
            }


            // 3. Application des filtres de date
            // Formatage des dates
            $dateDebut = $request->filled('date_debut') ? Carbon::parse($request->date_debut)->format('Y-m-d') : null;
            $dateFin = $request->filled('date_fin') ? Carbon::parse($request->date_fin)->format('Y-m-d') : null;

            // Application des filtres de date
            if ($dateDebut && $dateFin) {
                $venteQuery->whereBetween('orders.created_at', [$dateDebut, $dateFin]);
                $depenseQuery->whereBetween('date_depense', [$dateDebut, $dateFin]);
            } elseif ($dateDebut) {
                $venteQuery->where('orders.created_at', '=', $dateDebut);
                $depenseQuery->where('date_depense', '=', $dateDebut);
            } elseif ($dateFin) {
                $venteQuery->where('orders.created_at', '=', $dateFin);
                $depenseQuery->where('date_depense', '=', $dateFin);
            }

            // Application du filtre de periode
            // periode=> jour, semaine, mois, année

            $periode = $request->input('periode'); // request periode

            if ($request->filled('periode')) {
                if ($periode == 'jour') {
                    $venteQuery->whereDate('orders.created_at', Carbon::today());
                    $depenseQuery->whereDate('date_depense', Carbon::today());
                } elseif ($periode == 'semaine') {
                    $venteQuery->whereBetween('orders.created_at', [Carbon::today()->startOfWeek(), Carbon::today()->endOfWeek()]);
                    $depenseQuery->whereBetween('date_depense', [Carbon::today()->startOfWeek(), Carbon::today()->endOfWeek()]);
                } elseif ($periode == 'mois') {
                    $venteQuery->whereMonth('orders.created_at', Carbon::now()->month)
                        ->whereYear('orders.created_at', Carbon::now()->year);
                    $depenseQuery->whereMonth('date_depense', Carbon::now()->month)
                        ->whereYear('date_depense', Carbon::now()->year);
                } elseif ($periode == 'annee') {
                    $venteQuery->whereYear('orders.created_at', Carbon::now()->year);
                    $depenseQuery->whereYear('date_depense', Carbon::now()->year);
                }
            }

            // 4. Filtrage par catégorie de dépense
            if ($request->filled('categorie_depense')) {
                $categories_depense = $categories_depense->where('id', $request->categorie_depense);
                $depenseQuery->where('categorie_depense_id', $request->categorie_depense);
                $categories = CategorieDepense::with('libelleDepenses')->orderBy('libelle')->get(); // si une categorie on affiche toute les categories
            }

            // 5. Somme des dépenses par libellé et catégorie
            $depenses = $depenseQuery->with(['libelle_depense', 'categorie_depense'])
                ->select('libelle_depense_id', 'categorie_depense_id', DB::raw('SUM(montant) as total_montant'))
                ->groupBy('libelle_depense_id', 'categorie_depense_id')
                ->get();

            // 6. Groupement des dépenses par catégorie
            $depensesParCategorie = $depenses->groupBy('categorie_depense.libelle');

            // 7. Total des orders globale 

            ##Total des montants remise
            $totalRemise = $venteQuery->sum('discount');

            ##Total des montants avant remise
            $totalVenteBrut = $venteQuery->sum('subtotal') - $totalRemise;

            ##Total des montants apres remise
            $totalVenteNet = $venteQuery->sum('total');




            // // 8. Calcul des orders par famille (bar et menu) avec la table pivot
            // $ordersParFamille = $venteQuery->with('produits.categorie')
            //     ->select('categories.name', DB::raw('SUM(produit_vente.quantite * produit_vente.prix_unitaire) as total_orders'))
            //     ->join('produit_vente', 'orders.id', '=', 'produit_vente.vente_id')
            //     ->join('produits', 'produit_vente.produit_id', '=', 'produits.id')
            //     ->join('categories', 'produits.categorie_id', '=', 'categories.id')
            //     // ->whereIn('categories.famille', ['bar', 'menu'])
            //     ->groupBy('categories.name')
            //     ->get()
            //     ->pluck('total_orders', 'categorie')
            //     ->toArray();
            //     // dd($ordersParFamille);

            // Calcul des resultat


            $totalDepenses = $depenses->sum('total_montant');
            $totalVentes = $totalVenteNet;  // variable 


            $benefice = $totalVentes - $totalDepenses; // resultat



            return view('admin.pages.rapport.exploitation', compact('totalVentes', 'benefice', 'totalDepenses',  'categories_depense', 'depensesParCategorie',  'categories', 'totalVenteBrut', 'totalRemise', 'totalVenteNet'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function detail_depense(Request $request)
    {
        try {
            $dateDebut = $request->input('date_debut');
            $dateFin = $request->input('date_fin');
            $categorieDepense = $request->input('categorie_depense');  // ID de la catégorie de depense selectionnée
            $libelleDepense = $request->input('libelle_depense');

            $produitId = $request->input('produitId'); // Id du produit pour recuperer tous les achats du produits


            // Vérification de la catégorie de dépense
            $categorieDepenseExiste = CategorieDepense::where('id', $categorieDepense)->first();

           

            // Récupération des dépenses de la catégorie sélectionnée dans la période spécifiée
            $depenses = Depense::where('categorie_depense_id', $categorieDepense)
                ->where('libelle_depense_id', $libelleDepense)
                ->when($dateDebut && $dateFin, function ($query) use ($dateDebut, $dateFin) {
                    return $query->whereBetween('date_depense', [$dateDebut, $dateFin]);
                })
                ->when($dateDebut && !$dateFin, function ($query) use ($dateDebut) {
                    return $query->where('date_depense', '>=', $dateDebut);
                })
                ->when(!$dateDebut && $dateFin, function ($query) use ($dateFin) {
                    return $query->where('date_depense', '<=', $dateFin);
                })
                ->with(['categorie_depense', 'libelle_depense'])
                ->get();
           
            return view('admin.pages.rapport.detail_depense', compact('depenses', 'dateDebut', 'dateFin', 'categorieDepense'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
