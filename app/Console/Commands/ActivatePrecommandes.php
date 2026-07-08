<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Console\Command;

class ActivatePrecommandes extends Command
{
    protected $signature   = 'orders:activate-precommandes';
    protected $description = 'Passe en "attente" les précommandes dont la date prévue est arrivée';

    public function handle(): int
    {
        $orders = Order::where('status', Order::STATUS_PRECOMMANDE)
            ->whereDate('delivery_planned', '<=', now())
            ->get();

        if ($orders->isEmpty()) {
            $this->info('Aucune précommande à activer.');
            return self::SUCCESS;
        }

        foreach ($orders as $order) {
            Order::whereId($order->id)->update(['status' => Order::STATUS_ATTENTE]);

            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'old_status' => Order::STATUS_PRECOMMANDE,
                'new_status' => Order::STATUS_ATTENTE,
                'user_id'    => null, // action système
            ]);
        }

        $this->info("{$orders->count()} précommande(s) passées en attente.");
        return self::SUCCESS;
    }
}
