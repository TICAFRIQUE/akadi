<?php

namespace App\Http\Controllers\admin;

use App\Models\Caisse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class CaisseController extends Controller
{
    /** Liste des caisses */
    public function index()
    {
        $caisses = Caisse::with('user')->orderBy('created_at', 'DESC')->get();
        return view('admin.pages.caisse.index', compact('caisses'));
    }

    /** Créer une caisse */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
        ]);

        Caisse::create([
            'nom'         => $request->nom,
            'description' => $request->description,
            'actif'       => true,
            'statut'      => Caisse::STATUT_DISPONIBLE,
        ]);

        return redirect()->back()->with('success', 'Caisse créée avec succès.');
    }

    /** Édition */
    public function edit($id)
    {
        $caisse = Caisse::findOrFail($id);
        return view('admin.pages.caisse.edit', compact('caisse'));
    }

    /** Mise à jour */
    public function update(Request $request, $id)
    {
        $request->validate(['nom' => 'required|string|max:100']);
        $caisse = Caisse::findOrFail($id);
        $caisse->update([
            'nom'         => $request->nom,
            'description' => $request->description,
            'actif'       => $request->boolean('actif', true),
        ]);
        return redirect()->route('caisse.index')->with('success', 'Caisse mise à jour.');
    }

    /** Activation / désactivation */
    public function changeState(Request $request)
    {
        $caisse = Caisse::findOrFail($request->id);
        $caisse->update(['actif' => !$caisse->actif]);
        return response()->json(['success' => true, 'actif' => $caisse->actif]);
    }

    /** Supprimer */
    public function destroy($id)
    {
        Caisse::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Caisse supprimée.');
    }

    // ─── Session de caisse ───────────────────────────────────────────────────────

    /**
     * Page de sélection de caisse après connexion
     */
    public function selection()
    {
        // Si déjà une caisse en session, aller directement au POS
        if (session('caisse_id')) {
            return redirect()->route('pos.create');
        }

        $caisses = Caisse::disponible()->get();
        return view('admin.pages.caisse.selection', compact('caisses'));
    }

    /**
     * L'agent prend une caisse
     */
    public function prendreEnCharge(Request $request)
    {
        $request->validate(['caisse_id' => 'required|exists:caisses,id']);

        $caisse = Caisse::findOrFail($request->caisse_id);

        if ($caisse->statut !== Caisse::STATUT_DISPONIBLE) {
            return redirect()->back()->with('error', 'Cette caisse n\'est plus disponible.');
        }

        $caisse->prendreEnCharge(Auth::user());

        // Stocker en session
        session([
            'caisse_id'  => $caisse->id,
            'caisse_nom' => $caisse->nom,
        ]);

        return redirect()->route('pos.create')->with('success', "Caisse « {$caisse->nom} » activée. Bonne vente !");
    }

    /**
     * L'agent libère sa caisse
     */
    public function liberer()
    {
        $caisseId = session('caisse_id');
        if ($caisseId) {
            $caisse = Caisse::find($caisseId);
            $caisse?->liberer();
            session()->forget(['caisse_id', 'caisse_nom']);
        }
        return redirect()->route('caisse.selection')->with('success', 'Caisse libérée.');
    }
}
