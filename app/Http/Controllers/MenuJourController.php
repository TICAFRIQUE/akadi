<?php

namespace App\Http\Controllers;

use App\Models\MenuJour;
use App\Models\MenuProduit;
use App\Models\Plat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MenuJourController extends Controller
{
    /**
     * Liste des menus du jour (les plus récents en premier)
     */
    public function index()
    {
        $menusJour = MenuJour::withCount('menuProduits')
            ->orderBy('date', 'desc')
            ->paginate(20);

        return view('admin.pages.menu-jour.index', compact('menusJour'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $plats = Plat::where('actif', true)->orderBy('nom')->get(['id', 'nom', 'description', 'prix']);

        return view('admin.pages.menu-jour.create', compact('plats'));
    }

    /**
     * Enregistrer un nouveau menu du jour avec ses plats
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'date'                 => 'required|date|unique:menus_jour,date',
            'note'                 => 'nullable|string',
            'plats'                => 'required|array|min:1',
            'plats.*.plat_id'      => 'nullable|integer|exists:plats,id',
            'plats.*.nom'          => 'required|string|max:150',
            'plats.*.description'  => 'nullable|string',
            'plats.*.prix'         => 'required|numeric|min:0',
        ], [
            'date.unique'      => 'Un menu existe déjà pour cette date.',
            'plats.required'   => 'Ajoutez au moins un plat au menu.',
            'plats.min'        => 'Ajoutez au moins un plat au menu.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $menuJour = MenuJour::create([
            'date'       => $request->date,
            'actif'      => $request->has('actif') ? 1 : 0,
            'note'       => $request->note,
            'created_by' => Auth::id(),
        ]);

        foreach ($request->plats as $plat) {
            $menuJour->menuProduits()->create([
                'plat_id'     => $plat['plat_id'] ?? null,
                'nom'         => $plat['nom'],
                'description' => $plat['description'] ?? null,
                'prix'        => $plat['prix'],
                'disponible'  => true,
            ]);
        }

        return redirect()->route('menu-jour.index')
            ->with('success', 'Menu du jour créé avec succès.');
    }

    /**
     * Formulaire d'édition
     */
    public function edit(MenuJour $menuJour)
    {
        $menuJour->load('menuProduits');

        $plats = Plat::where('actif', true)->orderBy('nom')->get(['id', 'nom', 'description', 'prix']);

        // Inclure aussi les plats désactivés déjà utilisés dans ce menu, pour que
        // leur ligne reste correctement affichée/sélectionnée dans le select2.
        $platIdsUtilises = $menuJour->menuProduits->pluck('plat_id')->filter()->unique();
        $platIdsManquants = $platIdsUtilises->diff($plats->pluck('id'));
        if ($platIdsManquants->isNotEmpty()) {
            $plats = $plats
                ->concat(Plat::whereIn('id', $platIdsManquants)->get(['id', 'nom', 'description', 'prix']))
                ->sortBy('nom')
                ->values();
        }

        $existingPlats = $menuJour->menuProduits->map(fn ($p) => [
            'id'          => $p->id,
            'nom'         => $p->nom,
            'description' => $p->description,
            'prix'        => $p->prix,
            'disponible'  => $p->disponible,
            'plat_id'     => $p->plat_id,
        ])->values();

        return view('admin.pages.menu-jour.edit', compact('menuJour', 'plats', 'existingPlats'));
    }

    /**
     * Mettre à jour un menu du jour et synchroniser ses plats
     */
    public function update(Request $request, MenuJour $menuJour)
    {
        $validator = \Validator::make($request->all(), [
            'date'                 => ['required', 'date', Rule::unique('menus_jour', 'date')->ignore($menuJour->id)],
            'note'                 => 'nullable|string',
            'plats'                => 'required|array|min:1',
            'plats.*.id'           => 'nullable|integer|exists:menu_produits,id',
            'plats.*.plat_id'      => 'nullable|integer|exists:plats,id',
            'plats.*.nom'          => 'required|string|max:150',
            'plats.*.description'  => 'nullable|string',
            'plats.*.prix'         => 'required|numeric|min:0',
        ], [
            'date.unique'      => 'Un menu existe déjà pour cette date.',
            'plats.required'   => 'Ajoutez au moins un plat au menu.',
            'plats.min'        => 'Ajoutez au moins un plat au menu.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $menuJour->update([
            'date'  => $request->date,
            'actif' => $request->has('actif') ? 1 : 0,
            'note'  => $request->note,
        ]);

        $idsGardes = [];

        foreach ($request->plats as $plat) {
            if (!empty($plat['id'])) {
                $menuProduit = MenuProduit::where('menu_jour_id', $menuJour->id)->find($plat['id']);
                if ($menuProduit) {
                    $menuProduit->update([
                        'plat_id'     => $plat['plat_id'] ?? null,
                        'nom'         => $plat['nom'],
                        'description' => $plat['description'] ?? null,
                        'prix'        => $plat['prix'],
                    ]);
                    $idsGardes[] = $menuProduit->id;
                    continue;
                }
            }

            $nouveau = $menuJour->menuProduits()->create([
                'plat_id'     => $plat['plat_id'] ?? null,
                'nom'         => $plat['nom'],
                'description' => $plat['description'] ?? null,
                'prix'        => $plat['prix'],
                'disponible'  => true,
            ]);
            $idsGardes[] = $nouveau->id;
        }

        // Plats retirés du formulaire : supprimer s'ils n'ont jamais été vendus,
        // sinon simplement les désactiver pour ne pas casser l'historique des commandes.
        $platsRetires = $menuJour->menuProduits()->whereNotIn('id', $idsGardes)->get();
        foreach ($platsRetires as $plat) {
            if ($plat->orders()->exists()) {
                $plat->update(['disponible' => false]);
            } else {
                $plat->delete();
            }
        }

        return redirect()->route('menu-jour.index')
            ->with('success', 'Menu du jour mis à jour avec succès.');
    }

    /**
     * Supprimer un menu du jour (refusé si des plats ont déjà été vendus)
     */
    public function destroy(MenuJour $menuJour)
    {
        $dejaVendu = $menuJour->menuProduits()->whereHas('orders')->exists();

        if ($dejaVendu) {
            return redirect()->back()
                ->with('error', 'Impossible de supprimer ce menu : des plats ont déjà été vendus. Désactivez-le plutôt.');
        }

        $menuJour->delete();

        return redirect()->route('menu-jour.index')
            ->with('success', 'Menu du jour supprimé avec succès.');
    }
}
