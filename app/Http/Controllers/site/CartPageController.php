<?php

namespace App\Http\Controllers\site;

use Arr;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use Twilio\Rest\Client;
use App\Models\Delivery;
use Twilio\Http\CurlClient;
use Illuminate\Http\Request;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Session;



class CartPageController extends Controller
{

    /**
     * Nettoyer un numéro et le mettre au format WhatsApp
     */
    private function formatWhatsAppNumber($number, $countryCode = '+225')
    {
        // Supprimer tous les caractères non numériques
        $cleaned = preg_replace('/\D+/', '', $number);

        // Si le numéro ne commence pas déjà par l’indicatif, on l’ajoute
        if (strpos($cleaned, ltrim($countryCode, '+')) !== 0) {
            $cleaned = ltrim($countryCode, '+') . $cleaned;
        }

        return "whatsapp:+{$cleaned}";
    }

    //voir le panier
    public function panier()
    {

        $product = Product::withWhereHas('coupon', fn($q) => $q->where('status', 'en_cours'))->get();

        // dd($product->toArray());

        return view('site.pages.panier', compact('product'));
    }


    //Ajouter des produit au panier
    // public function addToCart($id)
    // {

    //     $product = Product::findOrFail($id);
    //     // $product = Product::whereId($id)->withWhereHas('coupon', fn ($q) => $q->where('status', 'en_cours'))->first();

    //     $cart = session()->get('cart', []);

    //     //verifier si le produit a une remise
    //     $price = 0;
    //     if ($product['montant_remise'] != null && $product['status_remise'] == 'en_cours') {
    //         $price = $product['price'] - $product['montant_remise'];
    //     } else {
    //         $price = $product['price'];
    //     }
    //     ##############################

    //     if (isset($cart[$id])) {
    //         $cart[$id]['quantity']++;
    //     } else {
    //         $cart[$id] = [
    //             "id" => $product->id,
    //             "code" => $product->code,
    //             "slug" => $product->slug,
    //             "title" => $product->title,
    //             "quantity" => 1,
    //             "price" => $price,
    //             "image" => $product->media[0]->getUrl(),

    //         ];
    //     }

    //     session()->put('cart', $cart);

    //     //recuperer la quantité total des produit du panier
    //     $countCart = count((array) session('cart'));
    //     $data = Session::get('cart');
    //     $totalQuantity = 0;
    //     foreach ($data as $id => $value) {
    //         $totalQuantity += $value['quantity'];
    //     }

    //     session()->put('totalQuantity', $totalQuantity);



    //     return response()->json([
    //         'countCart' => $countCart,
    //         'cart' => $cart,
    //         'totalQte' => $totalQuantity,


    //     ]);
    // }

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


