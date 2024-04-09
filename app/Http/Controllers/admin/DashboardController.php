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


        // statistic order
        $orders_days = Order::orderBy('created_at', 'DESC')
            ->whereDay('date_order', Carbon::now()->day)
            ->get();

        $orders_month = Order::orderBy('created_at', 'DESC')
            ->whereMonth('date_order', Carbon::now()->month)
            ->get();

        $orders_year = Order::orderBy('created_at', 'DESC')
            ->whereYear('date_order', Carbon::now()->year)
            ->get();

        //orders semaine

        $startWeek = Carbon::now()->subWeek()->startOfWeek();
        $endWeek   = Carbon::now()->subWeek()->endOfWeek();


        $orders_week = Order::orderBy('created_at', 'DESC')
            ->whereBetween('date_order', [$startWeek, $endWeek])->get();


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


        //    dd($ca_days);




        return view('admin.admin', compact(
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


        ));
    }
}
