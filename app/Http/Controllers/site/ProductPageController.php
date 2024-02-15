<?php

namespace App\Http\Controllers\site;

use Exception;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductPageController extends Controller
{


    /********** Get shop List of category  */
    public function liste_produit(Request $request)
    {
        try {
            $category = request('categorie');
            $subcategory = request('sous-categorie');

            if ($category) {

                $product = Product::whereHas(
                    'categories',
                    fn ($q) => $q->where('category_product.category_id', $category),

                )->with(['media', 'categories'])
                ->inRandomOrder()->paginate(36);
            } else if ($subcategory) {

                $product = Product::with(['media', 'categories'])
                ->where('sub_category_id', $subcategory)->inRandomOrder()->paginate(36);
            } else {
                $product = Product::with(['media', 'categories'])->inRandomOrder()->paginate(36);
            }

            return view(
                'site.pages.produit',
                compact('product',)
            );
        } catch (Exception $e) {
            $e->getMessage();
            $product = Product::with(['media', 'categories'])->inRandomOrder()->paginate(36);
            return view(
                'site.pages.produit',
                compact('product',)
            );
        }
    }




    ///********** detail du produit */
    public function detail_produit($slug)
    {
        try {
            $product = Product::whereSlug($slug)
                ->with(['categories', 'media'])
                ->firstOrFail();

                // dd($product->toArray());
            return view('site.pages.detail-produit', compact('product'));
        } catch (Exception $error) {
            return redirect()->action([HomePageController::class, 'page_acceuil']);
        }
    }


    
}