    public function addToCart($id)
    {
        $product = Product::with('media')->findOrFail($id);

        // Calculer le prix avec ou sans remise
        $hasDiscount = $product->montant_remise && $product->status_remise === 'en_cours';
        $price = $hasDiscount ? $product->price - $product->montant_remise : $product->price;

        $cart = session()->get('cart', []);

        // Ajouter ou mettre à jour le produit dans le panier
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'id'       => $product->id,
                'code'     => $product->code,
                'slug'     => $product->slug,
                'title'    => $product->title,
                'quantity' => 1,
                'price'    => $price,
                'image'    => $product->media->first()?->getUrl() ?? 'default.jpg',
            ];
        }

        // Mise à jour du panier en session
        session()->put('cart', $cart);

        // Calcul du total des quantités
        $totalQuantity = collect($cart)->sum('quantity');
        session()->put('totalQuantity', $totalQuantity);

        return response()->json([
            'countCart' => count($cart),
            'cart'      => $cart,
            'totalQte'  => $totalQuantity,
        ]);
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

    //deduction de la remise du coupon
    public function refreshCoupon($id)
    {

        $total = $_GET['data']['total']; //prix total article
        $sousTotal = $_GET['data']['sous_total'];
        $pourcentage = $_GET['data']['pourcentage'];
        $code_coupon = $_GET['data']['code_coupon'];


        // calculer montant_reduction
        $montant_reduction = $total * ($pourcentage / 100);
        $new_total = $total - $montant_reduction;


        // recalculer le sous total
        $new_sousTotal =   $sousTotal - $new_total;





        //supprimer l'utilisateur qui a utiliser le coupon
        // $coupon = Coupon::whereCode($code_coupon)->first();
        // DB::table('coupon_product')->where('coupon_id', $coupon['id'])
        //     ->where('product_id', $id)->delete();


        // $product = Product::findOrFail($id);

        // $cart[$id]["coupon"] = $product->coupon[0]->code;
        // $cart[$id]["pourcentage_coupon"] = $product->coupon[0]->pourcentage_coupon;
        // $cart[$id]["status"] = $product->coupon[0]->status;
        // session()->put('cart', $cart);

        return response()->json([
            'new_total' => $new_total,
            'new_sousTotal' => $new_sousTotal,
        ]);
    }


    //Verification du coupon entre
    // public function checkCoupon(Request $request, $code)
    // {
    //     // recuperer le montant de la commande en cours
    //     $subTotal = $request->sous_total;
    //     $coupon = Coupon::with('users')->where('code', strtoupper($code)) // Convertir le code en majuscules
    //         ->where('status', 'en_cours')
    //         // ->where('date_expiration', '>=', now()) // Vérifier si le coupon n'est pas expiré
    //         ->first();
    //     if ($coupon) {
    //         // recuperer le montant min et max du coupon
    //         $montant_min = $coupon->montant_min;
    //         $montant_max = $coupon->montant_max;

    //         // dd($subTotal,$montant_min, $montant_max);

    //         // verifier si le montant de la commande respecte les montant du coupon
    //         if ($subTotal < $montant_min) {
    //             return response()->json([
    //                 'coupon' => null,
    //                 'message' => 'Le montant de la commande doit être supérieur ou égal à ' . number_format($montant_min, 0, ',', ' ') . ' FCFA pour utiliser ce coupon.',
    //             ], 403);
    //         }
    //         if ($montant_max > 0 && $subTotal > $montant_max) {
    //             return response()->json([
    //                 'coupon' => null,
    //                 'message' => 'Le montant de la commande doit être inférieur ou égal à ' . number_format($montant_max, 0, ',', ' ') . ' FCFA pour utiliser ce coupon.',
    //             ], 403);
    //         }
    //     }

    //     if (!$coupon) {
    //         return response()->json([
    //             'coupon' => null,
    //             'message' => 'Coupon non trouvé ou expiré',
    //         ], 404);
    //     }

    //     // Vérifier si le coupon a atteint sa limite d'utilisation
    //     if ($coupon->utilisation_max > 0) {
    //         $coupon_use = DB::table('coupon_use')
    //             ->where('coupon_id', $coupon->id)
    //             ->where('user_id', Auth::user()->id)
    //             ->value('use_count');

    //         if ($coupon_use >= $coupon->utilisation_max) {
    //             return response()->json([
    //                 'coupon' => null,
    //                 'message' => 'Ce coupon a atteint sa limite d\'utilisation',
    //             ], 403);
    //         }
    //     }

    //     return response()->json([
    //         'coupon' => $coupon,
    //         'message' => 'Coupon valide',
    //     ], 200);
    // }

    public function checkCoupon(Request $request, $code)
    {
        $subTotal = $request->sous_total;

        // Récupération du coupon avec les utilisateurs associés
        $coupon = Coupon::with('users')
            ->where('code', strtoupper($code)) // Code en majuscules
            ->where('status', 'en_cours')
            // ->where('date_expiration', '>=', now()) // Vérifier si le coupon n'est pas expiré
            ->first();

        if (!$coupon) {
            return response()->json([
                'coupon' => null,
                'message' => 'Coupon non valide ou expiré',
            ], 404);
        }

        // Vérifier le type du coupon (unique ou groupe)
        if ($coupon->type_coupon === 'unique') {
            $userId = Auth::id(); // Récupération de l'ID de l'utilisateur connecté
            $isAssigned = $coupon->users->contains('id', $userId);

            if (!$isAssigned) {
                return response()->json([
                    'coupon' => null,
                    'message' => 'Coupon non valide.',
                ], 403);
            }
        }

        // Vérification des montants min et max
        if ($subTotal < $coupon->montant_min) {
            return response()->json([
                'coupon' => null,
                'message' => 'Le montant de la commande doit être supérieur ou égal à ' . number_format($coupon->montant_min, 0, ',', ' ') . ' FCFA pour utiliser ce coupon.',
            ], 403);
        }
        if ($coupon->montant_max > 0 && $subTotal > $coupon->montant_max) {
            return response()->json([
                'coupon' => null,
                'message' => 'Le montant de la commande doit être inférieur ou égal à ' . number_format($coupon->montant_max, 0, ',', ' ') . ' FCFA pour utiliser ce coupon.',
            ], 403);
        }

        // Vérifier si le coupon a atteint sa limite d'utilisation
        if ($coupon->utilisation_max > 0) {
            $coupon_use = DB::table('coupon_use')
                ->where('coupon_id', $coupon->id)
                ->where('user_id', Auth::id())
                ->value('use_count') ?? 0;

            if ($coupon_use >= $coupon->utilisation_max) {
                return response()->json([
                    'coupon' => null,
                    'message' => 'Ce coupon a atteint sa limite d\'utilisation.',
                ], 403);
            }
        }

        return response()->json([
            'coupon' => $coupon,
            'message' => 'Coupon valide',
        ], 200);
    }



    //caisse--resumé du panier
    public function checkout(Request $request)
    {
        //liste des zone de livraison
        $delivery = Delivery::orderBy('zone', 'ASC')->get();

        //verifier si le client a un coupon associé a u produit de son panier
        $auth_coupon = User::where('id', Auth::user()->id)->withWhereHas('coupon', function ($q) {
            $q->where('status', 'en_cours');
            $q->where('coupon_user.nbre_utilisation', 0);
        })->first();

        $product_coupon = "";
        if (count(Auth::user()->coupon) > 0) {
            $product_coupon = Product::withWhereHas('coupon', fn($q) => $q->where('status', 'en_cours')
                ->whereCode($auth_coupon['coupon'][0]['code']))->get();
        } else {
            $product_coupon = "";
        }


        // dd($product_coupon->toArray());

        // dd(Auth::user()->coupon[0]->pivot->toArray());


        return view('site.pages.caisse', compact('delivery', 'product_coupon'));
    }


    // public function sendWhatsAppNotification($order)
    // {
    //     // Numéro destinataire (client et admin)
    //     $clientPhone = 'whatsapp:+225' . Auth::user()->phone;
    //     // $adminPhone  = 'whatsapp:+2250101010101';

    //     // Identifiants Twilio
    //     $sid    = env('TWILIO_SUBACCOUNT_SID');
    //     $token  = env('TWILIO_SUBACCOUNT_TOKEN');
    //     $mg     = env('TWILIO_MESSAGING_SERVICE_SID'); // ⚠️ Utilise un Messaging Service relié à ton sender WhatsApp
    //     $contentSid = 'HX77c9fe48037b991fdc743eed78ae88a6'; // ton template

    //     if (!$sid || !$token || !$mg) {
    //         return response()->json(['error' => 'Identifiants Twilio manquants !']);
    //     }

    //     $twilio = new \Twilio\Rest\Client($sid, $token);

    //     // $recipients = [$clientPhone , $adminPhone];

    //     try {
    //         // foreach ($recipients as $to) {
    //         //     $twilio->messages->create($to, [
    //         //         'messagingServiceSid' => $mg,
    //         //         'contentSid'          => $contentSid,
    //         //         // pas de contentVariables car message statique
    //         //     ]);
    //         // }

    //         $twilio->messages->create($clientPhone, [
    //             'messagingServiceSid' => $mg,
    //             'contentSid'          => $contentSid,
    //         ]);

    //         return response()->json(['message' => 'Template WhatsApp envoyé avec succès ✅']);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()]);
    //     }
    // }



    public function sendWhatsAppNotification($order)
    {
        $clientName  = Auth::user()->first_name . ' ' . Auth::user()->last_name;
        $clientPhone = $this->formatWhatsAppNumber('0779613593');
        $adminPhone  = $this->formatWhatsAppNumber('0758838338');

        $sid        = env('TWILIO_SUBACCOUNT_SID');
        $token      = env('TWILIO_SUBACCOUNT_AUTH_TOKEN');
        $mg         = env('TWILIO_MESSAGING_SERVICE_SID'); // MG...
        $contentSid = 'HX77c9fe48037b991fdc743eed78ae88a6'; // ton template

        if (!$sid || !$token || !$mg || !$contentSid) {
            return response()->json(['error' => 'Identifiants Twilio manquants !']);
        }

        $twilio = new \Twilio\Rest\Client($sid, $token);

        $recipients = [
            ['name' => $clientName, 'phone' => $clientPhone],
            ['name' => 'Administrateur', 'phone' => $adminPhone],
        ];

        try {
            foreach ($recipients as $recipient) {
                // Variables pour ton template : {{1}} = nom, {{2}} = commande
                $variables = json_encode([
                    "1" => $recipient['name'],
                    "2" => (string)$order->code
                ]);

                $twilio->messages->create($recipient['phone'], [
                    'messagingServiceSid' => $mg,
                    'contentSid'          => $contentSid,
                    'contentVariables'    => $variables
                ]);
            }

            return response()->json(['message' => 'Template WhatsApp avec variables envoyé avec succès ✅']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }





    //store order---- enregistrer la commande de l'utilisateur
    public function storeOrder(Request $request)
    {
        if (Auth::check()) {

            if (session('cart')) {

                //informations depuis le front ajax
                $subTotal = $_GET['data']['subTotal'];
                $delivery_name = $_GET['data']['lieu_livraison'];
                $delivery_price = $_GET['data']['prix_livraison'];
                $delivery_mode = $_GET['data']['delivery_mode'];
                $address = $_GET['data']['address'];
                $address_yango = $_GET['data']['address_yango'];
                $note = $_GET['data']['note'];
                $type_commande = $_GET['data']['type_commande'];
                $delivery_planned = $_GET['data']['delivery_planned'];
                $code_promo = $_GET['data']['code_promo'];
                $discount = $_GET['data']['remise'] ?? 0; //remise
                //Id du coupon si il y en a
                $coupon_id = $_GET['data']['coupon_id'] ?? null;




                //calculer le total TTC
                $totalOrder =
                    $subTotal + $delivery_price;

                //quantité des produit au panier
                $quantity_product = count((array) session('cart'));

                //status commande
                $status = "";
                if ($type_commande == 'cmd_precommande') {
                    $status = "precommande";
                } else {
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

                    'discount' => $discount, //remise
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


                //si il ya une remise une remise on met a jour la table coupon_use
                if (!empty($coupon_id)) {
                    $couponUse = DB::table('coupon_use')
                        ->where('user_id', Auth::id())
                        ->where('coupon_id', $coupon_id)
                        ->first();

                    if ($couponUse) {
                        // Si l'utilisateur a déjà utilisé le coupon, on incrémente `count_use`
                        DB::table('coupon_use')
                            ->where('user_id', Auth::id())
                            ->where('coupon_id', $coupon_id)
                            ->increment('use_count');
                    } else {
                        // Sinon, on insère une nouvelle ligne avec count_use = 1
                        DB::table('coupon_use')->insert([
                            'user_id' => Auth::id(),
                            'coupon_id' => $coupon_id,
                            'use_count' => 1, // Premier usage
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }



                //function for update code promo
                if (isset($code_promo)) {
                    $code = Coupon::whereCode($code_promo)->first();
                    if ($code) {
                        DB::table('coupon_user')
                            ->where('user_id', Auth::user()->id)
                            ->where('coupon_id',  $code['id'])
                            ->update(['nbre_utilisation' => 1]);
                    }
                }


                // Envoyer la notification whatsapp au client
                $this->sendWhatsAppNotification($order);




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
