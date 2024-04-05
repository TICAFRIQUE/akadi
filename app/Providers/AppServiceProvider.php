<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Publicite;
use App\Models\Collection;
use App\Models\SubCategory;
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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        /****************************start publicite */

        //declenchez la promo publicitaire
        $publicite = Publicite::whereType('top-promo')->whereStatus('active')->first();

        if ($publicite) {
            $status_pub = ''; // bientot , en cour, termine
            $status = '';  // active ,  desactive
            if ($publicite['date_debut_pub'] > Carbon::now()) {
                $status_pub = 'bientot';
                $status = 'active';
            } elseif ($publicite['date_fin_pub'] < Carbon::now()) {
                $status_pub = 'termine';
                $status = 'desactive';
            } else {
                $status_pub = 'en cour';
                $status = 'active';
            }


            Publicite::whereType('top-promo')->whereStatus('active')
                ->update([
                    'status' => $status,
                    'status_pub' => $status_pub,
                ]);
        }

        /****************************end publicite */




        /****************************start product remise */

        //declenchez la promo product
        $remise = Product::where('montant_remise', '!=', null)->get();

        if ($remise) {
            $status_remise = ''; // bientot , en cour, termine
            $montant_remise = '';
            $date_debut_remise = '';
            $date_fin_remise = '';

            foreach ($remise as $value) {
                if ($value['date_debut_remise'] > Carbon::now()) {
                    $status_remise = 'bientot';
                } elseif ($value['date_fin_remise'] < Carbon::now()) {
                    $status_remise = 'termine';
                } else {
                    $status_remise = 'en cour';
                }

                Product::whereId($value['id'])
                    ->update([
                        'status_remise' => $status_remise,
                    ]);
            }
        }

        /****************************end product remise */




        //category frontend
        $category = Category::with([
            'products' => fn ($q) => $q->whereDisponibilite(1)->orderBy('created_at', 'DESC')->get(), 'media', 'subcategories'
        ])
            ->whereNotIn('name', ['Pack'])
            ->orderBy('created_at', 'DESC')->get();
        // dd($category->toArray());

        //category backend
        $category_backend = Category::with([
            'products' => fn ($q) => $q->orderBy('created_at', 'DESC')->get(), 'media', 'subcategories'
        ])
            ->orderBy('created_at', 'DESC')->get();
        // dd($category->toArray());

        $subcategory = SubCategory::with(['products', 'media', 'category'])->orderBy('name', 'ASC')->get();



        $roles = Role::where('name', '!=', 'developpeur')->get();


        /*********************ORDER GET */

        $orders_attente = Order::orderBy('created_at', 'DESC')
            ->whereIn('status', ['attente'])
            ->get();


        $orders_new = Order::orderBy('created_at', 'DESC')
            ->whereIn('status', ['attente', 'precommande'])
            ->get();

        View::composer('*', function ($view) use (
            $category,
            $subcategory,
            $roles,
            $category_backend,
            $orders_attente,
            $orders_new,
        ) {
            $view->with([
                'categories' => $category,
                'subcategories' => $subcategory,
                'roles' => $roles,
                'category_backend' => $category_backend,
                'orders_attente' => $orders_attente,
                'orders_new' => $orders_new,

            ]);
        });
    }
}
