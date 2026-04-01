<?php
namespace App\Providers;
require_once __DIR__ . '/../Helpers/product_alert.php';
use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use App\Models\Publicite;
use App\Models\SubCategory;
use App\Models\ProductBase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        DB::statement("SET lc_time_names = 'fr_FR'");

        // ── Vues FRONT (site public) ─────────────────────────────────────────────
        View::composer('site.*', function ($view) {
            $categories = Cache::remember('front_categories', 300, fn() =>
                Category::with([
                    'products' => fn($q) => $q->whereDisponibilite(1)->latest(),
                    'media',
                    'subcategories',
                ])->whereNotIn('name', ['Pack'])->active()->latest()->get()
            );

            $subcategory = Cache::remember('front_subcategories', 300, fn() =>
                SubCategory::with(['products', 'media', 'category'])->orderBy('name')->get()
            );

            $annonce = Cache::remember('annonce_active', 120, fn() =>
                Publicite::with('media')->whereType('annonce')->whereStatus('active')->first()
            );

            $view->with(compact('categories', 'subcategory', 'annonce'));
        });

        // ── Vues ADMIN (backoffice) ──────────────────────────────────────────────
        View::composer('admin.*', function ($view) {
            $category_backend = Cache::remember('admin_categories', 120, fn() =>
                Category::with(['products', 'media', 'subcategories'])->latest()->get()
            );

            $roleWithoutClient = Cache::remember('roles_without_client', 600, fn() =>
                Role::whereNotIn('name', ['developpeur', 'client', 'fidele', 'prospect'])->get()
            );

            $annonce = Cache::remember('annonce_active', 120, fn() =>
                Publicite::with('media')->whereType('annonce')->whereStatus('active')->first()
            );

            $productBases = Cache::remember('product_bases_list', 120, fn() =>
                ProductBase::orderBy('nom', 'ASC')->get()
            );

            // Commandes récentes : courte durée, données critiques
            $orders_new = Cache::remember('orders_new', 30, fn() =>
                Order::whereIn('status', ['attente', 'precommande'])->latest()->limit(100)->get()
            );
            $orders_attente = $orders_new->where('status', 'attente')->values();

            // Anniversaires : données peu changeantes
            $user_upcoming_birthday = Cache::remember('users_birthday_upcoming', 3600, fn() =>
                User::whereIn('notify_birthday', [2, 1])->get()
            );
            $user_birthday = Cache::remember('users_birthday_today', 3600, fn() =>
                User::where('notify_birthday', 0)->get()
            );

            $nb_product_alertes = Cache::remember('nb_product_alertes', 120, fn() =>
                count_product_alertes()
            );

            $view->with(compact(
                'annonce',
                'category_backend',
                'roleWithoutClient',
                'orders_attente',
                'orders_new',
                'user_upcoming_birthday',
                'user_birthday',
                'productBases',
                'nb_product_alertes',
            ));
        });
    }
}

