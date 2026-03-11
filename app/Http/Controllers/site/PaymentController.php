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
            $quantityTotal = count($cart);
            $deliveryPrice = $deliveryInfo['price'];
            $total = $subtotal + $deliveryPrice;

            // Déterminer le statut
            $status = $deliveryInfo['type_commande'] == 'cmd_precommande' 
                ? Order::STATUS_PRECOMMANDE 
                : Order::STATUS_ATTENTE;

            // Créer une commande en attente de paiement
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
                'payment_status' => 'pending',
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

            // Créer une session de paiement Wave
            $waveResponse = $this->waveService->createCheckoutSession(
                $total,
                'XOF',
                $order->id
            );

            if (!$waveResponse['success']) {
                // Supprimer la commande si le paiement échoue
                $order->delete();
                return back()->with('error', 'Impossible d\'initialiser le paiement Wave');
            }

            // Stocker l'ID de session Wave dans la commande
            $order->update([
                'wave_session_id' => $waveResponse['session_id'],
            ]);

            // Gérer le coupon avant de vider la session
            $this->handleCoupon($deliveryInfo);

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

            // Mettre à jour la commande selon le statut
            switch ($status) {
                case 'complete':
                case 'completed':
                    $order->update([
                        'payment_status' => 'completed',
                        'payment_completed_at' => now(),
                        'wave_payment_id' => $payload['wave_payment_id'] ?? null,
                        'status' => Order::STATUS_CONFIRMEE
                    ]);

                    Log::info('Wave Payment Completed', [
                        'order_id' => $order->id,
                        'session_id' => $sessionId
                    ]);

                    // Envoyer les notifications (email, WhatsApp)
                    $this->sendOrderNotifications($order);
                    break;

                case 'failed':
                case 'cancelled':
                    $order->update([
                        'payment_status' => $status,
                        'status' => Order::STATUS_ANNULEE,
                        'raison_annulation_cmd' => 'Paiement Wave ' . ($status === 'failed' ? 'échoué' : 'annulé')
                    ]);

                    Log::info('Wave Payment Failed/Cancelled', [
                        'order_id' => $order->id,
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
        $orderId = $request->query('order_id');

        if (!$orderId) {
            return redirect()->route('home')->with('error', 'Commande introuvable');
        }

        $order = Order::find($orderId);

        if (!$order) {
            return redirect()->route('home')->with('error', 'Commande introuvable');
        }

        // Vérifier le statut du paiement
        if ($order->payment_status === 'completed') {
            // Paiement déjà confirmé par webhook
            Session::forget('cart');
            Session::forget('delivery_info');

            return redirect()->route('order.success', $order->id)
                ->with('success', 'Votre paiement a été effectué avec succès');
        }

        // Le webhook n'a pas encore été reçu, afficher un message d'attente
        Session::forget('cart');
        Session::forget('delivery_info');

        return redirect()->route('order.success', $order->id)
            ->with('info', 'Votre paiement est en cours de vérification');
    }

    /**
     * Callback d'erreur Wave
     */
    public function waveError(Request $request)
    {
        $orderId = $request->query('order_id');

        if ($orderId) {
            $order = Order::find($orderId);
            if ($order) {
                // Vous pouvez choisir de supprimer la commande ou de la marquer comme échouée
                $order->update(['status' => Order::STATUS_ANNULEE]);
            }
        }

        return redirect()->route('payment.select')
            ->with('error', 'Le paiement a échoué. Veuillez réessayer.');
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
