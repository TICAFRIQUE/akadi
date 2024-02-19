<?php

namespace App\Http\Controllers\site;

use App\Models\Product;
use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CartPageController extends Controller
{
    //voir le panier
    public function panier()
    {

        return view('site.pages.panier');
    }


    //Ajouter des produit au panier
    public function addToCart($id)
    {

        $product = Product::findOrFail($id);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "id" => $product->id,
                "code" => $product->code,
                "slug" => $product->slug,
                "title" => $product->title,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->media[0]->getUrl(),
            ];
        }

        session()->put('cart', $cart);

        //recuperer la quantité total des produit du panier
        $countCart = count((array) session('cart'));
        $data = Session::get('cart');
        $totalQuantity = 0;
        foreach ($data as $id => $value) {
            $totalQuantity += $value['quantity'];
        }

        session()->put('totalQuantity', $totalQuantity);




        return response()->json([
            'countCart' => $countCart,
            'cart' => $cart,
            'totalQte' => $totalQuantity,

        ]);
    }

    //modifier et mettre à jour le panier
    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);


            $totalQuantity = 0;
            $sousTotal = 0;
            foreach ($cart as $value) {
                $totalQuantity += $value['quantity'];
                $sousTotal += $value['quantity'] * $value['price'];
            }

            session()->put('totalQuantity', $totalQuantity);

            return response()->json([
                'cart' => session()->get('cart'),
                'totalQte' => $totalQuantity,
                "sousTotal" => number_format($sousTotal),
            ]);
        }
    }


    //Supprimer un produit du panier
    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }

            $totalQuantity = 0;
            $sousTotal = 0;
            foreach ($cart as $value) {
                $totalQuantity += $value['quantity'];
                $sousTotal += $value['quantity'] * $value['price'];
            }

            session()->put('totalQuantity', $totalQuantity);
            $countCart = count((array) session('cart'));
        }
        return response()->json([
            'id' => $request->id,
            'message' => 'produit delete',
            'url' => '/panier',
            'cart' => session()->get('cart'),
            'totalQte' => $totalQuantity,
            'countCart' => $countCart,
            "sousTotal" => number_format($sousTotal),
        ]);
    }


    //recuperer le prix du lieu de livraison par get AJax
    public function refreshShipping($id)
    {

        $sub_total = $_GET['sub_total'];
        $delivery = Delivery::whereId($id)->first();
        $delivery_name = $delivery['zone'];
        $delivery_price = $delivery['tarif'];

        $total_price =  $sub_total +  $delivery_price;

        return response()->json([
            'delivery_price' => number_format($delivery_price, 0) ,
            'total_price' => number_format($total_price, 0) ,
            'delivery_name' => $delivery_name,

        ]);
    }


    public function checkout(){
        $delivery = Delivery::orderBy('zone', 'ASC')->get();

        return view('site.pages.caisse', compact('delivery'));
    }
}
