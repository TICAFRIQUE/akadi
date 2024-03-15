<?php

namespace App\Http\Controllers\site;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccountPageController extends Controller
{

    //Liste des commande de l'utilisateur
    public function userOrder(Request $request)
    {
        $orders = Order::where('user_id', Auth::user()->id)
            ->with(['user',
                'products'
                => fn ($q) => $q->with('media')
            ])
            ->orderBy('created_at', 'DESC')->get();
        return view('site.pages.auth.mes-commandes', compact('orders'));
    }

//Annuler une commande
    public function CancelOrder(Request $request , $id)
    {
        $request->validate([
            'motif'=>'required'
        ]);
       
        $cancelOrder = Order::whereId($id)->update([
            'status' => 'annulée',
            'raison_annulation_cmd' => $request['motif']

        ]);

        return back()->withSuccess('Votre commande à été annulée');
    }


    //Profil 
    public function profil(){
        return view('site.pages.auth.account.profil');
    }
}
