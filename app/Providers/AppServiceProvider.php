<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Category;
use App\Models\Publicite;
use App\Models\Collection;
use App\Models\SubCategory;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */


    // public function boot(): void
    // {
    //     DB::statement("SET lc_time_names = 'fr_FR'");

    //     /**************************** Start Publicite ********************************/
    //     $publicite = Publicite::whereIn('type', ['top-promo' , 'pack' , 'annonce'])->whereStatus('active')->first();

    //     if ($publicite) {
    //         $status_pub = ''; // bientot , en cours, termine
    //         $status = '';  // active , desactive

    //         if ($publicite->date_debut_pub > Carbon::now()) {
    //             $status_pub = 'bientot';
    //             $status = 'active';
    //         } elseif ($publicite->date_fin_pub < Carbon::now()) {
    //             $status_pub = 'termine';
    //             $status = 'desactive';
    //         } else {
    //             $status_pub = 'en_cour';
    //             $status = 'active';
    //         }

    //         // Update status in batch for all active promos
    //         Publicite::whereIn('type', ['top-promo' , 'pack' , 'annonce'])->whereStatus('active')
    //             ->update([
    //                 'status' => $status,
    //                 'status_pub' => $status_pub,
    //             ]);
    //     }
    //     /**************************** End Publicite ********************************/

    //     /**************************** Start Product Remise **************************/
    //     $remise = Product::whereNotNull('montant_remise')->get();

    //     foreach ($remise as $value) {
    //         $status_remise = ''; // bientot , en cours, termine

    //         if ($value->date_debut_remise > Carbon::now()) {
    //             $status_remise = 'bientot';
    //         } elseif ($value->date_fin_remise < Carbon::now()) {
    //             $status_remise = 'termine';
    //         } else {
    //             $status_remise = 'en cour';
    //         }

    //         // Update product remise status
    //         $value->update(['status_remise' => $status_remise]);
    //     }
    //     /**************************** End Product Remise ****************************/

    //     /**************************** Start Coupon Reduction ***********************/
    //     $coupons = Coupon::get();

    //     foreach ($coupons as $value) {
    //         $status_coupon = ''; // bientot , en cours, termine

    //         if ($value->date_debut > Carbon::now()) {
    //             $status_coupon = 'bientot';
    //         } elseif ($value->date_fin < Carbon::now()) {
    //             $status_coupon = 'expirer';
    //         } else {
    //             $status_coupon = 'en_cours';
    //         }

    //         // Update coupon status
    //         $value->update(['status' => $status_coupon]);
    //     }

    //     // Optionally, delete expired coupons
    //     // Coupon::where('status', 'terminer')->delete();
    //     /**************************** End Coupon Reduction ***********************/

    //     /**************************** Update User Role *****************************/
    //     $now = Carbon::now()->format('m');

    //     $users = User::withCount('orders')
    //         ->whereNotIn('role', ['developpeur', 'administrateur', 'gestionnaire'])
    //         ->orderBy('created_at', 'DESC')
    //         ->get();

    //     foreach ($users as $user) {
    //         if ($user->orders_count == 0) {
    //             $user->update(['role' => 'prospect']);
    //         }

    //         foreach ($user->orders as $order) {
    //             $order_month = Carbon::parse($order->date_order)->format('m');
    //             if ($order_month == $now) {
    //                 $user->update(['role' => 'fidele']);
    //             }
    //         }
    //     }
    //     /**************************** End Update User Role ***********************/

    //     /**************************** Notify Birthday ********************************/
    //     $now = Carbon::now()->endOfDay();
    //     $users = User::all();

    //     foreach ($users as $user) {
    //         $next_birthday = Carbon::parse($user->date_anniversaire . '-' . date('Y'))->endOfDay();
    //         $date_diff = $now->diffInDays($next_birthday, false);

    //         $user->update(['notify_birthday' => $date_diff]);
    //     }

    //     $user_upcoming_birthday = User::whereIn('notify_birthday', [2, 1])->get(); // Birthday in 2 days or 1 day
    //     $user_birthday = User::where('notify_birthday', 0)->get(); // Birthday today
    //     /**************************** End Notify Birthday **************************/

    //     /**************************** Category Fetching *****************************/
    //     $category = Category::with([
    //         'products' => fn($q) => $q->whereDisponibilite(1)->orderByDesc('created_at'),
    //         'media',
    //         'subcategories',
    //     ])
    //         ->whereNotIn('name', ['Pack'])
    //         ->orderByDesc('created_at')
    //         ->get();

    //     $category_backend = Category::with([
    //         'products' => fn($q) => $q->orderByDesc('created_at'),
    //         'media',
    //         'subcategories',
    //     ])
    //         ->orderByDesc('created_at')
    //         ->get();

    //     $subcategory = SubCategory::with(['products', 'media', 'category'])->orderBy('name', 'ASC')->get();
    //     /**************************** End Category Fetching *************************/

    //     /**************************** Roles Fetching ********************************/
    //     $roles = Role::where('name', '!=', 'developpeur')->get();
    //     $roleWithoutClient = Role::whereNotIn('name', ['developpeur', 'client', 'fidele', 'prospect'])->get();
    //     /**************************** End Roles Fetching ****************************/

    //     /**************************** Orders Fetching ******************************/
    //     $orders_attente = Order::whereIn('status', ['attente'])->orderByDesc('created_at')->get();
    //     $orders_new = Order::whereIn('status', ['attente', 'precommande'])->orderByDesc('created_at')->get();
    //     /**************************** End Orders Fetching **************************/

    //     /**************************** Publicite Information ************************/
    //     $annonce = Publicite::with('media')->whereType('annonce')->whereStatus('active')->first();
    //     /**************************** End Publicite Information ********************/

    //     View::composer('*', function ($view) use (
    //         $category,
    //         $subcategory,
    //         $roles,
    //         $category_backend,
    //         $orders_attente,
    //         $orders_new,
    //         $roleWithoutClient,
    //         $annonce,
    //         $user_upcoming_birthday,
    //         $user_birthday
    //     ) {
    //         $view->with([
    //             'annonce' => $annonce,
    //             'categories' => $category,
    //             'subcategories' => $subcategory,
    //             'roles' => $roles,
    //             'roleWithoutClient' => $roleWithoutClient,
    //             'category_backend' => $category_backend,
    //             'orders_attente' => $orders_attente,
    //             'orders_new' => $orders_new,
    //             'user_upcoming_birthday' => $user_upcoming_birthday,
    //             'user_birthday' => $user_birthday,
    //         ]);
    //     });
    // }



    //  public function boot(): void
    // {
    //     DB::statement("SET lc_time_names = 'fr_FR'");


    // }



    public function boot(): void
    {
        DB::statement("SET lc_time_names = 'fr_FR'");

        // Composer partagé pour les vues
        View::composer('*', function ($view) {
            $category = Cache::remember('categories', 3600, function () {
                return Category::with([
                    'products' => fn($q) => $q->whereDisponibilite(1)->latest(),
                    'media',
                    'subcategories',
                ])->whereNotIn('name', ['Pack'])->latest()->get();
            });

            $category_backend = Cache::remember('categories_backend', 3600, function () {
                return Category::with([
                    'products' => fn($q) => $q->latest(),
                    'media',
                    'subcategories',
                ])->latest()->get();
            });

            $subcategory = Cache::remember('subcategories', 3600, function () {
                return SubCategory::with(['products', 'media', 'category'])->orderBy('name')->get();
            });

            $roles = Cache::remember('roles', 3600, fn() => Role::where('name', '!=', 'developpeur')->get());
            $roleWithoutClient = Cache::remember(
                'roles_without_client',
                3600,
                fn() =>
                Role::whereNotIn('name', ['developpeur', 'client', 'fidele', 'prospect'])->get()
            );

            $orders_attente = Order::where('status', 'attente')->latest()->get();
            $orders_new = Order::whereIn('status', ['attente', 'precommande'])->latest()->get();

            $annonce = Publicite::with('media')->whereType('annonce')->whereStatus('active')->first();

            $user_upcoming_birthday = User::whereIn('notify_birthday', [2, 1])->get();
            $user_birthday = User::where('notify_birthday', 0)->get();

            $view->with([
                'annonce' => $annonce,
                'categories' => $category,
                'subcategories' => $subcategory,
                'roles' => $roles,
                'roleWithoutClient' => $roleWithoutClient,
                'category_backend' => $category_backend,
                'orders_attente' => $orders_attente,
                'orders_new' => $orders_new,
                'user_upcoming_birthday' => $user_upcoming_birthday,
                'user_birthday' => $user_birthday,
            ]);
        });
    }
}
