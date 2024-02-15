<?php

namespace App\Http\Controllers\site;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    //
    // get vendor order

    public function vendorOrder()
    {

        $orders = Order::withWhereHas(
                'products',
                fn ($q) => $q->with('media')->where('user_id', Auth::user()->id)
            )
            ->orderBy('created_at', 'DESC')->get();
        // dd($orders->toArray());
        return view('site.pages.user-account.vendor.vendor_order', compact('orders'));
    }

    public function vendorOrderDetail($id)
    {

        $orders = Order::whereId($id)
            ->with([
                'user', 'products'
                => fn ($q) => $q->with('media')->where('user_id', Auth::user()->id)
            ])
            ->orderBy('created_at', 'DESC')->first();
        // dd($orders->toArray());
        return view('site.pages.user-account.vendor.vendor-detail-order', compact('orders'));
    }


    //change state available 
    public function vendorAvailable(Request $request, $id)
    {
        
        DB::table('order_product')->where('order_id', $id)->where('product_id', $request['product_id'])
            ->update([
                'available' => $request['state']
            ]);

            return back()->withSuccess('Produit ' .$request['state']);
    }
}
