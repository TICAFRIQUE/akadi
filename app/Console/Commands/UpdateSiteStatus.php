<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Publicite;
use Illuminate\Console\Command;

class UpdateSiteStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-site-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Met à jour les statuts des publicités, remises, coupons, rôles utilisateurs, etc.';
    /**
     * Execute the console command.
     */
    //

    public function handle()
    {
        $now = Carbon::now();

        // Publicités
        $publicite = Publicite::whereIn('type', ['top-promo', 'pack', 'annonce'])->whereStatus('active')->first();
        if ($publicite) {
            $status_pub = $publicite->date_debut_pub > $now ? 'bientot' : ($publicite->date_fin_pub < $now ? 'termine' : 'en_cours');
            $status = $status_pub === 'termine' ? 'desactive' : 'active';

            Publicite::whereIn('type', ['top-promo', 'pack', 'annonce'])->whereStatus('active')
                ->update(['status' => $status, 'status_pub' => $status_pub]);
        }

        // Remises produits
        Product::whereNotNull('montant_remise')->each(function ($product) use ($now) {
            $status = $product->date_debut_remise > $now ? 'bientot' : ($product->date_fin_remise < $now ? 'termine' : 'en_cours');
            $product->update(['status_remise' => $status]);
        });

        // Coupons
        Coupon::all()->each(function ($coupon) use ($now) {
            $status = $coupon->date_debut > $now ? 'bientot' : ($coupon->date_fin < $now ? 'expirer' : 'en_cours');
            $coupon->update(['status' => $status]);
        });

        // Utilisateurs : mise à jour rôle
        User::withCount('orders')
            ->whereNotIn('role', ['developpeur', 'administrateur', 'gestionnaire'])
            ->get()
            ->each(function ($user) use ($now) {
                if ($user->orders_count == 0) {
                    $user->update(['role' => 'prospect']);
                }

                foreach ($user->orders as $order) {
                    if (Carbon::parse($order->date_order)->format('m') == $now->format('m')) {
                        $user->update(['role' => 'fidele']);
                        break;
                    }
                }
            });

        // Anniversaires
        User::all()->each(function ($user) use ($now) {
            $birthday = Carbon::parse($user->date_anniversaire . '-' . date('Y'))->endOfDay();
            $days_left = $now->diffInDays($birthday, false);
            $user->update(['notify_birthday' => $days_left]);
        });

        $this->info('Statuts du site mis à jour avec succès.');
    }
}
