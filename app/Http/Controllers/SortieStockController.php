<?php

namespace App\Http\Controllers;

use App\Models\SortieStock;
use App\Models\ProductBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SortieStockController extends Controller
{
    /**
     * Afficher la liste des sorties
     */
    public function index()
    {
        $sorties = SortieStock::with(['productBase', 'user'])
            ->orderBy('date_sortie', 'desc')
            ->get();

        return view('admin.pages.sortie-stock.index', compact('sorties'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $productBases = ProductBase::where('actif', true)
            ->orderBy('nom')
            ->get();

            //recuperer les motifs de sortie
            $motifs = SortieStock::getMotifs();

        return view('admin.pages.sortie-stock.create', compact('productBases', 'motifs'));
    }

    /**
     * Enregistrer une nouvelle sortie
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_sortie' => 'required|date',
            'product_base_id' => 'required|exists:product_bases,id',
            'quantite' => 'required|numeric|min:0.5',
            'motif' => 'required|string',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Vérifier que la quantité est un multiple de 0.5
        $quantite = floatval($request->quantite);
        $doubleQuantite = round($quantite * 2);
        $isMultipleOfHalf = (abs(($quantite * 2) - $doubleQuantite) < 0.0001);

        if (!$isMultipleOfHalf) {
            return redirect()->back()
                ->with('error', 'La quantité doit être un multiple de 0.5 (ex: 0.5, 1, 1.5, 2, 2.5, 3, 3.5...)')
                ->withInput();
        }

        // Vérifier le stock disponible
        $productBase = ProductBase::find($request->product_base_id);
        if ($productBase->stock < $request->quantite) {
            return redirect()->back()
                ->with('error', 'Stock insuffisant. Stock actuel : ' . $productBase->stock . ' ' . $productBase->unite)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            SortieStock::create([
                'numero' => 'SOR-' . date('YmdHis') . '-' . mt_rand(100, 999),
                'date_sortie' => $request->date_sortie,
                'product_base_id' => $request->product_base_id,
                'quantite' => $request->quantite,
                'motif' => $request->motif,
                'description' => $request->description,
                'user_id' => Auth::id(),
            ]);

            DB::commit();

            return redirect()->route('sortie-stock.index')
                ->with('success', 'Sortie de stock enregistrée avec succès. Stock mis à jour.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher une sortie
     */
    public function show(SortieStock $sortieStock)
    {
        $sortieStock->load(['productBase', 'user']);

        return view('admin.pages.sortie-stock.show', compact('sortieStock'));
    }

    /**
     * Supprimer une sortie (restaurer le stock)
     */
    public function destroy(SortieStock $sortieStock)
    {
        $sortieStock->delete();

        return redirect()->route('sortie-stock.index')
            ->with('success', 'Sortie supprimée avec succès. Stock restauré.');
    }

    /**
     * Rapport des sorties par période
     */
    public function rapport(Request $request)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->input('date_fin', now()->endOfMonth()->format('Y-m-d'));

        $sorties = SortieStock::with(['productBase', 'user'])
            ->whereBetween('date_sortie', [$dateDebut, $dateFin])
            ->orderBy('date_sortie', 'desc')
            ->get();

        $parProduit = [];

        // Regrouper par produit de base
        foreach ($sorties as $sortie) {
            $pbId = $sortie->product_base_id;
            if (!isset($parProduit[$pbId])) {
                $parProduit[$pbId] = [
                    'produit' => $sortie->productBase->nom,
                    'quantite_totale' => 0,
                    'unite' => $sortie->productBase->unite,
                    'par_motif' => [],
                ];
            }
            $parProduit[$pbId]['quantite_totale'] += $sortie->quantite;

            if (!isset($parProduit[$pbId]['par_motif'][$sortie->motif])) {
                $parProduit[$pbId]['par_motif'][$sortie->motif] = 0;
            }
            $parProduit[$pbId]['par_motif'][$sortie->motif] += $sortie->quantite;
        }

        $stats = [
            'total_sorties' => $sorties->count(),
            'par_produit' => collect($parProduit),
        ];

        return view('admin.pages.sortie-stock.rapport', compact('sorties', 'stats', 'dateDebut', 'dateFin'));
    }
}
