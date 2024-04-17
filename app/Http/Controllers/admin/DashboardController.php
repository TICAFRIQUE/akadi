<?php

namespace App\Http\Controllers\admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //home dashboard
    public function index()
    {
        // get user birthday 

     


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

        ############################## STATISTIQUE ORDER ###########
        // statistic order
        $orders_days = Order::orderBy('created_at', 'DESC')
            ->whereDay('date_order', Carbon::now()->day)
            ->count();

        $orders_month = Order::orderBy('created_at', 'DESC')
            ->whereMonth('date_order', Carbon::now()->month)
            ->count();

        $orders_year = Order::orderBy('created_at', 'DESC')
            ->whereYear('date_order', Carbon::now()->year)
            ->count();

        //orders semaine

        $startWeek = Carbon::now()->subWeek()->startOfWeek();
        $endWeek   = Carbon::now()->subWeek()->endOfWeek();


        $orders_week = Order::orderBy('created_at', 'DESC')
            ->whereBetween('date_order', [$startWeek, $endWeek])->count();



        #################################### CHIFFRE AFFAIRE ###################
        // Chiffre affaire
        $ca_days = Order::orderBy('created_at', 'DESC')
            ->whereDay('date_order', Carbon::now()->day)
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
            ->with('orders', fn ($q) => $q->whereNotIn('status', ['annulée']))
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




        return view('admin.admin', compact(

            //user birthday
            // 'user_upcoming_birthday',
            // 'user_birthday',
            'orders_attente',
            'orders',
            'products',
            'users',
            'orders_new',

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


        ));
    }
}
