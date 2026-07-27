<?php

namespace App\Http\Controllers;

use App\Models\Plat;
use Illuminate\Http\Request;

class PlatController extends Controller
{
    /**
     * Liste du catalogue de plats.
     */
    public function index()
    {
        $plats = Plat::orderBy('nom')->get();

        return view('admin.pages.plat.index', compact('plats'));
    }

    /**
     * Crée un plat. Utilisé à la fois par la page de gestion (formulaire classique)
     * et par le select2 du menu du jour (AJAX, création à la volée).
     */
    public function store(Request $request)
    {
        $rules = [
            'nom'         => 'required|string|max:150',
            'description' => 'nullable|string',
            'prix'        => 'required|numeric|min:0',
        ];

        if (!$request->wantsJson()) {
            $rules['nom'] .= '|unique:plats,nom';
        }

        $validated = $request->validate($rules);

        $plat = $request->wantsJson()
            ? Plat::firstOrCreate(
                ['nom' => trim($validated['nom'])],
                ['description' => $validated['description'] ?? null, 'prix' => $validated['prix'], 'actif' => true]
            )
            : Plat::create([
                'nom'         => trim($validated['nom']),
                'description' => $validated['description'] ?? null,
                'prix'        => $validated['prix'],
                'actif'       => $request->has('actif') ? 1 : 0,
            ]);

        if ($request->wantsJson()) {
            return response()->json([
                'id'          => $plat->id,
                'nom'         => $plat->nom,
                'description' => $plat->description,
                'prix'        => $plat->prix,
            ]);
        }

        return redirect()->route('plats.index')->with('success', 'Plat ajouté avec succès.');
    }

    /**
     * Met à jour un plat du catalogue.
     */
    public function update(Request $request, Plat $plat)
    {
        $validated = $request->validate([
            'nom'         => 'required|string|max:150|unique:plats,nom,' . $plat->id,
            'description' => 'nullable|string',
            'prix'        => 'required|numeric|min:0',
        ]);

        $plat->update($validated + ['actif' => $request->has('actif') ? 1 : 0]);

        return redirect()->route('plats.index')->with('success', 'Plat modifié avec succès.');
    }

    /**
     * Supprime un plat du catalogue (refusé s'il est déjà utilisé dans un menu du jour).
     */
    public function destroy(Plat $plat)
    {
        if ($plat->menuProduits()->exists()) {
            return response()->json([
                'status'  => 422,
                'message' => "Impossible de supprimer ce plat : il est déjà utilisé dans un menu du jour.",
            ], 422);
        }

        $plat->delete();

        return response()->json(['status' => 200]);
    }
}
