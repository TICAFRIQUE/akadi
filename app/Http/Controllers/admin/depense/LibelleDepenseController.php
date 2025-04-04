<?php

namespace App\Http\Controllers\admin\depense;

use Illuminate\Http\Request;
use App\Models\LibelleDepense;
use App\Models\CategorieDepense;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class LibelleDepenseController extends Controller
{
    public function index()
    {
        //
        try {
            $data_libelleDepense = LibelleDepense::OrderBy('created_at', 'desc')->get();
            // $categorie_depense = CategorieDepense::OrderBy('libelle', 'ASC')->get();
            $categorie_depense = CategorieDepense::whereNotIn('slug', ['achats'])->get();

            return view('admin.pages.depense.libelle-depense.index', compact('data_libelleDepense', 'categorie_depense'));
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }


    public function store(Request $request)
    {
        try {

            $data = $request->validate([
                'libelle' => 'required',
                'categorie_depense_id' => 'required',
                'description' => '',

            ]);

            $data_count = LibelleDepense::count();

            $data_LibelleDepense = LibelleDepense::firstOrCreate($data, ['user_id' => Auth::id()]);

            // Si vous utilisez AJAX, renvoyez les données en JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Libellé ajouté avec succès.',
                    'libelle' => $data_LibelleDepense // Vous renvoyez ici l'élément inséré
                ]);
            }


            return back()->with('success', 'operation reussi avec success');

        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


    public function edit(Request $request, $id)
    {
        try {
            $categorie_depense = CategorieDepense::all();
            $libelle_depense = LibelleDepense::find($id);
            return view('admin.pages.depense.libelle-depense.edit', compact('categorie_depense' , 'libelle_depense'));
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            //request validation ......
            $data = $request->validate([
                'libelle' => 'required',
                'categorie_depense_id' => 'required',
                'description' => '',

            ]);


            $data_LibelleDepense = LibelleDepense::find($id)->update($data, ['user_id' => Auth::id()]);

            return redirect()->route('libelle-depense.index')->withSuccess('Categorie modifiée avec success');

        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }


    public function delete($id)
    {

        try {
            LibelleDepense::find($id)->forceDelete();
            return response()->json([
                'status' => 200,
            ]);
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }
}
