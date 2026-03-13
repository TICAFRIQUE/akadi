<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Services\TicAfriqueService;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;

class OrderController extends Controller
{

    /**
     * Envoyer un SMS au client via l'API TIC Afrique un fois la commande confirmée
     */
    public function sendSms($order)
    {
        $smsService = new TicAfriqueService();
        $smsService->sendOrderConfirmationSms($order);
    }
    //get all order 
    public function getAllOrder(Request $request)
    {
        // Par défaut : mois en cours
        $dateDebut = $request->input('date_debut', now()->startOfMonth()->format('Y-m-d'));
        $dateFin   = $request->input('date_fin',   now()->endOfMonth()->format('Y-m-d'));
        $status    = $request->input('status');
        $source    = $request->input('source');

        // Restriction selon permission (p-cuisine, p-livraison, p-confirmation)
        $allowedStatuses = orderStatusesAllowed();

        // Si tableau vide → aucun statut autorisé, retour accès refusé
        if (is_array($allowedStatuses) && count($allowedStatuses) === 0) {
            abort(403, 'Accès non autorisé.');
        }

        $orders = Order::with(['user', 'paymentMethod', 'caisse', 'createdBy'])
            ->orderBy('created_at', 'DESC')
            ->when($allowedStatuses !== null, fn($q) => $q->whereIn('status', $allowedStatuses))
            ->when(request('d'), fn($q) => $q->where('date_order', Carbon::now()->format('Y-m-d')))
            ->when(request('s'), fn($q) => $q->whereStatus(request('s')))
            ->when($source, fn($q) => $q->where('source', $source))
            ->when(
                !request('d') && !request('s'),
                function ($q) use ($dateDebut, $dateFin, $status) {
                    $q->whereBetween('date_order', [$dateDebut, $dateFin]);
                    if ($status && $status !== 'all') {
                        $q->whereStatus($status);
                    }
                }
            )
            ->get();

        // Limiter le sélecteur de statuts aux statuts autorisés
        $statuts = $allowedStatuses !== null
            ? array_intersect_key(Order::$statuts, array_flip($allowedStatuses))
            : Order::$statuts;
        $sources = Order::$sources;

        return view('admin.pages.order.order', compact('orders', 'dateDebut', 'dateFin', 'statuts', 'sources'));
    }


