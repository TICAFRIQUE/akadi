<?php

namespace App\Http\Controllers\site;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Delivery;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Arr;

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

        //verifier si le produit a une remise
        $price = 0;
        if ($product['montant_remise'] != null && $product['status_remise'] == 'en cour') {
            $price = $product['price'] - $product['montant_remise'];
        } else {
            $price = $product['price'];
        }
        ##############################

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "id" => $product->id,
                "code" => $product->code,
                "slug" => $product->slug,
                "title" => $product->title,
                "quantity" => 1,
                "price" => $price,
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
            "sousTotal" => number_format($sousTotal)
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
            'delivery_price' => $delivery_price,
            'total_price' => number_format($total_price, 0, ',', ' '),
            'delivery_name' => $delivery_name,

        ]);
    }

    //caisse--resumé du panier
    public function checkout()
    {
        $delivery = Delivery::orderBy('zone', 'ASC')->get();

        return view('site.pages.caisse', compact('delivery'));
    }


    //store order
    public function storeOrder(Request $request)
    {
        if (Auth::check()) {

            if (session('cart')) {

                //informations depuis ajax
                $subTotal = $_GET['data']['subTotal'];
                $delivery_name = $_GET['data']['lieu_livraison'];
                $delivery_price = $_GET['data']['prix_livraison'];
                $delivery_mode = $_GET['data']['delivery_mode'];
                $address = $_GET['data']['address'];
                $address_yango = $_GET['data']['address_yango'];
                $note = $_GET['data']['note'];
                $type_commande = $_GET['data']['type_commande'];
                $delivery_planned = $_GET['data']['delivery_planned'];



                //calculer le total TTC
                $totalOrder =
                    $subTotal + $delivery_price;

                //quantité des produit au panier
                $quantity_product = count((array) session('cart'));

                //status commande
                $status = "";
               if( $type_commande == 'cmd_precommande'){
                $status = "precommande";
               }else{
                    $status = "attente";

               }

                //enregistrer la commande
                $order = Order::firstOrCreate([
                    "user_id" => Auth::user()->id,
                    'quantity_product' => $quantity_product,
                    'subtotal' => $subTotal,
                    'total' => $totalOrder,
                    'delivery_name' =>   $delivery_name,
                    'delivery_price' => $delivery_price,
                    'address' => $address,
                    'address_yango' => $address_yango,
                    'mode_livraison' => $delivery_mode,
                    'note' => $note,
                    'type_order' => $type_commande,


                    // 'discount' => '',
                    'delivery_planned' => $delivery_planned, //date de livraison prevue
                    // 'delivery_date' => '', //date de livraison
                    'status' => $status,         // livré, en attente
                    // 'available_product' =>  '' //disponibilite
                    'payment method' => 'paiement à la livraison',
                    'date_order' => Carbon::now()->format('Y-m-d')
                ]);

                //insert data in pivot order_product

                foreach (session('cart') as $key => $value) {
                    $order->products()->attach($key, [
                        'quantity' => $value['quantity'],
                        'unit_price' => $value['price'],
                        'total' => $value['price'] * $value['quantity'],
                    ]);
                }


                $orders = Order::whereId($order['id'])
                    ->with([
                        'user', 'products'
                        => fn ($q) => $q->with('media')
                    ])
                    ->orderBy('created_at', 'DESC')->first();

                $data_products = [];

                // $product = json_encode($data_products);

                foreach ($orders['products']  as $key => $value) {
                    $name = $value['title'];
                    $qte = $value['pivot']['quantity'];
                    $price = $value['pivot']['unit_price'];
                    $total = $value['pivot']['quantity'] * $value['pivot']['unit_price'];

                    array_push($data_products, ['name' => $name, 'qte' => $qte, 'price' => $price, 'total' => $total]);
                }

                foreach ($data_products  as $key => $value) {
                }

                // return response()->json([
                //     'message' => $data_products
                // ]);



                // return PDF::loadView(
                //     'admin.pages.order.invoicePdf',
                //     compact('orders')
                // )
                //     ->setPaper('a5', 'portrait')
                //     ->setWarnings(true)
                //     ->save(public_path("storage/" . $orders['id'] . ".pdf"));


                //new send mail with phpMailer
                $mail = new PHPMailer(true);
                // require base_path("vendor/autoload.php");

                /* Email SMTP Settings */
                $mail->SMTPDebug = 0;
                $mail->isSMTP();
                $mail->Host = 'mail.akadi.ci';
                $mail->SMTPAuth = true;
                $mail->Username = 'info@akadi.ci';
                $mail->Password = 'S$UBfu.8s(#z';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                $mail->setFrom('info@akadi.ci', 'info@akadi.ci');
                $mail->addAddress('ane.assbill@gmail.com');

                $mail->isHTML(true);


                $mail->Subject = 'Nouvelle commande';

                $mail->Body    =
                    '<b> Vous avez reçu une nouvelle
                                    <br> 
                                    <h3 style="text-align:center ; margin-bottom:30px"> <u>Detail de la commande</u> </h3>
                                    <div style="margin-bottom: 10px">
                                        <p>Nom du client : ' . Auth::user()->name . '</p>
                                        <p>Contact du client : ' . Auth::user()->phone . '</p>
                                       
                                    </div>

                                    </b>';
                foreach ($data_products  as $key => $value) {
                    $mail->Body .=

                        ' 
                         <div margin-bottom:10px>
                     <b> Produit ' . ++$key . ' <div>
                    <p>Nom : ' . $value['name'] . '</p>
                     <p>qte : ' . $value['qte'] . '</p>
                     <p>prix : ' . $value['price'] . '</p>
                    </div>
                    <br>
                                        ';
                }
                $mail->Body .= '
                  <b> 
                        <p>Total : ' . $order['total'] . '</p>
                        <p>Mode de livraison : ' . $order['mode_livraison'] . '</p>
                        <p>Adresse: ' . $order['address'] . '</p>
                        <p>Adresse de yango: ' . $order['address_yango'] . '</p>
                        <p>Note de la commande: ' . $order['note'] . '</p>


                 <a href="' . env('APP_URL') . '/admin/order?s=attente">Voir les commandes en attente</a>


                 <b>
                
                ';




                // $mail->addAttachment("storage/" . $orders['id'] . ".pdf");
                $mail->send();


                // Mail::send('site.pages.auth.email.email_register', ['user' => Auth::user()->name], function ($message) use ($request) {
                //     $message->to('alexkouamelan96@gmail.com');
                //     $message->subject('Création de compte');
                // });


                //supprimer la session du panier
                Session::forget('cart');
                Session::forget('totalQuantity');




                return response()->json([
                    'message' => 'commande enregistrée avec success',
                    'status' => 200,
                ], 200);
            }
        }
    }
}
