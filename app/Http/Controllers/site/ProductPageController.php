<?php

namespace App\Http\Controllers\site;

use Exception;
use App\Models\Product;
use App\Models\Category;
use App\Models\Publicite;
use App\Models\Commentaire;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductPageController extends Controller
{


    /********** Get shop List of category  */
    // public function liste_produit(Request $request)
    // {
    //     try {
    //         $category    = $request->query('categorie');
    //         $subcategory = $request->query('sous-categorie');
    //         $name_category = '';

    //         if ($category) {
    //             $name_category = Cache::remember("category_name_{$category}", 600, fn () =>
    //                 Category::whereId($category)->select('id', 'name')->first()
    //             );

    //             $product = Cache::remember("products_by_category_{$category}", 180, fn () =>
    //                 Product::whereHas('categories',
    //                     fn ($q) => $q->where('category_product.category_id', $category)->active()
    //                 )->with(['media', 'categories', 'subcategorie'])
    //                     ->whereDisponibilite(1)
    //                     ->orderBy('created_at', 'DESC')
    //                     ->paginate(1)->withQueryString()
    //             );
    //         } elseif ($subcategory) {
    //             $name_category = Cache::remember("subcategory_name_{$subcategory}", 600, fn () =>
    //                 SubCategory::whereId($subcategory)->select('id', 'name')->first()
    //             );

    //             $product = Cache::remember("products_by_subcategory_{$subcategory}", 180, fn () =>
    //                 Product::with(['media', 'categories', 'subcategorie'])
    //                     ->where('sub_category_id', $subcategory)
    //                     ->whereDisponibilite(1)
    //                     ->orderBy('created_at', 'DESC')
    //                     ->paginate(12)->withQueryString()
    //             );
    //         } else {
    //             $product = Cache::remember('products_all_active', 180, fn () =>
    //                 Product::with(['media', 'categories', 'subcategorie'])
    //                     ->whereHas('categories', fn ($q) => $q->active())
    //                     ->whereDisponibilite(1)
    //                     ->orderBy('created_at', 'DESC')
    //                     ->paginate(12)->withQueryString()
    //             );
    //         }

    //         return view('site.pages.produit', compact('product', 'name_category'));
    //     } catch (Exception $e) {
    //         $product = Cache::remember('products_all_available', 180, fn () =>
    //             Product::with(['media', 'categories', 'subcategorie'])
    //                 ->whereDisponibilite(1)
    //                 ->orderBy('created_at', 'DESC')
    //                 ->paginate(12)->withQueryString()
    //         );
    //         return view('site.pages.produit', compact('product'));
    //     }
    // }
    public function liste_produit(Request $request)
    {
        try {
            $category      = $request->query('categorie');
            $subcategory   = $request->query('sous-categorie');
            $page          = $request->query('page', 1);  // ← récupérer la page courante
            $name_category = '';

            if ($category) {
                $name_category = Cache::remember(
                    "category_name_{$category}",
                    600,
                    fn() =>
                    Category::whereId($category)->select('id', 'name')->first()
                );

                $product = Cache::remember(
                    "products_by_category_{$category}_page_{$page}",
                    180,
                    fn() =>
                    Product::whereHas(
                        'categories',
                        fn($q) => $q->where('category_product.category_id', $category)->active()
                    )->with(['media', 'categories', 'subcategorie'])
                        ->whereDisponibilite(1)
                        ->orderBy('created_at', 'DESC')
                        ->paginate(12)->withQueryString()
                );
            } elseif ($subcategory) {
                $name_category = Cache::remember(
                    "subcategory_name_{$subcategory}",
                    600,
                    fn() =>
                    SubCategory::whereId($subcategory)->select('id', 'name')->first()
                );

                $product = Cache::remember(
                    "products_by_subcategory_{$subcategory}_page_{$page}",
                    180,
                    fn() =>
                    Product::with(['media', 'categories', 'subcategorie'])
                        ->where('sub_category_id', $subcategory)
                        ->whereDisponibilite(1)
                        ->orderBy('created_at', 'DESC')
                        ->paginate(12)->withQueryString()
                );
            } else {
                $product = Cache::remember(
                    "products_all_active_page_{$page}",
                    180,
                    fn() =>
                    Product::with(['media', 'categories', 'subcategorie'])
                        ->whereHas('categories', fn($q) => $q->active())
                        ->whereDisponibilite(1)
                        ->orderBy('created_at', 'DESC')
                        ->paginate(12)->withQueryString()
                );
            }

            return view('site.pages.produit', compact('product', 'name_category'));
        } catch (Exception $e) {
            $page    = $request->query('page', 1);

            $product = Cache::remember(
                "products_all_available_page_{$page}",
                180,
                fn() =>
                Product::with(['media', 'categories', 'subcategorie'])
                    ->whereDisponibilite(1)
                    ->orderBy('created_at', 'DESC')
                    ->paginate(12)->withQueryString()
            );

            return view('site.pages.produit', compact('product'));
        }
    }



    ///********** detail du produit */
    public function detail_produit($slug)
    {
        try {
            $product = Cache::remember(
                "product_detail_{$slug}",
                300,
                fn() =>
                Product::whereSlug($slug)
                    ->with(['categories', 'subcategorie', 'media'])
                    ->firstOrFail()
            );

            // inRandomOrder() est coûteux sur MySQL, on cache le résultat 5 min
            $categoryId = $product->categories->first()?->id;
            $product_related = Cache::remember(
                "product_related_{$product->id}_{$categoryId}",
                300,
                fn() =>
                Product::with(['media', 'categories', 'subcategorie'])
                    ->whereHas('categories', fn($q) => $q->where('category_product.category_id', $categoryId))
                    ->where('sub_category_id', $product->sub_category_id)
                    ->where('id', '!=', $product->id)
                    ->whereDisponibilite(1)
                    ->inRandomOrder()
                    ->take(10)
                    ->get()
            );

            return view('site.pages.detail-produit', compact('product', 'product_related'));
        } catch (Exception $error) {
            return redirect()->action([HomePageController::class, 'page_acceuil']);
        }
    }


    //creer un commentaire lié a un produits
    public function commentaire(Request $request)
    {
        $rating = $request['rating'];
        $comment = $request['comment'];
        $productId = $request['productId'];


        $data = Commentaire::create([
            'note' => $rating,
            'description' => $comment,
            'user_id' => Auth::user()->id,
            'product_id' => $productId
        ]);

        return response()->json([
            'status'  => 200,
            'data'  => $data
        ], 200);
    }

    /***************rechercher un produit */
    public function recherche(Request $request)
    {
        $search = $request['q'];
        $product = Product::with(['categories', 'subcategorie', 'media'])
            ->where('title', 'Like', "%{$search}%")
            ->whereDisponibilite(1)
            ->orderBy('created_at', 'desc')
            // ->take(50)
            ->paginate(12)->withQueryString();

        return view('site.pages.produit', compact('product'));
    }
}
