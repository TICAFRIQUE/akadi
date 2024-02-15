<?php

namespace App\Http\Controllers\site;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CartPageController extends Controller
{
    //voir le panier
    public function panier(){
        return view( 'site.pages.panier');
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
            $totalQuantity +=$value['quantity'];
        }

        session()->put('totalQuantity', $totalQuantity);


        

        return response()->json([
            'countCart' => $countCart,
            'cart' => $cart,
            'totalQte' => $totalQuantity,

        ]);
    }
}
