<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

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
            ->orderBy('created_at', 'DESC')->paginate(8);
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


    //Suivi de commande
    public function trackingOrder($code)
    {
       try {
            $order = Order::where('code', $code)->with(['user', 'products' => fn ($q) => $q->with('media')])->firstOrFail();
        } catch (\Exception $e) {
            Alert::error('Commande non trouvée.');
             return redirect()->route('page-acceuil');
        }

          
        
        return view('site.pages.auth.suivi-commande', compact('order'));
    }

    //Profil 
    public function profil(){
        return view('site.pages.auth.account.profil');
    }
}
