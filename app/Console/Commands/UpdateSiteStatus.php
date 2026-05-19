<?php

namespace App\Console\Commands;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\Publicite;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateSiteStatus extends Command
{
    protected $signature = 'app:update-site-status';

    protected $description = 'Met à jour les statuts du site';

    public function handle()
    {
        $now = Carbon::now();

        /*
        |--------------------------------------------------------------------------
        | PUBLICITÉS
        |--------------------------------------------------------------------------
        */
        $publicites = Publicite::whereIn('type', ['top-promo', 'pack', 'annonce'])
            ->where('status', 'active')
            ->get();

        foreach ($publicites as $publicite) {

            $status_pub = $publicite->date_debut_pub > $now
                ? 'bientot'
                : ($publicite->date_fin_pub < $now ? 'termine' : 'en_cours');

            $status = $status_pub === 'termine' ? 'desactive' : 'active';

            $publicite->update([
                'status' => $status,
                'status_pub' => $status_pub
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | REMISES PRODUITS
        |--------------------------------------------------------------------------
        */
        Product::whereNotNull('montant_remise')
            ->chunk(200, function ($products) use ($now) {

                foreach ($products as $product) {

                    $status = $product->date_debut_remise > $now
                        ? 'bientot'
                        : ($product->date_fin_remise < $now ? 'termine' : 'en_cours');

                    $product->update([
                        'status_remise' => $status
                    ]);
                }
            });

        /*
        |--------------------------------------------------------------------------
        | COUPONS
        |--------------------------------------------------------------------------
        */
        Coupon::chunk(200, function ($coupons) use ($now) {

            foreach ($coupons as $coupon) {

                $status = $coupon->date_debut > $now
                    ? 'bientot'
                    : ($coupon->date_fin < $now ? 'expirer' : 'en_cours');

                $coupon->update([
                    'status' => $status
                ]);
            }
        });

        /*
        |--------------------------------------------------------------------------
        | CLIENTS (prospect / fidèle)
        |--------------------------------------------------------------------------
        */
        User::where('role', 'client')
            ->with('orders')
            ->chunk(200, function ($users) use ($now) {

                foreach ($users as $user) {

                    $type = 'prospect';

                    foreach ($user->orders as $order) {

                        if (
                            $order->date_order &&
                            Carbon::parse($order->date_order)->isCurrentMonth()
                        ) {
                            $type = 'fidele';
                            break;
                        }
                    }

                    $user->update([
                        'type_client' => $type
                    ]);
                }
            });

        /*
        |--------------------------------------------------------------------------
        | ANNIVERSAIRES (SAFE)
        |--------------------------------------------------------------------------
        */
        User::whereNotNull('date_anniversaire')
            ->chunk(200, function ($users) use ($now) {

                foreach ($users as $user) {

                    try {

                        if (!str_contains($user->date_anniversaire, '-')) {
                            continue;
                        }

                        [$day, $month] = explode('-', $user->date_anniversaire);

                        if (!is_numeric($day) || !is_numeric($month)) {
                            continue;
                        }

                        $birthday = Carbon::createFromDate(
                            $now->year,
                            (int) $month,
                            (int) $day
                        )->endOfDay();

                        $days_left = $now->diffInDays($birthday, false);

                        $user->update([
                            'notify_birthday' => $days_left
                        ]);
                    } catch (\Exception $e) {

                        Log::warning('Date anniversaire invalide ignorée', [
                            'user_id' => $user->id,
                            'value' => $user->date_anniversaire
                        ]);
                    }
                }
            });

        $this->info('Statuts du site mis à jour avec succès.');
    }
}
