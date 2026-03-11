<?php

namespace App\Http\Controllers\admin;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::orderBy('position')->get();
        return view('admin.pages.payment_method.index', compact('paymentMethods'));
    }

    public function store(Request $request)
    {
        $request->validate(['nom' => 'required|string|max:100']);
        PaymentMethod::create([
            'nom'      => $request->nom,
            'code'     => $request->code,
            'icone'    => $request->icone,
            'position' => $request->position ?? 0,
            'actif'    => true,
        ]);
        return redirect()->back()->with('success', 'Moyen de paiement ajouté.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'code' => 'nullable|string|max:50',
            'icone' => 'nullable|string|max:100',
            'position' => 'nullable|integer|min:0',
            'actif' => 'required|in:0,1'
        ]);
        
        $pm = PaymentMethod::findOrFail($id);
        $pm->update([
            'nom'      => $request->nom,
            'code'     => $request->code,
            'icone'    => $request->icone,
            'position' => $request->position ?? 0,
            'actif'    => $request->actif == '1',
        ]);
        
        return redirect()->back()->with('success', 'Moyen de paiement mis à jour.');
    }

    public function changeState(Request $request)
    {
        try {
            $request->validate(['id' => 'required|exists:payment_methods,id']);
            
            $pm = PaymentMethod::findOrFail($request->id);
            $newState = !$pm->actif;
            $pm->update(['actif' => $newState]);
            
            return response()->json([
                'success' => true, 
                'actif' => $newState,
                'message' => 'Statut mis à jour avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage()
            ], 400);
        }
    }

    public function destroy($id)
    {
        PaymentMethod::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Moyen de paiement supprimé.');
    }
}
