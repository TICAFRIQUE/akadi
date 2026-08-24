<?php

namespace App\Providers;

require_once __DIR__ . '/../Helpers/product_alert.php';

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Publicite;
use App\Models\SubCategory;
use App\Models\ProductBase;
use App\Models\MenuSemaine;
use App\Observers\OrderObserver;
use App\Observers\ProductObserver;
use App\Observers\ProductBaseObserver;
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

    // public function boot(): void
    // {
    //     DB::statement("SET lc_time_names = 'fr_FR'");

    //     // ── Vues FRONT (site public) ─────────────────────────────────────────────
    //     View::composer('site.*', function ($view) {
    //         $categories = Cache::remember('front_categories', 300, fn() =>
    //             Category::with([
    //                 'products' => fn($q) => $q->whereDisponibilite(1)->latest()->take(10),
    //                 'media',
    //                 'subcategories',
    //             ])->whereNotIn('name', ['Pack'])->active()->latest()->get()
    //         );

    //         $subcategory = Cache::remember('front_subcategories', 300, fn() =>
    //             SubCategory::with(['products', 'media', 'category'])->orderBy('name')->get()
    //         );

    //         $annonce = Cache::remember('annonce_active', 120, fn() =>
    //             Publicite::with('media')->whereType('annonce')->whereStatus('active')->first()
    //         );

    //         $view->with(compact('categories', 'subcategory', 'annonce'));
    //     });

    //     // ── Vues ADMIN (backoffice) ──────────────────────────────────────────────
    //     View::composer('admin.*', function ($view) {
    //         $category_backend = Cache::remember('admin_categories', 120, fn() =>
    //             Category::with(['products', 'media', 'subcategories'])->latest()->get()
    //         );

    //         $roleWithoutClient = Cache::remember('roles_without_client', 600, fn() =>
    //             Role::whereNotIn('name', ['developpeur', 'client', 'fidele', 'prospect'])->get()
    //         );

    //         $annonce = Cache::remember('annonce_active', 120, fn() =>
    //             Publicite::with('media')->whereType('annonce')->whereStatus('active')->first()
    //         );

    //         $productBases = Cache::remember('product_bases_list', 120, fn() =>
    //             ProductBase::orderBy('nom', 'ASC')->get()
    //         );

    //         // Commandes récentes : courte durée, données critiques
    //         $orders_new = Cache::remember('orders_new', 30, fn() =>
    //             Order::whereIn('status', ['attente', 'precommande'])->latest()->limit(100)->get()
    //         );
    //         $orders_attente = $orders_new->where('status', 'attente')->values();

    //         // Anniversaires : données peu changeantes
    //         $user_upcoming_birthday = Cache::remember('users_birthday_upcoming', 3600, fn() =>
    //             User::whereIn('notify_birthday', [2, 1])->get()
    //         );
    //         $user_birthday = Cache::remember('users_birthday_today', 3600, fn() =>
    //             User::where('notify_birthday', 0)->get()
    //         );

    //         $nb_product_alertes = Cache::remember('nb_product_alertes', 120, fn() =>
    //             count_product_alertes()
    //         );

    //         $view->with(compact(
    //             'annonce',
    //             'category_backend',
    //             'roleWithoutClient',
    //             'orders_attente',
    //             'orders_new',
    //             'user_upcoming_birthday',
    //             'user_birthday',
    //             'productBases',
    //             'nb_product_alertes',
    //         ));
    //     });
    // }



    public function boot(): void
    {
        DB::statement("SET lc_time_names = 'fr_FR'");

        // ── Observers ─────────────────────────────────────────────────────────────
        // Order::observe(OrderObserver::class);
        Product::observe(ProductObserver::class);
        ProductBase::observe(ProductBaseObserver::class);

        // ── Vues FRONT (site public) ─────────────────────────────────────────────
        View::composer('site.*', function ($view) {
            static $front = null;

            if ($front === null) {
                $front = [
                    // 'categories' => Cache::remember(
                    //     'front_categories',
                    //     300,
                    //     fn() =>
                    //     Category::with([
                    //         'products' => fn($q) => $q->whereDisponibilite(1)->latest()->take(10),
                    //         'media',
                    //         'subcategories',
                    //     ])->whereNotIn('name', ['Pack'])->active()->latest()->get()
                    // ),


                    'categories' => Cache::remember(
                        'front_categories',
                        300,
                        fn() =>
                        Category::with([
                            'products' => fn($q) => $q->whereDisponibilite(1)->with(['media', 'categories', 'subcategorie'])->latest()->take(10),
                            'media',
                        ])
                            ->whereNotIn('name', ['Pack'])
                            ->whereHas('products', fn($q) => $q->whereDisponibilite(1))
                            ->active()
                            ->latest()
                            ->get()
                    ),

                    'subcategory' => Cache::remember(
                        'front_subcategories',
                        300,
                        fn() =>
                        SubCategory::with(['products', 'media', 'category'])->orderBy('name')->get()
                    ),

                    // with('media') retiré : Spatie le charge automatiquement
                    // et causait des requêtes répétées à chaque @include
                    'annonce' => Cache::remember(
                        'annonce_active',
                        120,
                        fn() =>
                        Publicite::whereType('annonce')->whereStatus('active')->first()
                    ),

                    // Carte menu de la semaine actuellement en cours (remplace l'ancien "menu du jour")
                    'menuSemaineActive' => Cache::remember(
                        'menu_semaine_active_' . today()->toDateString(),
                        300,
                        fn() =>
                        MenuSemaine::with(['menusJour' => fn($q) => $q->where('actif', true)->orderBy('date'),
                            'menusJour.menuProduits' => fn($q) => $q->where('disponible', true)])
                            ->where('actif', true)
                            ->whereDate('date_fin', '>=', today())
                            ->orderBy('date_debut')
                            ->first()
                    ),
                ];
            }

            // Propre à la session courante : jamais mis en cache comme le reste de $front ci-dessus.
            $front['cartMenuSemaineJours'] = count(session('cart_menu_semaine.jours', []));

            $view->with($front);
        });

        // ── Vues ADMIN (backoffice) ──────────────────────────────────────────────
        View::composer('admin.*', function ($view) {
            static $admin = null;

            if ($admin === null) {
                $orders_new = Cache::remember(
                    'orders_new',
                    30,
                    fn() =>
                    // Order::whereIn('status', ['attente', 'precommande'])->latest()->limit(100)->get()

                    Order::orderBy('created_at', 'DESC')
                        // ->where('source', 'web')
                        ->where('payment_status', 'completed')
                        // Les commandes issues d'une réservation menu de la semaine ont leur propre
                        // notification (voir orders_menu_new ci-dessous), pas de doublon ici.
                        ->whereNull('menu_semaine_reservation_id')
                        ->where(function ($query) {
                            // Commandes en attente normale : toujours affichées
                            $query->where('status', 'attente')
                                // Précommandes : uniquement si la date prévue est aujourd'hui ou passée
                                ->orWhere(function ($q) {
                                    $q->where('status', 'precommande')
                                        ->whereDate('delivery_planned', '<=', now()->format('Y-m-d'));
                                });
                        })
                        ->latest()->limit(100)->get()
                );

                // Commandes issues d'une réservation "menu de la semaine", payées et pas encore livrées.
                $orders_menu_new = Cache::remember(
                    'orders_menu_new',
                    30,
                    fn() =>
                    Order::whereNotNull('menu_semaine_reservation_id')
                        ->where('payment_status', 'completed')
                        ->where('status', '!=', Order::STATUS_LIVREE)
                        ->latest()->limit(100)->get()
                );

                $admin = [
                    'category_backend' => Cache::remember(
                        'admin_categories',
                        120,
                        fn() =>
                        Category::with(['products', 'media', 'subcategories'])->latest()->get()
                    ),

                    'roleWithoutClient' => Cache::remember(
                        'roles_without_client',
                        600,
                        fn() =>
                        Role::whereNotIn('name', ['developpeur', 'client', 'fidele', 'prospect'])->get()
                    ),

                    // with('media') retiré ici aussi pour la même raison
                    'annonce' => Cache::remember(
                        'annonce_active',
                        120,
                        fn() =>
                        Publicite::whereType('annonce')->whereStatus('active')->first()
                    ),

                    // orders_new chargé avant le tableau pour pouvoir
                    // dériver orders_attente sans requête supplémentaire
                    'orders_new'      => $orders_new,
                    'orders_attente'  => $orders_new->where('status', 'attente')->values(),
                    'orders_menu_new' => $orders_menu_new,

                    'user_upcoming_birthday' => Cache::remember(
                        'users_birthday_upcoming',
                        3600,
                        fn() =>
                        User::whereIn('notify_birthday', [2, 1])->get()
                    ),

                    'user_birthday' => Cache::remember(
                        'users_birthday_today',
                        3600,
                        fn() =>
                        User::where('notify_birthday', 0)->get()
                    ),

                    'nb_product_alertes' => Cache::remember(
                        'nb_product_alertes',
                        120,
                        fn() =>
                        count_product_alertes()
                    ),
                ];
            }

            $view->with($admin);
        });
    }
}
