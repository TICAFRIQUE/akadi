<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDF;


class CouponController extends Controller
{
    //

    public function index()
    {
        $coupon = Coupon::with(['users', 'products'])->orderBy('created_at', 'DESC')->get();
        return view('admin.pages.coupon.index', compact('coupon'));
    }


    public function create()
    {

        //all product
        $product = Product::with(['media', 'categories', 'subcategorie'])->orderBy('created_at', 'DESC')
            ->whereDisponibilite(1)
            ->get();

        //all customer
        $customer = User::with('roles')
            ->whereHas(
                'roles',
                fn($q) => $q->where('name', '!=', 'developpeur')
            )
            ->orderBy('created_at', 'DESC')->get();

        return view('admin.pages.coupon.add',  compact('product', 'customer'));
    }


    // public function store(Request $request)
    // {
    //     // dd($request->toArray());

    //     // gestion validation
    //     $request->validate([
    //         'code' => 'required|unique:coupons,code',
    //         'nom' => 'required',
    //         'quantite' => 'required',
    //         'utilisation_max' => 'required',
    //         'type_remise' => 'required',
    //         'valeur_remise' => 'required',
    //         'montant_min' => 'required',
    //         'montant_max' => '',
    //         'date_debut' => 'required',
    //         'date_fin' => 'required',
    //     ], [
    //         'code.unique' => 'Ce code coupon existe déjà',
    //         'date_debut.after_or_equal' => 'La date de début doit être aujourd\'hui ou future',
    //         'date_fin.after' => 'La date de fin doit être après la date de début'
    //     ]);

    //     try {

    //         //verifier si le code existe dejà
    //         $code_exist = Coupon::where('code', $request->code)
    //             ->where('date_fin', $request->date_fin)
    //             ->exists();

    //         if ($code_exist) {
    //             return back()->with('error', '⚠️ Ce coupon existe déjà et est encore en cours d’utilisation.');
    //         }

    //         $coupon = Coupon::create([
    //             'code' => $request['code'],
    //             'nom' => $request['nom'],
    //             'quantite' => $request['quantite'],
    //             'utilisation_max' => $request['utilisation_max'],
    //             'type_remise' => $request['type_remise'],
    //             'valeur_remise' => $request['valeur_remise'],
    //             'montant_min' => $request['montant_min'],
    //             'montant_max' => $request['montant_max'] ?? 0,
    //             'date_debut' => $request['date_debut'],
    //             'date_fin' => $request['date_fin'],
    //             'type_coupon' => $request['type_coupon'],
    //             'expiration' => $request['date_fin'],
    //         ]);



    //         // if ($request['customers']) {
    //         //     $coupon->users()->attach($request['customers']);
    //         // }

    //         if ($request->filled('customers')) {
    //             $coupon->users()->attach($request->customers);
    //         }

    //         // if ($request['products']) {

    //         //     $coupon->products()->attach($request['products']);
    //         // }

    //         // if ($request['customers']) {
    //         //     # code...
    //         // }

    //         return back()->withSuccess('Coupon crée avec success');
    //     } catch (\Throwable $th) {
    //         return $th->getMessage();
    //     }
    // }


    public function store(Request $request)
    {
        // ✅ Validation des champs
        $request->validate([
           'code' => 'required|string|unique:coupons,code',
            'nom' => 'required',
            'quantite' => 'required|integer|min:1',
            'utilisation_max' => 'required|integer|min:1',
            'type_remise' => 'required|in:montant,pourcentage',
            'valeur_remise' => 'required|numeric|min:0',
            'montant_min' => 'required|numeric|min:0',
            'montant_max' => 'nullable|numeric|min:0',
            'date_debut' => 'required|date|after_or_equal:today',
            'date_fin' => 'required|date|after:date_debut',
            // 'type_coupon' => 'required|in:unique,groupe',
        ], [
            'code.unique' => '⚠️ Ce code coupon existe déjà.',
            'date_debut.after_or_equal' => 'La date de début doit être aujourd\'hui ou ultérieure.',
            'date_fin.after' => 'La date de fin doit être après la date de début.',
            
        ]);

            //   // ✅ Vérifie si un coupon actif existe déjà avec ce code
            // $code_exist = Coupon::where('code', $request->code)
            //     ->where('date_fin', '>=', now()) // coupon encore valide
            //     ->exists();

            // if ($code_exist) {
            //     return back()->with('error', '⚠️ Ce coupon existe déjà et est encore en cours d’utilisation.');
            // }

        try {
      

            // ✅ Création du coupon
            $coupon = Coupon::create([
                'code' => $request->code,
                'nom' => $request->nom,
                'quantite' => $request->quantite,
                'utilisation_max' => $request->utilisation_max,
                'type_remise' => $request->type_remise,
                'valeur_remise' => $request->valeur_remise,
                'montant_min' => $request->montant_min,
                'montant_max' => $request->montant_max ?? 0,
                'date_debut' => $request->date_debut,
                'date_fin' => $request->date_fin,
                'type_coupon' => $request->type_coupon,
                'expiration' => $request->date_fin,
            ]);

            // ✅ Attache les clients sélectionnés
            if ($request->filled('customers')) {
                $coupon->users()->attach($request->customers);
            }

            // ✅ Succès
            return back()->with('success', '✅ Coupon créé avec succès !');
        } catch (\Throwable $th) {
            // ⚠️ Gestion d'erreur
            return back()->with('error', 'Une erreur est survenue : ' . $th->getMessage());
        }
    }


    public function generateCouponPdf($id)
    {
        $coupon = Coupon::findOrFail($id);
        $quantite = $coupon->quantite; // la quantité a generer

        $pdf = PDF::loadView('admin.pages.coupon.couponPdf', compact('coupon', 'quantite'))
            ->setPaper('a4', 'landscape'); // Format carte

        return $pdf->download('coupon-' . $coupon->code . '.pdf');
    }

    public function destroy(string $id)
    {
        //
        Coupon::whereId($id)->delete();
        return response()->json([
            'status' => 200
        ]);
    }
}
