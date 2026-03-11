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

            if (empty($cart)) {
                return redirect()->route('panier')->with('error', 'Votre panier est vide');
            }

            if (empty($deliveryInfo)) {
                return redirect()->route('checkout')->with('error', 'Informations de livraison manquantes');
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
                return redirect()->route('checkout')->with('error', 'Informations de livraison manquantes');
            }

            // Calculer les totaux
            $subtotal = $deliveryInfo['subTotal'];
            $deliveryPrice = $deliveryInfo['price'];
            $total = $subtotal + $deliveryPrice;

            // Générer un identifiant unique pour cette transaction
            $transactionRef = 'TXN_' . Auth::id() . '_' . time();

            // Stocker les infos complètes pour le webhook (inclut payment_method_id et cart)
            Session::put('wave_transaction_' . $transactionRef, [
                'cart' => $cart,
                'delivery_info' => $deliveryInfo,
                'payment_method_id' => $request->payment_method_id,
                'user_id' => Auth::id(),
            ]);

            // Créer une session de paiement Wave SANS créer la commande
            $waveResponse = $this->waveService->createCheckoutSession(
                $total,
                'XOF',
                $transactionRef  // Utiliser la référence au lieu de l'order_id
            );

            if (!$waveResponse['success']) {
                Session::forget('wave_transaction_' . $transactionRef);
                return back()->with('error', 'Impossible d\'initialiser le paiement Wave');
            }

            // Stocker la référence de transaction avec le session_id Wave
            Session::put('wave_session_mapping_' . $waveResponse['session_id'], $transactionRef);

            // Rediriger vers Wave
            return redirect()->away($waveResponse['wave_launch_url']);

        } catch (Exception $e) {
            Log::error('Wave Payment Error', ['error' => $e->getMessage()]);
            return back()->with('error', 'Une erreur est survenue lors de l\'initialisation du paiement');
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

            // Trouver la commande correspondante
            $order = Order::where('wave_session_id', $sessionId)->first();

            if (!$order) {
                Log::warning('Wave Webhook - commande introuvable', ['session_id' => $sessionId]);
                return response()->json(['error' => 'Order not found'], 404);
            }

            // Récupérer la référence de transaction
            $transactionRef = Session::get('wave_session_mapping_' . $sessionId);
            
            if (!$transactionRef) {
                // Ancien flux : commande existe déjà, mise à jour
                if ($order) {
                    $this->updateExistingOrder($order, $status, $payload, $sessionId);
                }
                return response()->json(['status' => 'success'], 200);
            }

            // Nouveau flux : créer la commande seulement si paiement completed
            $transactionData = Session::get('wave_transaction_' . $transactionRef);
            
            if (!$transactionData) {
                Log::error('Wave Webhook - données de transaction introuvables', [
                    'transaction_ref' => $transactionRef,
                    'session_id' => $sessionId
                ]);
                return response()->json(['error' => 'Transaction data not found'], 404);
            }

            // Traiter selon le statut
            switch ($status) {
                case 'complete':
                case 'completed':
                    // CRÉER la commande maintenant que le paiement est confirmé
                    $order = $this->createOrderFromTransaction($transactionData, $sessionId, $payload);
                    
                    Log::info('Wave Payment Completed - Order Created', [
                        'order_id' => $order->id,
                        'session_id' => $sessionId,
                        'amount_paid' => $order->total,
                        'transaction_ref' => $transactionRef
                    ]);

                    // Envoyer les notifications
                    $this->sendOrderNotifications($order);
                    
                    // Nettoyer les sessions
                    Session::forget('wave_transaction_' . $transactionRef);
                    Session::forget('wave_session_mapping_' . $sessionId);
                    Session::forget('cart');
                    Session::forget('delivery_info');
                    Session::forget('totalQuantity');
                    break;

                case 'failed':
                case 'cancelled':
                    // Paiement échoué/annulé : ne pas créer de commande, juste nettoyer
                    Log::info('Wave Payment Failed/Cancelled - No Order Created', [
                        'session_id' => $sessionId,
                        'status' => $status,
                        'transaction_ref' => $transactionRef
                    ]);
                    
                    // Nettoyer les sessions mais garder cart et delivery_info 
                    // pour que l'utilisateur puisse réessayer
                    Session::forget('wave_transaction_' . $transactionRef);
                    Session::forget('wave_session_mapping_' . $sessionId);
                    break;

                default:
                    Log::warning('Wave Webhook - statut inconnu', [
                        'status' => $status,
                        'session_id' => $sessionId,
                        'transaction_ref' => $transactionRef
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
     * Créer une commande à partir des données de transaction (nouveau flux)
     */
    protected function createOrderFromTransaction($transactionData, $sessionId, $payload)
    {
        $cart = $transactionData['cart'];
        $deliveryInfo = $transactionData['delivery_info'];
        
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
            'user_id' => $transactionData['user_id'],
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
            'payment_method_id' => $transactionData['payment_method_id'],
            'payment_status' => 'completed',
            'payment_completed_at' => now(),
            'wave_session_id' => $sessionId,
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
            // Attendre quelques secondes que le webhook soit traité
            sleep(3);
            
            // Chercher si la commande a été créée
            $order = Order::where('user_id', Auth::id())
                ->where('payment_status', 'completed')
                ->latest()
                ->first();
            
            if ($order) {
                // La commande a été créée par le webhook
                Session::forget('cart');
                Session::forget('delivery_info');
                Session::forget('totalQuantity');
                
                return redirect()->route('order.success', $order->id)
                    ->with('success', 'Votre paiement a été effectué avec succès');
            }
            
            // Le webhook n'a pas encore été reçu
            return view('site.pages.payment-pending')
                ->with('message', 'Votre paiement est en cours de vérification. Veuillez patienter...');
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

        // Si c'est le nouveau flux (transaction), ne rien faire
        // Si c'est l'ancien flux avec un order_id, marquer la commande comme annulée
        if ($ref && !str_starts_with($ref, 'TXN_')) {
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
