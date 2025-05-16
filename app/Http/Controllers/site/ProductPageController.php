<?php

namespace App\Http\Controllers\site;

use Exception;
use App\Models\Product;
use App\Models\Category;
use App\Models\Publicite;
use App\Models\Commentaire;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductPageController extends Controller
{


    /********** Get shop List of category  */
    public function liste_produit(Request $request)
    {
        try {
            $category = request('categorie');
            $subcategory = request('sous-categorie');
            //get category et subcategory name
            $name_category = '';

            if ($category) {
                $name_category = Category::whereId($category)->select('name')->first();

                $product = Product::whereHas(
                    'categories',
                    fn($q) => $q->where('category_product.category_id', $category),

                )->with(['media', 'categories', 'subcategorie'])
                    ->orderBy('created_at', 'DESC')
                    ->whereDisponibilite(1)
                    ->get();
            } else if ($subcategory) {
                $name_category = SubCategory::whereId($subcategory)->select('name')->first();

                $product = Product::with(['media', 'categories', 'subcategorie'])
                    ->where('sub_category_id', $subcategory)->orderBy('created_at', 'DESC')
                    ->whereDisponibilite(1)
                    ->get();
            } else {
                $product = Product::with(['media', 'categories', 'subcategorie'])->orderBy('created_at', 'DESC')
                    ->whereDisponibilite(1)
                    ->get();
            }
            // dd([$name_category]); 

            return view(
                'site.pages.produit',

                compact('product', 'name_category')
            );
        } catch (Exception $e) {
            $e->getMessage();
            $product = Product::with(['media', 'categories', 'subcategorie'])->orderBy('created_at', 'DESC')
                ->whereDisponibilite(1)
                ->get();
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
                ->with(['categories', 'subcategorie', 'media'])
                ->firstOrFail();


            $product_related =
                Product::with(['media', 'categories', 'subcategorie'])
                ->whereHas('categories', fn($q) => $q->where('category_product.category_id', $product['categories'][0]['id']))
                ->Where('sub_category_id', $product['sub_category_id'])
                ->where('id', '!=',  $product['id'])
                ->whereDisponibilite(1)
                ->inRandomOrder()->take(10)->get();

            // dd($product_related->toArray());
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
        $product = Product::with([
            'categories',
            'subcategorie',
            'media'
        ])
            ->where('title', 'Like', "%{$search}%")
            ->whereDisponibilite(1)
            ->orderBy('created_at', 'desc')->inRandomOrder()->get();

        return view('site.pages.produit', compact('product',));
    }
}
