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
        $request->validate(['nom' => 'required|string|max:100']);
        PaymentMethod::findOrFail($id)->update([
            'nom'      => $request->nom,
            'code'     => $request->code,
            'icone'    => $request->icone,
            'position' => $request->position ?? 0,
            'actif'    => $request->boolean('actif', true),
        ]);
        return redirect()->back()->with('success', 'Moyen de paiement mis à jour.');
    }

    public function changeState(Request $request)
    {
        $pm = PaymentMethod::findOrFail($request->id);
        $pm->update(['actif' => !$pm->actif]);
        return response()->json(['success' => true, 'actif' => $pm->actif]);
    }

    public function destroy($id)
    {
        PaymentMethod::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Moyen de paiement supprimé.');
    }
}
