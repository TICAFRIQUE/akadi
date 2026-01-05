<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\TicAfriqueService;

class TestApiController extends Controller
{
  /**
   * Test Api SMS.
   *
   * @return \Illuminate\Http\Response
   */
  public function smsApi()
  {
      $smsService = new TicAfriqueService();

      $order = Order::with('user')->first();
      $smsService->sendOrderConfirmationSms($order);
  }
}
