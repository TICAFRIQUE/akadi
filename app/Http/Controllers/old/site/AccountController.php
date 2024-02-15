<?php

namespace App\Http\Controllers\site;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{

    //user account
    public function account()
    {
        if (Auth::check()) {
            //statistic of vendor 
            $orders = Order::withWhereHas(
                'products',
                fn ($q) => $q->with('media')->where('user_id', Auth::user()->id)
            )
                ->orderBy('created_at', 'DESC')->get();

            $orders_attente = Order::withWhereHas(
                'products',
                fn ($q) => $q->with('media')->where('user_id', Auth::user()->id)
                    ->where('available', null)
            )
                ->orderBy('created_at', 'DESC')->count();


            return view('site.pages.user-account.dashboard', compact('orders', 'orders_attente'));
        } else {
            return redirect()->route('login-form');
        }
    }

    
    // profile user
    public function profile(Request $request, $id)
    {

        if (request()->method() == 'GET') {

            $user = User::findOrFail($id)->first();
            return view('site.pages.user-account.profile', compact('user'));
        } elseif (request()->method() == 'POST') {
            // dd($request);
            $url = $request['url_previous'];

            $new_password = '';
            if ($request->has('password')) {
                $new_password = $request['password'];
            }

            $user = User::whereId($id)->update([
                'name' => $request['name'],
                'phone' => $request['phone'],
                'email' => $request['email'],
                'shop_name' => $request['shop_name'],
                'localisation' => $request['localisation'],
                'password' => Hash::make($new_password),



            ]);
            return redirect()->to($url)->withSuccess('Modification reussie');
        }
    }


    // list order user
    public function order()
    {

        $orders = Order::where('user_id', Auth::user()->id)
            ->with([
                'products'
                => fn ($q) => $q->with('media')
            ])
            ->orderBy('created_at', 'DESC')->get();

        // dd($orders->toArray());
        return view('site.pages.user-account.order', compact('orders'));
    }


    // detail of order user
    public function orderDetail($id)
    {

        $orders = Order::whereId($id)
            ->with([
                'user', 'products'
                => fn ($q) => $q->with('media')
            ])
            ->orderBy('created_at', 'DESC')->first();
        // dd($orders->toArray());
        return view('site.pages.user-account.detail-order', compact('orders'));
    }
}
