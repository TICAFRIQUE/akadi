<?php
namespace App\Providers;
require_once __DIR__ . '/../Helpers/product_alert.php';
use App\Models\User;
use App\Models\Order;
use App\Models\Category;
use App\Models\Publicite;
use App\Models\SubCategory;
use App\Models\ProductBase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\View;
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

 

    public function boot(): void
    {
        DB::statement("SET lc_time_names = 'fr_FR'");

        // Composer partagé pour les vues
        View::composer('*', function ($view) {

            $category = Category::with([
                'products' => fn($q) => $q->whereDisponibilite(1)->latest(),
                'media',
                'subcategories',
            ])->whereNotIn('name', ['Pack'])->active()->latest()->get();

            $category_backend = Category::with([
                'products' => fn($q) => $q->latest(),
                'media',
                'subcategories',
            ])->latest()->get();

            $subcategory = SubCategory::with([
                'products',
                'media',
                'category'
            ])->orderBy('name')->get();

            // $roles = Role::get();

            $roleWithoutClient = Role::whereNotIn('name', [
                'developpeur',
                'client',
                'fidele',
                'prospect'
            ])->get();


            $orders_attente = Order::where('status', 'attente')->latest()->get();
            $orders_new = Order::whereIn('status', ['attente', 'precommande'])->latest()->get();

            $annonce = Publicite::with('media')->whereType('annonce')->whereStatus('active')->first();

            $user_upcoming_birthday = User::whereIn('notify_birthday', [2, 1])->get();
            $user_birthday = User::where('notify_birthday', 0)->get();

            // Produits de base pour la gestion des achats et stocks
            $productBases = ProductBase::orderBy('nom', 'ASC')->get();

            // dd($category->toArray());

            $nb_product_alertes = count_product_alertes();
            $view->with([
                'annonce' => $annonce,
                'categories' => $category,
                'subcategories' => $subcategory,
                // 'roles' => $roles,
                'roleWithoutClient' => $roleWithoutClient,
                'category_backend' => $category_backend,
                'orders_attente' => $orders_attente,
                'orders_new' => $orders_new,
                'user_upcoming_birthday' => $user_upcoming_birthday,
                'user_birthday' => $user_birthday,
                'productBases' => $productBases,
                'nb_product_alertes' => $nb_product_alertes,
            ]);
        });
    }
}
