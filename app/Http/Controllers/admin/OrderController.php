<?php

namespace App\Http\Controllers\admin;

use Exception;
use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Support\Str;
use App\Services\TicAfriqueService;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

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

        $orders = Order::orderBy('created_at', 'DESC')
            ->when(request('d'), fn($q) => $q->where('date_order', Carbon::now()->format('Y-m-d')))
            ->when(request('s'), fn($q) => $q->whereStatus(request('s')))
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

        return view('admin.pages.order.order', compact('orders', 'dateDebut', 'dateFin'));
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
                'products'
                => fn($q) => $q->with('media')
            ])
            ->orderBy('created_at', 'DESC')->first();
        // dd($orders->toArray());
        return view('admin.pages.order.order_show', compact('orders'));
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
        $state = request('cs'); // cs => change state 
        $orderId = request('id');

        $order = Order::with('user')->whereId($orderId)->first();

        $changeState = Order::whereId($orderId)->update([
            'status' => $state
        ]);

        // Envoyer SMS seulement si confirmée
        if ($state == 'confirmée') {
            $this->sendSms($order);
        }

        if ($state == 'livrée') {
            $changeState = Order::whereId($orderId)->update([
                'delivery_date' => carbon::now()
            ]);
        }

        if ($state == 'all') {
            $changeState = Order::whereStatus("attente")->update([
                'status' => 'confirmée'
            ]);
        }

        // Envoyer email pour tous les changements de statut via Queue
        if (!empty($order->user->email) && in_array($state, ['confirmée', 'annulée', 'livrée', 'en cours'])) {
            $subject = match ($state) {
                'confirmée' => 'Confirmation de commande',
                'annulée' => 'Annulation de commande',
                'livrée' => 'Commande livrée',
                'en cours' => 'Commande en cours de traitement',
                default => 'Mise à jour de votre commande'
            };

            $emailData = [
                'imagePath' => asset('site/assets/img/custom/AKADI.png'),
                'clientName' => $order->user->name,
                'orderCode' => $order->code,
                'status' => $state,
                'raison' => $order->raison_annulation_cmd ?? null
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

        return back()->withSuccess('statut changé avec success');
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


    public function checkNewOrder()
    {

        $orders_new = Order::with('user')->orderBy('created_at', 'DESC')
            ->whereIn('status', ['attente', 'precommande'])
            ->get();

        return response()->json([
            'status' => 'success',
            'orders' => $orders_new
            // 'orders_new' => $orders_new->map(function ($order) {
            //     return [
            //         'id' => $order->id,
            //         'code' => $order->code,
            //         'status' => $order->status,
            //         'created_at' => $order->created_at->format('Y-m-d H:i:s'),
            //         'created_at_human' => Carbon::parse($order->created_at)->diffForHumans(),
            //     ];
            // }),
            // 'count' => $orders_new->count() // Ajoute le nombre total des nouvelles commandes
        ]);
    }
}
