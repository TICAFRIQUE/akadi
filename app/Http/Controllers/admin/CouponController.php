<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
                fn ($q) => $q->where('name', '!=', 'developpeur')
            )
            ->orderBy('created_at', 'DESC')->get();

        return view('admin.pages.coupon.add',  compact('product', 'customer'));
    }



    public function store(Request $request)
    {
        //    dd($request->toArray());

        $coupon = Coupon::firstOrCreate([
            'code' => $request['code'],

            // 'montant_coupon' => $request['montant_coupon'],
            'pourcentage_coupon' => $request['pourcentage_coupon'],
            'date_debut_coupon' => $request['date_debut_coupon'],
            'date_fin_coupon' => $request['date_fin_coupon'],
            'status_coupon' => '',
        ]);



        if ($request['customers']) {
            $coupon->users()->attach($request['customers']);
        }

        if ($request['products']) {

            $coupon->products()->attach($request['products']);
        }

        // if ($request['customers']) {
        //     # code...
        // }

        return back()->withSuccess('Coupon crée avec success');
    }


    public function destroy(string $id)
    {
        //
        Product::whereId($id)->delete();
        return response()->json([
            'status' => 200
        ]);
    }

}
