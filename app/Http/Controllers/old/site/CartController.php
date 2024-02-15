<?php

namespace App\Http\Controllers\site;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Delivery;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    //view cart
    public function cart()
    {
        return view('site.pages.cart');
    }

    //Add to cart
    public function addToCart($id)
    {
        $options = '';
        if (isset($_GET['options'])) {
            $options = $_GET['options'];
        } else {
            $options = '';
        }


        $product = Product::findOrFail($id);

        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "id" => $product->id,
                "code" => $product->code,
                "title" => $product->title,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->media[0]->getUrl(),
                "options" => $options,

            ];
        }

        session()->put('cart', $cart);
        //quantity product of cart
        $countCart = count((array) session('cart'));

        return response()->json([
            'countCart' => $countCart,
            'cart' => $cart,
        ]);
    }


    //update cart
    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
        }
    }


    //remove product from cart
    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
        }
        return response()->json([
            'id' => $request->id,
            'message' => 'produit delete',
            'url' => '/cart',

        ]);
    }


    //checkout
    public function checkout()
    {
        if (session('cart')) {
            if (Auth::check()) {
                $carts = session()->get('cart');
                $total_price = 0;
                foreach ($carts as $item) {
                    $total_price += $item['price'] * $item['quantity'];
                }
                $delivery = Delivery::all();

                return view('site.pages.checkout', compact('carts', 'total_price', 'delivery'));
                // return redirect()->to('/checkout')->with(['carts' => $carts, 'total_price' => $total_price]);
            }
        } else {
            return redirect('/my-account');
        }
    }



    //refresh shipping
    public function refreshShipping($id)
    {

        $sub_total = $_GET['sub_total'];
        $delivery = Delivery::whereId($id)->first();
        $delivery_name = $delivery['zone'];
        $delivery_price = $delivery['tarif'];

        $total_price =  $sub_total +  $delivery_price;

        //html format replace 
        // $total_price = '<p class="mb-0 total_price">' . number_format($total_price, 0) . ' FCFA </p>';
        // $delivery_price = ' <p class="mb-0 delivery_price">' . number_format($delivery_price, 0) . ' FCFA</p>';
        // $delivery_name =  '<span class="delivery">' . $delivery_name . '</span>';
        return response()->json([
            'delivery_price' => number_format($delivery_price, 0) . ' FCFA',
            'total_price' => number_format($total_price, 0) . ' FCFA',
            'delivery_name' => $delivery_name,

        ]);
    }


    //store order
    public function storeOrder(Request $request)
    {
        if (Auth::check()) {
            if (session('cart')) {

                //data from view ajax
                $subTotal = $_GET['data']['subTotal'];
                $delivery_name = $_GET['data']['delivery_name'];
                $delivery_price = $_GET['data']['delivery_price'];
                $total_price = $_GET['data']['total_price'];

                //quantity product
                $quantity_product = count((array) session('cart'));

                $order = Order::firstOrCreate([
                    // 'code' => 'CM00' . mt_rand(1, 9),
                    "user_id" => Auth::user()->id,
                    'quantity_product' => $quantity_product,
                    'subtotal' => $subTotal,
                    'total' => $total_price,
                    'delivery_price' => $delivery_price,
                    'delivery_name' =>   $delivery_name,
                    // 'discount' => '',
                    'delivery_planned' => Carbon::now()->addDay(3), //date de livraison prevue
                    // 'delivery_date' => '', //date de livraison
                    'status' => 'attente',         // livré, en attente
                    // 'available_product' =>  '' //disponibilite
                    'payment method' => 'paiement à la livraison',
                    'date_order' =>Carbon::now()->format('Y-m-d')


                ]);

                //insert data in pivot order_product
                foreach (session('cart') as $key => $value) {
                    $order->products()->attach($key, [
                        'quantity' => $value['quantity'],
                        'unit_price' => $value['price'],
                        'total' => $value['price'] * $value['quantity'],
                        'options' => $value['options'] ? implode(", ", $value['options']) : '',
                    ]);
                }

                //destroy cart session
                Session::forget('cart');


                return response()->json([
                    'status' => 200,
                    'url' => '/my-order',

                ]);
            }
        }
    }
}
