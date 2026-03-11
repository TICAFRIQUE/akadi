<?php

namespace App\Http\Controllers\site;

use Exception;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\WaveTransaction;
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

            Log::info('Processing Wave Payment', [
                'user_id' => Auth::id(),
                'has_cart' => !empty($cart),
                'has_delivery_info' => !empty($deliveryInfo),
                'cart_count' => count($cart),
                'delivery_info_keys' => !empty($deliveryInfo) ? array_keys($deliveryInfo) : []
            ]);

            if (empty($cart)) {
                Log::warning('Wave Payment - Empty Cart', ['user_id' => Auth::id()]);
                return redirect()->route('panier')->with('error', 'Votre panier est vide');
            }

            if (empty($deliveryInfo)) {
                Log::error('Wave Payment - Missing Delivery Info', [
                    'user_id' => Auth::id(),
                    'session_keys' => array_keys(Session::all()),
                    'all_session' => Session::all()
                ]);
                return redirect()->route('checkout')->with('error', 'Veuillez remplir les informations de livraison avant de procéder au paiement');
            }

            // Calculer les totaux
            $subtotal = $deliveryInfo['subTotal'];
            $deliveryPrice = $deliveryInfo['price'];
            $total = $subtotal + $deliveryPrice;

            // Générer un identifiant unique pour cette transaction
            $transactionRef = 'TXN_' . Auth::id() . '_' . time() . '_' . uniqid();

            // Créer une session de paiement Wave SANS créer la commande
            $waveResponse = $this->waveService->createCheckoutSession(
                $total,
                'XOF',
                $transactionRef
            );

            if (!$waveResponse['success']) {
                $errorMessage = $waveResponse['error'] ?? 'Erreur inconnue';
                Log::error('Wave Payment Init Failed', [
                    'user_id' => Auth::id(),
                    'error' => $errorMessage,
                    'transaction_ref' => $transactionRef
                ]);
                
                return back()->with('error', 'Impossible d\'initialiser le paiement Wave. Veuillez réessayer ou choisir un autre moyen de paiement. Erreur: ' . $errorMessage);
            }

            // Stocker la transaction en base de données (accessible par webhook)
            WaveTransaction::create([
                'transaction_ref' => $transactionRef,
                'wave_session_id' => $waveResponse['session_id'],
                'user_id' => Auth::id(),
                'payment_method_id' => $request->payment_method_id,
                'cart_data' => $cart,
                'delivery_info' => $deliveryInfo,
                'status' => WaveTransaction::STATUS_PENDING,
            ]);

            Log::info('Wave Transaction Created', [
                'transaction_ref' => $transactionRef,
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
            // Log de la requête reçue
            Log::info('Wave Webhook Received', [
                'headers' => $request->headers->all(),
                'payload' => $request->all()
            ]);

            // Récupérer les données du webhook
            $payload = $request->all();

            // Vérifier que le payload contient les données nécessaires
            if (!isset($payload['id']) || !isset($payload['status'])) {
                Log::warning('Wave Webhook - données manquantes', ['payload' => $payload]);
                return response()->json(['error' => 'Invalid payload'], 400);
            }

            $sessionId = $payload['id'];
            $status = $payload['status'];

            // Chercher la transaction Wave par session_id
            $waveTransaction = WaveTransaction::where('wave_session_id', $sessionId)->first();

            if (!$waveTransaction) {
                // Vérifier si c'est une ancienne commande (rétrocompatibilité)
                $order = Order::where('wave_session_id', $sessionId)->first();
                if ($order) {
                    $this->updateExistingOrder($order, $status, $payload, $sessionId);
                    return response()->json(['status' => 'success'], 200);
                }
                
                Log::error('Wave Webhook - transaction introuvable', ['session_id' => $sessionId]);
                return response()->json(['error' => 'Transaction not found'], 404);
            }

            // Traiter selon le statut
            switch ($status) {
                case 'complete':
                case 'completed':
                    // CRÉER la commande maintenant que le paiement est confirmé
                    $order = $this->createOrderFromWaveTransaction($waveTransaction, $payload);
                    
                    // Mettre à jour la transaction
                    $waveTransaction->update([
                        'status' => WaveTransaction::STATUS_COMPLETED,
                        'order_id' => $order->id,
                    ]);
                    
                    Log::info('Wave Payment Completed - Order Created', [
                        'order_id' => $order->id,
                        'transaction_ref' => $waveTransaction->transaction_ref,
                        'session_id' => $sessionId,
                        'amount_paid' => $order->total
                    ]);

                    // Envoyer les notifications
                    $this->sendOrderNotifications($order);
                    break;

                case 'failed':
                case 'cancelled':
                    // Mettre à jour le statut de la transaction
                    $waveTransaction->update([
                        'status' => $status === 'failed' 
                            ? WaveTransaction::STATUS_FAILED 
                            : WaveTransaction::STATUS_CANCELLED
                    ]);
                    
                    Log::info('Wave Payment Failed/Cancelled', [
                        'transaction_ref' => $waveTransaction->transaction_ref,
                        'session_id' => $sessionId,
                        'status' => $status
                    ]);
                    break;

                default:
                    Log::warning('Wave Webhook - statut inconnu', [
                        'status' => $status,
                        'session_id' => $sessionId
                    ]);
            }

            // Répondre à Wave pour confirmer la réception
            return response()->json(['status' => 'success'], 200);

        } catch (Exception $e) {
            Log::error('Wave Webhook Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Créer une commande à partir d'une WaveTransaction
     */
    protected function createOrderFromWaveTransaction(WaveTransaction $waveTransaction, $payload)
    {
        $cart = $waveTransaction->cart_data;
        $deliveryInfo = $waveTransaction->delivery_info;
        
        // Calculer les totaux
        $subtotal = $deliveryInfo['subTotal'];
        $quantityTotal = count($cart);
        $deliveryPrice = $deliveryInfo['price'];
        $total = $subtotal + $deliveryPrice;
        
        // Déterminer le statut
        $status = $deliveryInfo['type_commande'] == 'cmd_precommande' 
            ? Order::STATUS_PRECOMMANDE 
            : Order::STATUS_ATTENTE;
        
        // Calculer acompte et solde
        $acompteAmount = $total;
        $soldeRestant = 0;
        
        // Créer la commande
        $order = Order::create([
            'user_id' => $waveTransaction->user_id,
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
            'payment_method_id' => $waveTransaction->payment_method_id,
            'payment_status' => 'completed',
            'payment_completed_at' => now(),
            'wave_session_id' => $waveTransaction->wave_session_id,
            'wave_payment_id' => $payload['wave_payment_id'] ?? null,
            'acompte' => $acompteAmount,
            'solde_restant' => $soldeRestant,
            'date_order' => now()->format('Y-m-d'),
        ]);
        
        // Attacher les produits
        foreach ($cart as $productId => $item) {
            $order->products()->attach($productId, [
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'total' => $item['price'] * $item['quantity'],
            ]);
        }
        
        // Gérer le coupon
        $this->handleCoupon($deliveryInfo);
        
        return $order;
    }
    
    /**
     * Mettre à jour une commande existante (ancien flux - rétrocompatibilité)
     */
    protected function updateExistingOrder($order, $status, $payload, $sessionId)
    {
        switch ($status) {
            case 'complete':
            case 'completed':
                $acompteAmount = $order->total;
                $soldeRestant = 0;
                
                $order->update([
                    'payment_status' => 'completed',
                    'payment_completed_at' => now(),
                    'wave_payment_id' => $payload['wave_payment_id'] ?? null,
                    'acompte' => $acompteAmount,
                    'solde_restant' => $soldeRestant,
                    'status' => Order::STATUS_ATTENTE
                ]);
                
                Log::info('Wave Payment Completed - Existing Order Updated', [
                    'order_id' => $order->id,
                    'session_id' => $sessionId,
                    'amount_paid' => $acompteAmount
                ]);
                
                $this->sendOrderNotifications($order);
                break;
                
            case 'failed':
            case 'cancelled':
                $order->update([
                    'payment_status' => $status,
                    'status' => Order::STATUS_ANNULEE,
                    'raison_annulation_cmd' => 'Paiement Wave ' . ($status === 'failed' ? 'échoué' : 'annulé')
                ]);
                
                Log::info('Wave Payment Failed/Cancelled - Order Updated', [
                    'order_id' => $order->id,
                    'session_id' => $sessionId,
                    'status' => $status
                ]);
                break;
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
            // Pas de référence : afficher un message d'attente générique
            return view('site.pages.payment-pending')
                ->with('message', 'Votre paiement est en cours de traitement. Vous recevrez une confirmation sous peu.');
        }

        // Vérifier si la référence correspond à une transaction (nouveau flux)
        if (str_starts_with($ref, 'TXN_')) {
            // Chercher la transaction Wave
            $waveTransaction = WaveTransaction::where('transaction_ref', $ref)->first();
            
            if (!$waveTransaction) {
                Log::error('Wave Success - transaction introuvable', ['ref' => $ref]);
                return redirect()->route('home')->with('error', 'Transaction introuvable');
            }
            
            // Si la commande est déjà créée, rediriger
            if ($waveTransaction->order_id) {
                Session::forget('cart');
                Session::forget('delivery_info');
                Session::forget('totalQuantity');
                
                return redirect()->route('order.success', $waveTransaction->order_id)
                    ->with('success', 'Votre paiement a été effectué avec succès');
            }
            
            // Attendre que le webhook crée la commande
            for ($i = 0; $i < 5; $i++) {
                sleep(2);
                $waveTransaction->refresh();
                
                if ($waveTransaction->order_id) {
                    Session::forget('cart');
                    Session::forget('delivery_info');
                    Session::forget('totalQuantity');
                    
                    return redirect()->route('order.success', $waveTransaction->order_id)
                        ->with('success', 'Votre paiement a été effectué avec succès');
                }
            }
            
            // Le webhook n'a pas encore été reçu après 10 secondes
            return view('site.pages.payment-pending')
                ->with('message', 'Votre paiement est en cours de vérification. Veuillez patienter...')
                ->with('transaction_ref', $ref);
        }
        
        // Ancien flux : ref est un order_id
        $order = Order::find($ref);
        
        if (!$order) {
            return redirect()->route('home')->with('error', 'Commande introuvable');
        }

        // Vérifier le statut du paiement
        if ($order->payment_status === 'completed') {
            Session::forget('cart');
            Session::forget('delivery_info');
            Session::forget('totalQuantity');

            return redirect()->route('order.success', $order->id)
                ->with('success', 'Votre paiement a été effectué avec succès');
        }

        // Le webhook n'a pas encore été reçu
        return view('site.pages.payment-pending')
            ->with('message', 'Votre paiement est en cours de vérification');
    }

    /**
     * Callback d'erreur Wave
     */
    public function waveError(Request $request)
    {
        $ref = $request->query('ref');

        // Si c'est le nouveau flux (transaction), mettre à jour le statut
        if ($ref && str_starts_with($ref, 'TXN_')) {
            $waveTransaction = WaveTransaction::where('transaction_ref', $ref)->first();
            if ($waveTransaction) {
                $waveTransaction->update(['status' => WaveTransaction::STATUS_CANCELLED]);
            }
        } else if ($ref) {
            // Ancien flux avec un order_id
            $order = Order::find($ref);
            if ($order) {
                $order->update([
                    'status' => Order::STATUS_ANNULEE,
                    'raison_annulation_cmd' => 'Paiement Wave annulé par l\'utilisateur'
                ]);
            }
        }

        return redirect()->route('payment.select')
            ->with('error', 'Le paiement a échoué ou a été annulé. Veuillez réessayer.');
    }

    /**
     * Vérifier le statut d'une transaction Wave (via AJAX)
     */
    public function checkTransactionStatus(Request $request)
    {
        $ref = $request->query('ref');
        
        if (!$ref || !str_starts_with($ref, 'TXN_')) {
            return response()->json(['error' => 'Invalid reference'], 400);
        }
        
        $waveTransaction = WaveTransaction::where('transaction_ref', $ref)->first();
        
        if (!$waveTransaction) {
            return response()->json(['error' => 'Transaction not found'], 404);
        }
        
        return response()->json([
            'status' => $waveTransaction->status,
            'order_id' => $waveTransaction->order_id,
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
