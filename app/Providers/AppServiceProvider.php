<?php

namespace App\Providers;

use App\Models\Category;
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
        //category frontend
        $category = Category::with(['products' =>fn($q)=>$q->orderBy('created_at', 'DESC')->get()
        ,'media', 'subcategories'])
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



        View::composer('*', function ($view) use ($category, $subcategory, $roles,$category_backend) {
            $view->with([
                'categories' => $category,
                'subcategories' => $subcategory,
                'roles' => $roles,
                'category_backend' => $category_backend,

            ]);
        });
    }
}
