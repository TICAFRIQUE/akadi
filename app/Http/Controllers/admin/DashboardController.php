<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //home dashboard
    public function index(){
        $orders_attente = Order::orderBy('created_at','DESC')
        ->whereIn('status', ['attente', 'precommande'])
        ->get();


        //statistic
        //count all orders
        $orders=Order::count();
        //count all products
        $products= Product::count();
        //count all user  
        $users= User::count();
        return view('admin.admin',compact(
            'orders_attente',
            'orders',  
            'products',  
            'users'

        ));
      
    }

   

}
