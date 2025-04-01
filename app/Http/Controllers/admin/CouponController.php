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


    public function store(Request $request)
    {
        dd($request->toArray());

        // gestion validation
        $request->validate([
            'code' => 'required',
            'nom' => 'required',
            'quantite' => 'required',
            'utilisation_max' => 'required',
            'type_remise' => 'required',
            'valeur_remise' => 'required',
            'montant_min' => 'required',
            'montant_max' => '',
            'date_debut' => 'required',
            'date_fin' => 'required',
        ]);

        $coupon = Coupon::firstOrCreate([
            'code' => $request['code'],
            'nom' => $request['nom'],
            'quantite' => $request['quantite'],
            'utilisation_max' => $request['utilisation_max'],
            'type_remise' => $request['type_remise'],
            'valeur_remise' => $request['valeur_remise'],
            'montant_min' => $request['montant_min'],
            'montant_max' => $request['montant_max'] ??  $request['montant_min'],
            'date_debut' => $request['date_debut'],
            'date_fin' => $request['date_fin'],
            'type_coupon' => $request['type_coupon'],
            'expiration' => $request['date_fin'],
        ]);





        // if ($request['customers']) {
        //     $coupon->users()->attach($request['customers']);
        // }

        // if ($request['products']) {

        //     $coupon->products()->attach($request['products']);
        // }

        // if ($request['customers']) {
        //     # code...
        // }

        return back()->withSuccess('Coupon crée avec success');
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
