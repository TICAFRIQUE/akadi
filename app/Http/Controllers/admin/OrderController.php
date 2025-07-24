<?php

namespace App\Http\Controllers\admin;

use Log;
use Exception;
use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class OrderController extends Controller
{
    //get all order 
    public function getAllOrder(Request $request)
    {
        // dd($request->all);
        //request('s') ## s => status ,  filter from status order
        $orders = Order::orderBy('created_at', 'DESC')
            ->when(request('d'), fn($q) => $q->where('date_order', Carbon::now()->format('Y-m-d')))
            ->when(request('s'), fn($q) => $q->whereStatus(request('s')))
            //get orders between two dates
            ->when(
                request('date_debut') && request('date_fin') && request('status') != 'all',
                fn($q) => $q->whereBetween('date_order', [request('date_debut'), request('date_fin')])->whereStatus(request('status'))
            )
            ->when(request('status') == 'all') // toutes les commandes
            ->whereBetween('date_order', [request('date_debut'), request('date_fin')])
            ->get();

        return view('admin.pages.order.order', compact(['orders']));
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


        //send mail if order is confirmed or cancel
        if (!empty($order->user->email)) {
            $data = [
                'imagePath' => asset('site/assets/img/custom/AKADI.png'),
            ];

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'mail.akadi.ci';
                $mail->SMTPAuth = true;
                $mail->Username = 'info@akadi.ci';
                $mail->Password = 'S$UBfu.8s(#z';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                $mail->setFrom('info@akadi.ci', 'Akadi');
                $mail->addAddress($order->user->email);

                $mail->isHTML(true);

                if ($state == 'confirmée') {
                    $mail->Subject = 'Confirmation de commande';
                    $mail->Body = '
                <p style="text-align: center;"><img src="' . $data['imagePath'] . '" alt="Akadi logo" width="80"></p>
                <p style="text-align: center;">Hello, ' . $order->user->name . ' </p>
                <p style="text-align: center;">Votre commande <b>#' . $order->code . '</b> a été confirmée avec succès.</p>
                <p style="text-align: center;">Vous serez livré dans peu de temps.</p>
                <p style="text-align: center;">Merci pour votre confiance, <a href="http://Akadi.ci" target="_blank">www.akadi.ci</a></p>';
                } elseif ($state == 'annulée') {
                    $mail->Subject = 'Annulation de la commande';
                    $mail->Body = '
                <p style="text-align: center;"><img src="' . $data['imagePath'] . '" alt="Akadi logo" width="80"></p>
                <p style="text-align: center;">Hello, ' . $order->user->name . ' </p>
                <p style="text-align: center;">Votre commande <b>#' . $order->code . '</b> a été annulée.</p>
                <p style="text-align: center;">Raison : ' . $order->raison_annulation_cmd . '</p>
                <p style="text-align: center;">Merci pour votre confiance, <a href="http://Akadi.ci" target="_blank">www.akadi.ci</a></p>';
                }

                $mail->send();
            } catch (Exception $e) {
               return back()->withError($e->getMessage());
            }
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
