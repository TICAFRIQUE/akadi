<?php

namespace App\Http\Controllers;

use App\Models\ProductBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductBaseController extends Controller
{
    /**
     * Afficher la liste des produits de base
     */
    public function index()
    {
        $productBases = ProductBase::with('products')
            ->orderBy('nom')
            ->get();

        return view('admin.pages.product-base.index', compact('productBases'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.pages.product-base.create');
    }

    /**
     * Enregistrer un nouveau produit de base
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock' => 'nullable|numeric|min:0',
            'stock_alerte' => 'required|numeric|min:0',
            'unite' => 'required|string|max:50',
            'prix_achat_moyen' => 'nullable|numeric|min:0',
            'actif' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $productBase = ProductBase::create([
            'nom' => $request->nom,
            'description' => $request->description,
            'stock' => $request->stock ?? 0,
            'stock_alerte' => $request->stock_alerte,
            'unite' => $request->unite,
            'prix_achat_moyen' => $request->prix_achat_moyen ?? 0,
            'actif' => $request->has('actif'),
        ]);

        return redirect()->route('product-base.index')
            ->with('success', 'Produit de base créé avec succès');
    }

    /**
     * Afficher un produit de base
     */
    public function show(ProductBase $productBase)
    {
        $productBase->load(['products', 'achats' => function ($query) {
            $query->orderBy('date_achat', 'desc')->limit(10);
        }]);

        return view('admin.pages.product-base.show', compact('productBase'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(ProductBase $productBase)
    {
        return view('admin.pages.product-base.edit', compact('productBase'));
    }

    /**
     * Mettre à jour un produit de base
     */
    public function update(Request $request, ProductBase $productBase)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'stock' => 'nullable|numeric|min:0',
            'stock_alerte' => 'required|numeric|min:0',
            'unite' => 'required|string|max:50',
            'prix_achat_moyen' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $productBase->update([
            'nom' => $request->nom,
            'description' => $request->description,
            'stock' => $request->stock,
            'stock_alerte' => $request->stock_alerte,
            'unite' => $request->unite,
            'prix_achat_moyen' => $request->prix_achat_moyen,
            'actif' => $request->has('actif'),
        ]);

        return redirect()->route('product-base.index')
            ->with('success', 'Produit de base mis à jour avec succès');
    }

    /**
     * Supprimer un produit de base
     */
    public function destroy(ProductBase $productBase)
    {
        // Vérifier s'il y a des produits associés
        if ($productBase->products()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer ce produit de base car il est utilisé par des produits');
        }

        $productBase->delete();

        return redirect()->route('product-base.index')
            ->with('success', 'Produit de base supprimé avec succès');
    }

    /**
     * Obtenir les produits de base pour API/AJAX
     */
    public function getProductBases()
    {
        $productBases = ProductBase::where('actif', true)
            ->orderBy('nom')
            ->get(['id', 'nom', 'unite', 'stock', 'stock_alerte']);

        return response()->json($productBases);
    }
}
