<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FournisseurController extends Controller
{
    /**
     * Afficher la liste des fournisseurs
     */
    public function index()
    {
        $fournisseurs = Fournisseur::orderBy('nom')->get();

        return view('admin.pages.fournisseur.index', compact('fournisseurs'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.pages.fournisseur.create');
    }

    /**
     * Enregistrer un nouveau fournisseur
     */
    public function store(Request $request)
    {
     try {
          $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Fournisseur::create([
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'adresse' => $request->adresse,
            'notes' => $request->notes,
            'actif' => $request->has('actif') ? 1 : 0,
        ]);

        return redirect()->route('fournisseur.index')
            ->with('success', 'Fournisseur créé avec succès.');
     } catch (\Throwable $e) {
        return redirect()->back()
            ->with('error', 'Erreur lors de la création du fournisseur: ' . $e->getMessage())
            ->withInput();
     }
    }

    /**
     * Afficher un fournisseur
     */
    public function show(Fournisseur $fournisseur)
    {
        $fournisseur->load('achats');

        return view('admin.pages.fournisseur.show', compact('fournisseur'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Fournisseur $fournisseur)
    {
        return view('admin.pages.fournisseur.edit', compact('fournisseur'));
    }

    /**
     * Mettre à jour un fournisseur
     */
    public function update(Request $request, Fournisseur $fournisseur)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'adresse' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $fournisseur->update([
            'nom' => $request->nom,
            'telephone' => $request->telephone,
            'email' => $request->email,
            'adresse' => $request->adresse,
            'notes' => $request->notes,
            'actif' => $request->has('actif') ? 1 : 0,
        ]);

        return redirect()->route('fournisseur.index')
            ->with('success', 'Fournisseur mis à jour avec succès.');
    }

    /**
     * Supprimer un fournisseur
     */
    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();

        return redirect()->route('fournisseur.index')
            ->with('success', 'Fournisseur supprimé avec succès.');
    }
}
