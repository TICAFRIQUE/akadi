<?php

namespace App\Http\Controllers;

use App\Models\Achat;
use App\Models\ProductBase;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AchatController extends Controller
{
    /**
     * Afficher la liste des achats
     */
    public function index()
    {
        $achats = Achat::with(['lignes.productBase', 'user'])
            ->orderBy('date_achat', 'desc')
            ->get();

        return view('admin.pages.achat.index', compact('achats'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        $productBases = ProductBase::where('actif', true)
            ->orderBy('nom')
            ->get();

        $fournisseurs = Fournisseur::where('actif', true)
            ->orderBy('nom')
            ->get();

        return view('admin.pages.achat.create', compact('productBases', 'fournisseurs'));
    }

    /**
     * Enregistrer un nouveau achat
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date_achat' => 'required|date',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'fournisseur' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'lignes' => 'required|array|min:1',
            'lignes.*.product_base_id' => 'required|exists:product_bases,id',
            'lignes.*.quantite' => 'required|numeric|min:0.01',
            'lignes.*.prix_unitaire' => 'required|numeric|min:0',
            'lignes.*.notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Créer l'achat
            $achat = Achat::create([
                'numero' => $request->numero ?? 'ACH-' . date('YmdHis') . '-' . mt_rand(100, 999),
                'date_achat' => $request->date_achat,
                'montant_total' => 0, // Sera calculé après
                'fournisseur_id' => $request->fournisseur_id,
                'fournisseur' => $request->fournisseur,
                'notes' => $request->notes,
                'user_id' => Auth::id(),
            ]);

            // Créer les lignes d'achat
            $montantTotal = 0;
            foreach ($request->lignes as $ligneData) {
                $ligne = $achat->lignes()->create([
                    'product_base_id' => $ligneData['product_base_id'],
                    'quantite' => $ligneData['quantite'],
                    'prix_unitaire' => $ligneData['prix_unitaire'],
                    'notes' => $ligneData['notes'] ?? null,
                ]);
                $montantTotal += $ligne->montant_ligne;
            }

            // Mettre à jour le montant total
            $achat->update(['montant_total' => $montantTotal]);

            DB::commit();

            return redirect()->route('achat.index')
                ->with('success', 'Achat enregistré avec succès. Stocks mis à jour.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'enregistrement: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher un achat
     */
    public function show(Achat $achat)
    {
        $achat->load(['lignes.productBase', 'user']);

        return view('admin.pages.achat.show', compact('achat'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Achat $achat)
    {
        $productBases = ProductBase::where('actif', true)
            ->orderBy('nom')
            ->get();

        $fournisseurs = Fournisseur::where('actif', true)
            ->orderBy('nom')
            ->get();

        return view('admin.pages.achat.edit', compact('achat', 'productBases', 'fournisseurs'));
    }

    /**
     * Mettre à jour un achat
     */
    public function update(Request $request, Achat $achat)
    {
        $validator = Validator::make($request->all(), [
            'date_achat' => 'required|date',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'fournisseur' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'lignes' => 'required|array|min:1',
            'lignes.*.id' => 'nullable|exists:achat_lignes,id',
            'lignes.*.product_base_id' => 'required|exists:product_bases,id',
            'lignes.*.quantite' => 'required|numeric|min:0.01',
            'lignes.*.prix_unitaire' => 'required|numeric|min:0',
            'lignes.*.notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            // Mettre à jour l'achat
            $achat->update([
                'date_achat' => $request->date_achat,
                'fournisseur_id' => $request->fournisseur_id,
                'fournisseur' => $request->fournisseur,
                'notes' => $request->notes,
            ]);

            // IDs des lignes envoyées
            $lignesIds = collect($request->lignes)->pluck('id')->filter();

            // Supprimer les lignes qui ne sont plus dans la requête
            $achat->lignes()->whereNotIn('id', $lignesIds)->delete();

            // Mettre à jour ou créer les lignes
            $montantTotal = 0;
            foreach ($request->lignes as $ligneData) {
                if (!empty($ligneData['id'])) {
                    // Mise à jour d'une ligne existante
                    $ligne = $achat->lignes()->find($ligneData['id']);
                    if ($ligne) {
                        $ligne->update([
                            'product_base_id' => $ligneData['product_base_id'],
                            'quantite' => $ligneData['quantite'],
                            'prix_unitaire' => $ligneData['prix_unitaire'],
                            'notes' => $ligneData['notes'] ?? null,
                        ]);
                    }
                } else {
                    // Création d'une nouvelle ligne
                    $ligne = $achat->lignes()->create([
                        'product_base_id' => $ligneData['product_base_id'],
                        'quantite' => $ligneData['quantite'],
                        'prix_unitaire' => $ligneData['prix_unitaire'],
                        'notes' => $ligneData['notes'] ?? null,
                    ]);
                }
                $montantTotal += $ligne->montant_ligne;
            }

            // Mettre à jour le montant total
            $achat->update(['montant_total' => $montantTotal]);

            DB::commit();

            return redirect()->route('achat.index')
                ->with('success', 'Achat mis à jour avec succès. Stocks ajustés.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Supprimer un achat
     */
    public function destroy(Achat $achat)
    {
        $achat->delete();

        return redirect()->route('achat.index')
            ->with('success', 'Achat supprimé avec succès. Stock ajusté.');
    }

    /**
     * Rapport des achats par période
     */
    public function rapport(Request $request)
    {
        $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin = $request->input('date_fin', now()->endOfMonth()->format('Y-m-d'));

        $achats = Achat::with(['lignes.productBase', 'user'])
            ->whereBetween('date_achat', [$dateDebut, $dateFin])
            ->orderBy('date_achat', 'desc')
            ->get();

        $parProduit = [];

        // Regrouper par produit de base
        foreach ($achats as $achat) {
            foreach ($achat->lignes as $ligne) {
                $pbId = $ligne->product_base_id;
                if (!isset($parProduit[$pbId])) {
                    $parProduit[$pbId] = [
                        'produit' => $ligne->productBase->nom,
                        'quantite_totale' => 0,
                        'montant_total' => 0,
                        'nombre_achats' => 0,
                        'unite' => $ligne->productBase->unite,
                    ];
                }
                $parProduit[$pbId]['quantite_totale'] += $ligne->quantite;
                $parProduit[$pbId]['montant_total'] += $ligne->montant_ligne;
                $parProduit[$pbId]['nombre_achats']++;
            }
        }

        $totalAchats = $achats->count();
        $montantTotal = $achats->sum('montant_total');

        $stats = [
            'total_achats' => $totalAchats,
            'montant_total' => $montantTotal,
            'montant_moyen' => $totalAchats > 0 ? $montantTotal / $totalAchats : 0,
            'par_produit' => collect($parProduit),
        ];

        return view('admin.pages.achat.rapport', compact('achats', 'stats', 'dateDebut', 'dateFin'));
    }
}