    //filter
    public function filter(Request $request)
    {
        try {
            // dd($request->date_debut==null);
            $orders = Order::query()
                //when only request('status') 
                ->when($request->status)
                ->whereStatus($request->status)

                //when request('status') and request('date_debut')
                ->when($request->status && $request->date_debut)
                ->whereStatus($request->status)
                ->where('date_order', $request->date_debut)
                ->orderBy('created_at', 'DESC')->get();

            return view('admin.pages.order.order', compact(['orders']));
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    //show order     
    // detail of order user
    public function showOrder($id)
    {
        $orders = Order::whereId($id)
            ->with([
                'user',
                'paymentMethod',
                'caisse',
                'createdBy',
                'products' => fn($q) => $q->with('media')
            ])
            ->orderBy('created_at', 'DESC')->first();

        $statuts        = Order::$statuts;
        $sources        = Order::$sources;
        $paymentMethods = PaymentMethod::actif()->get();

        return view('admin.pages.order.order_show', compact('orders', 'statuts', 'sources', 'paymentMethods'));
    }

    public function invoice($id)
    {
        //function si on verifie la disponibilite des articles
        // $orders= Order::whereId($id)
        // ->with(['user','products'
        //     =>fn($q)=>$q->with('media')->where('available', 'disponible')
        // ])
        // ->orderBy('created_at','DESC')->first();


        //function sans verification de disponiblite
        $orders = Order::whereId($id)
            ->with([
                'user',
                'products'
                => fn($q) => $q->with('media')
            ])
            ->orderBy('created_at', 'DESC')->first();


        return PDF::loadView('admin.pages.order.invoicePdf', compact('orders'))
            ->setPaper('a5', 'portrait')
            ->setWarnings(true)
            ->save(public_path("storage/" . $orders['id'] . ".pdf"))
            ->stream(Str::slug($orders->code) . ".pdf");

        // return $pdf->download(Str::slug($orders->code) . ".pdf");


        // dd($orders->toArray());
        // return view('admin.pages.order.invoicePdf',compact('orders'));
    }


    //changer le status de la commande
    public function changeState(Request $request)
    {
        $state   = request('cs'); // cs => change state
        $orderId = request('id');

        $allowedStatuses = array_keys(Order::$statuts);

        $order = Order::with('user')->whereId($orderId)->first();

        if (!$order) {
            return back()->with('error', 'Commande introuvable.');
        }

        // ── Contrôle des permissions de transition ────────────────────────────────
        if (!canChangeOrderStatus($state)) {
            return back()->with('error', 'Vous n\'avez pas la permission d\'effectuer ce changement de statut.');
        }

        // ── Règles métier sur l'acompte ──────────────────────────────────────────
        $acompteOptionnel = in_array($state, [
            Order::STATUS_ATTENTE,
            Order::STATUS_PRECOMMANDE,
            Order::STATUS_ATTENTE_ACOMPTE,
            Order::STATUS_ANNULEE,
        ]);

        if (!$acompteOptionnel) {
            if ($state === Order::STATUS_LIVREE) {
                if (round((float) $order->acompte, 2) !== round((float) $order->total, 2)) {
                    return back()->with(
                        'error',
                        "Pour passer en « Livrée », l'acompte (" . number_format($order->acompte, 0, ',', ' ') . " FCFA) doit être égal au total (" . number_format($order->total, 0, ',', ' ') . " FCFA)."
                    );
                }
            } elseif ((float) $order->acompte <= 0) {
                return back()->with(
                    'error',
                    "Un acompte est obligatoire avant de passer au statut « {$state} »."
                );
            }
        }

        Order::whereId($orderId)->update(['status' => $state, 'created_by' => Auth::id()]);

        // Envoyer SMS seulement si confirmée
        if ($state === Order::STATUS_CONFIRMEE) {
            $this->sendSms($order);
        }

        if ($state === Order::STATUS_LIVREE) {
            Order::whereId($orderId)->update(['delivery_date' => Carbon::now()]);
        }

        if ($state === 'all') {
            Order::whereStatus(Order::STATUS_ATTENTE)->update(['status' => Order::STATUS_CONFIRMEE]);
        }

        // Envoyer email pour les changements de statut importants
        $notifyStatuses = [Order::STATUS_CONFIRMEE, Order::STATUS_ANNULEE, Order::STATUS_LIVREE, Order::STATUS_EN_CUISINE, Order::STATUS_CUISINE_TERMINEE];
        if (!empty($order->user?->email) && in_array($state, $notifyStatuses)) {
            $subject = match ($state) {
                Order::STATUS_CONFIRMEE        => 'Confirmation de commande',
                Order::STATUS_ANNULEE          => 'Annulation de commande',
                Order::STATUS_LIVREE           => 'Commande livrée',
                Order::STATUS_EN_CUISINE       => 'Commande en préparation',
                Order::STATUS_CUISINE_TERMINEE => 'Commande prête',
                default                        => 'Mise à jour de votre commande'
            };

            $emailData = [
                'imagePath'  => asset('site/assets/img/custom/AKADI.png'),
                'clientName' => $order->user->name,
                'orderCode'  => $order->code,
                'status'     => $state,
                'raison'     => $order->raison_annulation_cmd ?? null
            ];

            SendEmailJob::dispatch(
                $order->user->email,
                $subject,
                'emails.order-status',
                $emailData,
                'info@akadi.ci',
                'Akadi'
            );
        }

        return back()->withSuccess('Statut changé avec succès');
    }


    public function orderCancel(Request $request)  // cancel order with reason
    {
        $motif = "";
        if ($request['motif'] == 'autre') {
            $motif = $request['motif_autre'];
        } else {
            $motif = $request['motif'];
        }
        Order::whereId($request['commandeId'])->update([
            'status' => 'annulée',
            'raison_annulation_cmd' => $motif
        ]);

        //envoyer email d'annulation via queue
        $order = Order::with('user')->whereId($request['commandeId'])->first();
        if (!empty($order->user->email)) {
            SendEmailJob::dispatch(
                $order->user->email,
                'Annulation de commande',
                'emails.order-status',
                [
                    'imagePath' => asset('site/assets/img/custom/AKADI.png'),
                    'clientName' => $order->user->name,
                    'orderCode' => $order->code,
                    'status' => 'annulée',
                    'raison' => $motif
                ],
                'info@akadi.ci',
                'Akadi'
            );
        }

        return back()->withSuccess('Commande annulée avec success');
    }


    public function checkNewOrder(Request $request)
    {
        // Ne retourner que les commandes postérieures au dernier ID connu côté client
        $sinceId = (int) $request->input('since_id', 0);

        $orders_new = Order::with('user')
            ->whereIn('status', [Order::STATUS_ATTENTE, Order::STATUS_PRECOMMANDE])
            ->where('source', 'web')
            ->where('payment_status', 'completed')
            ->when($sinceId > 0, fn($q) => $q->where('id', '>', $sinceId))
            ->orderBy('id', 'DESC')
            ->limit(20)
            ->get()
            ->map(fn($order) => [
                'id'           => $order->id,
                'code'         => $order->code,
                'status'       => $order->status,
                'status_label' => Order::$statuts[$order->status]['label'] ?? $order->status,
                'status_color' => Order::$statuts[$order->status]['color'] ?? 'secondary',
                'source'       => $order->source,
                'source_label' => Order::$sources[$order->source]['label'] ?? $order->source,
                'source_icon'  => Order::$sources[$order->source]['icon'] ?? 'fa-question',
                'nom_client'   => $order->nom_client,
                'tel_client'   => $order->tel_client,
                'total'        => (float) $order->total,
                'acompte'      => (float) $order->acompte,
                'solde_restant' => (float) $order->solde_restant,
                'created_at'   => $order->created_at->format('d/m/Y H:i'),
            ]);

        return response()->json([
            'status' => 'success',
            'orders' => $orders_new,
            'count'  => $orders_new->count(),
        ]);
    }
}
