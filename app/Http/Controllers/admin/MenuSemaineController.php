<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MenuJour;
use App\Models\MenuSemaine;
use App\Models\Plat;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuSemaineController extends Controller
{
    /**
     * Liste des menus de la semaine (les plus récents en premier)
     */
    public function index()
    {
        $menusSemaine = MenuSemaine::withCount('menusJour')
            ->orderBy('date_debut', 'desc')
            ->paginate(20);

        return view('admin.pages.menu-semaine.index', compact('menusSemaine'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $plats = Plat::where('actif', true)->orderBy('nom')->get(['id', 'nom', 'description', 'prix']);

        return view('admin.pages.menu-semaine.create', compact('plats'));
    }

    /**
     * Enregistrer un nouveau menu de la semaine avec ses jours et leurs plats.
     * Les jours sont fournis sous forme de tableau clé par date (Y-m-d), déterminée
     * côté client par l'intervalle date_debut → date_fin.
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'titre'                        => 'nullable|string|max:150',
            'date_debut'                   => 'required|date',
            'date_fin'                     => 'required|date|after_or_equal:date_debut',
            'seuil_jours'                  => 'required|integer|min:1',
            'jours'                        => 'required|array',
            'jours.*.actif'                => 'nullable|boolean',
            'jours.*.plats'                => 'nullable|array',
            'jours.*.plats.*.plat_id'      => 'nullable|integer|exists:plats,id',
            'jours.*.plats.*.nom'          => 'required_with:jours.*.plats|string|max:150',
            'jours.*.plats.*.description'  => 'nullable|string',
            'jours.*.plats.*.prix_normal'  => 'required_with:jours.*.plats|numeric|min:0',
            'jours.*.plats.*.prix_reduit'  => 'required_with:jours.*.plats|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $dateDebut = Carbon::parse($request->date_debut)->startOfDay();
        $dateFin = Carbon::parse($request->date_fin)->startOfDay();

        // Ne garder que les jours réellement activés avec au moins un plat, dans l'intervalle choisi
        $joursActifs = [];
        foreach ($request->jours as $date => $jour) {
            $d = Carbon::parse($date)->startOfDay();
            if ($d->lt($dateDebut) || $d->gt($dateFin)) {
                continue;
            }
            if (!empty($jour['actif']) && !empty($jour['plats'])) {
                $joursActifs[$d->format('Y-m-d')] = $jour['plats'];
            }
        }

        if (empty($joursActifs)) {
            return redirect()->back()->withErrors(['jours' => 'Ajoutez au moins un jour avec des plats.'])->withInput();
        }

        // Vérifier qu'aucun menu du jour n'existe déjà sur ces dates
        $conflits = MenuJour::whereIn('date', array_keys($joursActifs))->pluck('date');
        if ($conflits->isNotEmpty()) {
            $listeConflits = $conflits->map(fn ($d) => Carbon::parse($d)->format('d/m/Y'))->implode(', ');
            return redirect()->back()
                ->withErrors(['jours' => "Un menu du jour existe déjà pour : {$listeConflits}. Supprimez-le ou choisissez d'autres dates."])
                ->withInput();
        }

        $menuSemaine = MenuSemaine::create([
            'titre'       => $request->titre ?: null,
            'date_debut'  => $dateDebut->format('Y-m-d'),
            'date_fin'    => $dateFin->format('Y-m-d'),
            'actif'       => $request->has('actif') ? 1 : 0,
            'seuil_jours' => $request->seuil_jours,
            'created_by'  => Auth::id(),
        ]);

        foreach ($joursActifs as $date => $plats) {
            $menuJour = MenuJour::create([
                'menu_semaine_id' => $menuSemaine->id,
                'date'            => $date,
                'actif'           => true,
                'created_by'      => Auth::id(),
            ]);

            foreach ($plats as $plat) {
                $menuJour->menuProduits()->create([
                    'plat_id'     => $plat['plat_id'] ?? null,
                    'nom'         => $plat['nom'],
                    'description' => $plat['description'] ?? null,
                    'prix'        => $plat['prix_normal'],
                    'prix_normal' => $plat['prix_normal'],
                    'prix_reduit' => $plat['prix_reduit'],
                    'disponible'  => true,
                ]);
            }
        }

        return redirect()->route('menu-semaine.index')
            ->with('success', 'Menu de la semaine créé avec succès.');
    }

    /**
     * Détail d'un menu de la semaine
     */
    public function show(MenuSemaine $menuSemaine)
    {
        $menuSemaine->load(['menusJour.menuProduits']);

        return view('admin.pages.menu-semaine.show', compact('menuSemaine'));
    }

    /**
     * Supprimer un menu de la semaine (refusé si des plats ont déjà été vendus)
     */
    public function destroy(MenuSemaine $menuSemaine)
    {
        $menuSemaine->load('menusJour.menuProduits');

        foreach ($menuSemaine->menusJour as $menuJour) {
            if ($menuJour->menuProduits()->whereHas('orders')->exists()) {
                return redirect()->back()
                    ->with('error', 'Impossible de supprimer cette semaine : des plats ont déjà été vendus. Désactivez-la plutôt.');
            }
        }

        foreach ($menuSemaine->menusJour as $menuJour) {
            $menuJour->delete();
        }
        $menuSemaine->delete();

        return redirect()->route('menu-semaine.index')
            ->with('success', 'Menu de la semaine supprimé avec succès.');
    }
}
