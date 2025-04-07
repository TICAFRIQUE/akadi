<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Depense;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class DashboardController extends Controller
{
    //home dashboard
    public function index()
    {
        // get user birthday 



       if (Config::get('app.env') == 'production') {
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
        $mail->addAddress('Restaurantakadi@gmail.com');

        $mail->isHTML(true);


        $mail->Subject = 'Anniversaire';
        $mail->Body =
            '<b> Bonjour Akadi, C\'est bientot l\'anniversaire de vos client  <br> Veuillez consulter les notifications sur le dashboard  <b>';

        $mail->send();

       }

        $orders_attente = Order::orderBy('created_at', 'DESC')
            ->whereIn('status', ['attente'])
            ->get();


        $orders_new = Order::orderBy('created_at', 'DESC')
            ->whereIn('status', ['attente', 'precommande'])
            ->get();





        //statistic
        //count all orders
        $orders = Order::count();
        //count all products
        $products = Product::count();
        //count all user  
        $users = User::count();
        //montant des depenses
        $depenses = Depense::sum('montant');

        ############################## STATISTIQUE ORDER ###########
        // statistic order
        $orders_days = Order::orderBy('created_at', 'DESC')
            ->where('date_order', Carbon::now()->format('Y-m-d'))
            ->whereStatus('livrée')
            ->count();

        // dd($orders_days);

        $orders_month = Order::orderBy('created_at', 'DESC')
            ->whereMonth('date_order', Carbon::now()->month)
            ->whereStatus('livrée')
            ->count();

        $orders_year = Order::orderBy('created_at', 'DESC')
            ->whereYear('date_order', Carbon::now()->year)
            ->whereStatus('livrée')
            ->count();

        //orders semaine

        $startWeek =  Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endWeek =  Carbon::now()->endOfWeek(Carbon::SUNDAY);



        $orders_week = Order::orderBy('created_at', 'DESC')
            ->whereBetween('date_order', [$startWeek, $endWeek])
            ->whereStatus('livrée')
            ->count();



        #################################### CHIFFRE AFFAIRE ###################
        // Chiffre affaire
        $ca_days = Order::orderBy('created_at', 'DESC')
            ->where('date_order', Carbon::today())
            ->whereStatus('livrée')
            ->sum('total');


        $ca_week = Order::orderBy('created_at', 'DESC')
            ->whereBetween('date_order', [$startWeek, $endWeek])
            ->whereStatus('livrée')
            ->sum('total');

        $ca_month = Order::orderBy('created_at', 'DESC')
            ->whereMonth('date_order', Carbon::now()->month)
            ->whereStatus('livrée')
            ->sum('total');

        $ca_year = Order::orderBy('created_at', 'DESC')
            ->whereYear('date_order', Carbon::now()->year)
            ->whereStatus('livrée')
            ->sum('total');

        ####################### TOP CLIENT#####################
        //meilleur client
        $top_user_order = User::withCount('orders')
            ->with('orders', fn($q) => $q->whereStatus('livrée'))
            ->having('orders_count', '>', 0)
            ->orderBy('orders_count', 'DESC')->take(5)->get();


        ####################### TYPE DE CLIENT#####################
        //client fidele
        $client_fidele = User::withCount('orders')
            ->where('role', 'fidele')->get()->count();

        //client fidele
        $client_prospect = User::withCount('orders')
            ->where('role', 'prospect')->count();

        // dd($top_user_order->toArray());



        ####################### Statistic product #####################
        //meilleur produit
        $top_product_order = Product::withCount('orders')
            ->with('orders', fn($q) => $q->whereNotIn(
                'status',
                ['annulée']
            ))
            ->having('orders_count', '>', 0)
            ->orderBy('orders_count', 'DESC')->take(5)->get();


        // dd($top_product_order->toArray());
        return view('admin.admin', compact(

            //user birthday
            // 'user_upcoming_birthday',
            // 'user_birthday',
            'orders_attente',
            'orders',
            'products',
            'users',
            'orders_new',
            'depenses',

            //order
            'orders_days',
            'orders_week',
            'orders_month',
            'orders_year',

            //chiffre affaire
            'ca_days',
            'ca_week',
            'ca_month',
            'ca_year',

            //top user order
            'top_user_order',

            //type client
            'client_prospect',
            'client_fidele',

            //product
            'top_product_order',


        ));
    }

    public function checkNewOrder()
    {

        $orders_new = Order::orderBy('created_at', 'DESC')
            ->whereIn('status', ['attente', 'precommande'])
            ->get();

        return response()->json([
            'orders_new' => $orders_new->map(function ($order) {
                return [
                    'id' => $order->id,
                    'code' => $order->code,
                    'status' => $order->status,
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    'created_at_human' => Carbon::parse($order->created_at)->diffForHumans(),
                ];
            }),
            'count' => $orders_new->count() // Ajoute le nombre total des nouvelles commandes
        ]);
    }

    public function product_statistic(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        //meilleur produit

        //if $startDate and endDate with eloquent

        if ($startDate && $endDate) {
            $top_product_order = Product::whereHas(
                'orders',
                fn($q) => $q->whereBetween('date_order', [$startDate, $endDate])->whereStatus('livrée')
            )
                ->withCount('orders')
                ->having('orders_count', '>', 0)
                ->orderBy('orders_count', 'DESC')->get();
        } else {

            $top_product_order = Product::withCount('orders')
                ->with('orders', fn($q) => $q->whereStatus('livrée'))
                ->having('orders_count', '>', 0)
                ->orderBy('orders_count', 'DESC')->get();
            // dd($top_product_order->toArray());

        }


        //Number of products per category
        $product_per_category = Category::withCount('products')->orderBy('products_count', 'DESC')->get();



        return response()->json([
            'status' => 'success',
            'top_product_order' => $top_product_order,
            'product_per_category' => $product_per_category,

        ]);
    }

    public function order_period(Request $request)
    {
        // count order by years
        $orders_by_year = Order::selectRaw('YEAR(date_order) as year, COUNT(*) as count')
            ->whereStatus('livrée')
            ->groupBy('year')
            ->orderBy('year', 'ASC')
            ->get();


        // count order by month
        $year = $request->input('year');

        if ($year) {
            $orders_by_month = Order::selectRaw('YEAR(date_order) as year, MONTH(date_order) as month, DATE_FORMAT(date_order, "%M") as month_name, COUNT(*) as count')
                ->whereStatus('livrée')
                ->whereYear('date_order', $year)
                ->groupBy('year', 'month', 'month_name')
                ->orderBy('year', 'ASC')
                ->get();
        } else {
            $orders_by_month = Order::selectRaw('YEAR(date_order) as year, MONTH(date_order) as month, DATE_FORMAT(date_order, "%M") as month_name, COUNT(*) as count')
                ->whereStatus('livrée')
                ->groupBy('year', 'month', 'month_name')
                ->orderBy('year', 'ASC')
                ->get();
        }



        return response()->json([
            'status' => 'success',
            'orders_by_year' => $orders_by_year,
            'orders_by_month' => $orders_by_month,

        ]);
    }


    //Chiffre d'affaire

    public function revenu_period(Request $request)
    {
        // count order by years
        $revenu_by_year = Order::selectRaw('YEAR(date_order) as year, SUM(total) as total')
            ->whereStatus('livrée')
            ->groupBy('year')
            ->orderBy('year', 'ASC')
            ->get();


        // count order by month
        $year = $request->input('year');

        if ($year) {
            $revenu_by_month = Order::selectRaw('YEAR(date_order) as year, MONTH(date_order) as month, DATE_FORMAT(date_order, "%M") as month_name, SUM(total) as total')
                ->whereStatus('livrée')
                ->whereYear('date_order', $year)
                ->groupBy('year', 'month', 'month_name')
                ->orderBy('year', 'ASC')
                ->get();
        } else {
            $revenu_by_month = Order::selectRaw('YEAR(date_order) as year, MONTH(date_order) as month, DATE_FORMAT(date_order, "%M") as month_name, SUM(total) as total')
                ->whereStatus('livrée')
                ->groupBy('year', 'month', 'month_name')
                ->orderBy('year', 'ASC')
                ->get();
        }



        return response()->json([
            'status' => 'success',
            'revenu_by_year' => $revenu_by_year,
            'revenu_by_month' => $revenu_by_month,

        ]);
    }
}
