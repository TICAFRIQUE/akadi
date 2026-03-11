<?php

namespace App\Http\Controllers\site;

use Exception;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\WavePaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    protected $waveService;

    public function __construct(WavePaymentService $waveService)
    {
        $this->waveService = $waveService;
    }

    /**
     * Afficher la page de sélection du moyen de paiement
     */
    public function selectPaymentMethod(Request $request)
    {
        // Récupérer les méthodes de paiement actives
        $paymentMethods = PaymentMethod::actif()->get();

        // Vérifier si l'utilisateur a des articles dans le panier
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('panier')->with('error', 'Votre panier est vide');
        }

        // Calculer le total du panier
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Récupérer les informations de livraison si disponibles
        $deliveryInfo = Session::get('delivery_info');
        $deliveryPrice = $deliveryInfo['price'] ?? 0;
        $total = $subtotal + $deliveryPrice;

        return view('site.pages.payment-selection', compact('paymentMethods', 'subtotal', 'deliveryPrice', 'total'));
    }

    /**
     * Traiter le paiement selon la méthode choisie
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

        // Si c'est un paiement en espèces, créer la commande directement
        if ($paymentMethod->code === 'cash' || $paymentMethod->code === 'espece') {
            return $this->processCashPayment($request);
        }

        // Si c'est Wave, rediriger vers Wave
        if ($paymentMethod->code === 'wave') {
            return $this->processWavePayment($request);
        }

        return back()->with('error', 'Méthode de paiement non supportée');
    }

    /**
     * Traiter le paiement en espèces
     */
    protected function processCashPayment(Request $request)
    {
        try {
            // Créer la commande avec paiement en espèces
            $cart = Session::get('cart', []);
            $deliveryInfo = Session::get('delivery_info');

            Log::info('Processing Cash Payment', [
                'user_id' => Auth::id(),
                'has_cart' => !empty($cart),
                'has_delivery_info' => !empty($deliveryInfo),
                'cart_count' => count($cart)
            ]);

            if (empty($cart)) {
                Log::warning('Cash Payment - Empty Cart', ['user_id' => Auth::id()]);
                return redirect()->route('panier')->with('error', 'Votre panier est vide');
            }

            if (empty($deliveryInfo)) {
                Log::warning('Cash Payment - Missing Delivery Info', [
                    'user_id' => Auth::id(),
                    'session_keys' => array_keys(Session::all())
                ]);
                return redirect()->route('checkout')->with('error', 'Veuillez remplir les informations de livraison');
            }

            // Calculer les totaux
            $subtotal = $deliveryInfo['subTotal'];
            $quantityTotal = count($cart);
            $deliveryPrice = $deliveryInfo['price'];
            $total = $subtotal + $deliveryPrice;

            // Déterminer le statut
            $status = $deliveryInfo['type_commande'] == 'cmd_precommande' 
                ? Order::STATUS_PRECOMMANDE 
                : Order::STATUS_ATTENTE;

            // Créer la commande
            $order = Order::create([
                'user_id' => Auth::id(),
                'quantity_product' => $quantityTotal,
                'subtotal' => $subtotal,
                'total' => $total,
                'delivery_price' => $deliveryPrice,
                'delivery_name' => $deliveryInfo['name'],
                'address' => $deliveryInfo['address'],
                'address_yango' => $deliveryInfo['address_yango'],
                'mode_livraison' => $deliveryInfo['mode'],
                'note' => $deliveryInfo['note'],
                'type_order' => $deliveryInfo['type_commande'],
                'discount' => $deliveryInfo['discount'],
                'delivery_planned' => $deliveryInfo['delivery_planned'],
                'status' => $status,
                'payment_method_id' => $request->payment_method_id,
                'payment_status' => 'completed',
                'date_order' => now()->format('Y-m-d'),
            ]);

            // Attacher les produits à la commande
            foreach ($cart as $productId => $item) {
                $order->products()->attach($productId, [
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);
            }

            // Gérer le coupon si présent
            $this->handleCoupon($deliveryInfo);

            // Vider le panier et les infos de livraison
            Session::forget('cart');
            Session::forget('delivery_info');
            Session::forget('totalQuantity');

            return redirect()->route('order.success', $order->id)
                ->with('success', 'Votre commande a été enregistrée avec succès');

        } catch (Exception $e) {
            Log::error('Cash Payment Error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Une erreur est survenue lors de la création de la commande');
        }
    }

    /**
     * Traiter le paiement Wave
     */
    protected function processWavePayment(Request $request)
    {
        try {
            $cart = Session::get('cart', []);
            $deliveryInfo = Session::get('delivery_info');

            if (empty($cart)) {
                return redirect()->route('panier')->with('error', 'Votre panier est vide');
            }

            if (empty($deliveryInfo)) {
                return redirect()->route('checkout')->with('error', 'Veuillez remplir les informations de livraison avant de procéder au paiement');
            }

            // Calculer les totaux
            $subtotal = $deliveryInfo['subTotal'];
            $quantityTotal = count($cart);
            $deliveryPrice = $deliveryInfo['price'];
            $total = $subtotal + $deliveryPrice;

            // Déterminer le statut
            $status = $deliveryInfo['type_commande'] == 'cmd_precommande' 
                ? Order::STATUS_PRECOMMANDE 
                : Order::STATUS_ATTENTE;

            // CRÉER LA COMMANDE IMMÉDIATEMENT (comme avant)
            $order = Order::create([
                'user_id' => Auth::id(),
                'quantity_product' => $quantityTotal,
                'subtotal' => $subtotal,
                'total' => $total,
                'delivery_price' => $deliveryPrice,
                'delivery_name' => $deliveryInfo['name'],
                'address' => $deliveryInfo['address'],
                'address_yango' => $deliveryInfo['address_yango'],
                'mode_livraison' => $deliveryInfo['mode'],
                'note' => $deliveryInfo['note'],
                'type_order' => $deliveryInfo['type_commande'],
                'discount' => $deliveryInfo['discount'],
                'delivery_planned' => $deliveryInfo['delivery_planned'],
                'status' => $status,
                'payment_method_id' => $request->payment_method_id,
                'payment_status' => 'pending', // En attente de confirmation Wave
                'date_order' => now()->format('Y-m-d'),
            ]);

            // Attacher les produits à la commande
            foreach ($cart as $productId => $item) {
                $order->products()->attach($productId, [
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total' => $item['price'] * $item['quantity'],
                ]);
            }

            // Gérer le coupon si présent
            $this->handleCoupon($deliveryInfo);

            // Créer une session de paiement Wave
            $waveResponse = $this->waveService->createCheckoutSession(
                $total,
                'XOF',
                'ORDER_' . $order->id
            );

            if (!$waveResponse['success']) {
                // Supprimer la commande en cas d'erreur
                $order->delete();
                
                $errorMessage = $waveResponse['error'] ?? 'Erreur inconnue';
                return back()->with('error', 'Impossible d\'initialiser le paiement Wave: ' . $errorMessage);
            }

            // Stocker le session_id Wave dans la commande
            $order->update([
                'wave_session_id' => $waveResponse['session_id']
            ]);

            // Vider le panier
            Session::forget(['cart', 'delivery_info']);

            Log::info('Wave Payment Initiated', [
                'order_id' => $order->id,
                'wave_session_id' => $waveResponse['session_id'],
                'user_id' => Auth::id()
            ]);

            // Rediriger vers Wave
            return redirect()->away($waveResponse['wave_launch_url']);

        } catch (Exception $e) {
            Log::error('Wave Payment Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            return back()->with('error', 'Une erreur est survenue lors de l\'initialisation du paiement: ' . $e->getMessage());
        }
    }

    /**
     * Webhook Wave - Réception des notifications de paiement
     */
    public function waveWebhook(Request $request)
    {
        try {
            // TOUJOURS logger ce qui est reçu (pour déboguer)
            Log::info('Wave Webhook Received', [
                'method' => $request->method(),
                'headers' => $request->headers->all(),
                'raw_body' => $request->getContent(),
                'parsed_payload' => $request->all(),
                'query_params' => $request->query(),
            ]);

            // Récupérer les données du webhook
            $payload = $request->all();
            
            // Wave peut envoyer les données de différentes manières
            // Format 1: {"id": "xxx", "status": "completed"}
            // Format 2: {"data": {"id": "xxx", "status": "completed"}}
            // Format 3: {"event": "checkout.completed", "data": {...}}
            
            $sessionId = null;
            $status = null;
            
            // Essayer d'extraire l'ID et le statut
            if (isset($payload['id'])) {
                $sessionId = $payload['id'];
            } elseif (isset($payload['data']['id'])) {
                $sessionId = $payload['data']['id'];
            } elseif (isset($payload['checkout_session_id'])) {
                $sessionId = $payload['checkout_session_id'];
            }
            
            if (isset($payload['status'])) {
                $status = $payload['status'];
            } elseif (isset($payload['data']['status'])) {
                $status = $payload['data']['status'];
            } elseif (isset($payload['event'])) {
                // Déduire le statut depuis l'event
                if (strpos($payload['event'], 'completed') !== false) {
                    $status = 'completed';
                } elseif (strpos($payload['event'], 'failed') !== false) {
                    $status = 'failed';
                } elseif (strpos($payload['event'], 'cancelled') !== false) {
                    $status = 'cancelled';
                }
            }
            
            // Si on n'a toujours pas les données nécessaires
            if (!$sessionId || !$status) {
                Log::warning('Wave Webhook - données manquantes ou format inconnu', [
                    'payload' => $payload,
                    'session_id_found' => $sessionId,
                    'status_found' => $status
                ]);
                
                // Retourner 200 pour éviter que Wave réessaie en boucle
                // mais logger qu'on n'a pas pu traiter
                return response()->json([
                    'status' => 'received',
                    'message' => 'Webhook received but could not process'
                ], 200);
            }

            Log::info('Wave Webhook Processing', [
                'session_id' => $sessionId,
                'status' => $status
            ]);

            // Chercher la commande par wave_session_id
            $order = Order::where('wave_session_id', $sessionId)->first();

            if (!$order) {
                Log::error('Wave Webhook - commande introuvable', [
                    'session_id' => $sessionId,
                    'searched_in_db' => true
                ]);
                
                // Retourner 200 même si commande introuvée (éviter les retry infinis)
                return response()->json([
                    'status' => 'received',
                    'message' => 'Order not found but webhook acknowledged'
                ], 200);
            }

            // Traiter selon le statut
            switch (strtolower($status)) {
                case 'complete':
                case 'completed':
                case 'success':
                case 'succeeded':
                    // METTRE À JOUR la commande existante
                    $order->update([
                        'payment_status' => 'completed',
                        'acompte' => $order->total,
                        'solde_restant' => 0,
                        'status' => Order::STATUS_ATTENTE, // Changer de "attente" à "confirmée" si nécessaire
                        'payment_completed_at' => now(),
                    ]);
                    
                    Log::info('Wave Payment Completed - Order Updated', [
                        'order_id' => $order->id,
                        'order_code' => $order->code,
                        'session_id' => $sessionId,
                        'amount_paid' => $order->total,
                        'user_id' => $order->user_id
                    ]);

                    // Envoyer les notifications
                    $this->sendOrderNotifications($order);
                    break;

                case 'failed':
                case 'failure':
                    $order->update([
                        'payment_status' => 'failed',
                        'status' => Order::STATUS_ANNULEE
                    ]);
                    
                    Log::info('Wave Payment Failed', [
                        'order_id' => $order->id,
                        'session_id' => $sessionId
                    ]);
                    break;
                    
                case 'cancelled':
                case 'canceled':
                    $order->update([
                        'payment_status' => 'cancelled',
                        'status' => Order::STATUS_ANNULEE
                    ]);
                    
                    Log::info('Wave Payment Cancelled', [
                        'order_id' => $order->id,
                        'session_id' => $sessionId
                    ]);
                    break;

                default:
                    Log::warning('Wave Webhook - statut inconnu', [
                        'status' => $status,
                        'session_id' => $sessionId,
                        'order_id' => $order->id
                    ]);
            }

            // TOUJOURS retourner 200 pour confirmer la réception
            return response()->json([
                'status' => 'success',
                'order_id' => $order->id,
                'message' => 'Webhook processed successfully'
            ], 200);

        } catch (Exception $e) {
            Log::error('Wave Webhook Exception', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_body' => $request->getContent()
            ]);
            
            // Même en cas d'erreur, retourner 200 pour éviter les retry infinis
            return response()->json([
                'status' => 'error',
                'message' => 'Webhook received but processing failed'
            ], 200);
        }
    }

    /**
     * Envoyer les notifications de commande (email, WhatsApp)
     */
    protected function sendOrderNotifications($order)
    {
        try {
            // TODO: Envoyer email à l'admin
            // TODO: Envoyer WhatsApp au client
            
            Log::info('Order notifications sent', ['order_id' => $order->id]);
        } catch (Exception $e) {
            Log::error('Error sending notifications', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Gérer l'utilisation du coupon
     */
    protected function handleCoupon($deliveryInfo)
    {
        if (!empty($deliveryInfo['coupon_id'])) {
            $couponUse = DB::table('coupon_use')
                ->where('user_id', Auth::id())
                ->where('coupon_id', $deliveryInfo['coupon_id'])
                ->first();

            if ($couponUse) {
                DB::table('coupon_use')
                    ->where('user_id', Auth::id())
                    ->where('coupon_id', $deliveryInfo['coupon_id'])
                    ->increment('use_count');
            } else {
                DB::table('coupon_use')->insert([
                    'user_id' => Auth::id(),
                    'coupon_id' => $deliveryInfo['coupon_id'],
                    'use_count' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        if (!empty($deliveryInfo['code_promo'])) {
            $code = \App\Models\Coupon::whereCode($deliveryInfo['code_promo'])->first();
            if ($code) {
                DB::table('coupon_user')
                    ->where('user_id', Auth::id())
                    ->where('coupon_id', $code->id)
                    ->update(['nbre_utilisation' => 1]);
            }
        }
    }

    /**
     * Callback de succès Wave
     */
    public function waveSuccess(Request $request)
    {
        $ref = $request->query('ref');

        if (!$ref) {
            return view('site.pages.payment-pending')
                ->with('message', 'Votre paiement est en cours de traitement.');
        }

        // Extraire l'ID de la commande depuis la référence (ORDER_123)
        $orderId = str_replace('ORDER_', '', $ref);
        $order = Order::find($orderId);
        
        if (!$order) {
            Log::error('Wave Success - commande introuvable', ['ref' => $ref]);
            return redirect()->route('home')->with('error', 'Commande introuvable');
        }
        
        // Si le paiement est déjà confirmé, rediriger vers la page de succès
        if ($order->payment_status === 'completed') {
            return redirect()->route('order.success', $order->id)
                ->with('success', 'Votre paiement a été effectué avec succès');
        }
        
        // Attendre que le webhook confirme le paiement (max 10 secondes)
        for ($i = 0; $i < 5; $i++) {
            sleep(2);
            $order->refresh();
            
            if ($order->payment_status === 'completed') {
                return redirect()->route('order.success', $order->id)
                    ->with('success', 'Votre paiement a été effectué avec succès');
            }
        }
        
        // Le webhook n'a pas encore été reçu
        return view('site.pages.payment-pending')
            ->with('message', 'Votre paiement est en cours de vérification. Veuillez patienter...')
            ->with('order_id', $order->id);
    }

    /**
     * Callback d'erreur Wave
     */
    public function waveError(Request $request)
    {
        $ref = $request->query('ref');

        if ($ref) {
            $orderId = str_replace('ORDER_', '', $ref);
            $order = Order::find($orderId);
            
            if ($order) {
                $order->update([
                    'payment_status' => 'cancelled',
                    'status' => Order::STATUS_ANNULEE,
                ]);
            }
        }

        return redirect()->route('payment.select')
            ->with('error', 'Le paiement a échoué ou a été annulé. Veuillez réessayer.');
    }

    /**
     * Vérifier le statut d'une commande Wave (via AJAX)
     */
    public function checkTransactionStatus(Request $request)
    {
        $orderId = $request->query('order_id');
        
        if (!$orderId) {
            return response()->json(['error' => 'Missing order_id'], 400);
        }
        
        $order = Order::find($orderId);
        
        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }
        
        return response()->json([
            'status' => $order->payment_status,
            'order_id' => $order->id,
            'completed' => $order->payment_status === 'completed',
        ]);
    }

    /**
     * Page de succès de commande
     */
    public function orderSuccess($orderId)
    {
        $order = Order::with(['products', 'paymentMethod'])
            ->where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('site.pages.order-success', compact('order'));
    }
}
