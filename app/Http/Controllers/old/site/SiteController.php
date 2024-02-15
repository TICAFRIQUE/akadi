<?php

namespace App\Http\Controllers\site;

use Exception;
use App\Models\Publicite;
use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SiteController extends Controller
{

public function index(){
    return view('site.site_frame');
}


    /************Home page */
    public function home()
    {
        //category list
        $category = Category::with('media')
            ->orderBy('created_at', 'DESC')
            ->get();
            

        //category section has product list
        //  $section_has_product = Category::withWhereHas('products', fn ($q) =>
        // $q->with('media'))
        // ->whereType('section')
        // ->orderBy('name')->get();


        //subcat list
        // $subcategory = SubCategory::withWhereHas('products', fn ($q) =>
        // $q->with('media'))->orderBy('name', 'DESC')->get();

        //collection List
        $collection = Collection::with('media')->orderBy('name')->get();

        //Publicite type==banniere
        $Publicite_banniere = Publicite::whereType('banniere')->orderBy('created_at', 'DESC')->get();


        //category with product
        $category_with_product = Category::withWhereHas(
            'products',
            function ($q) {
                $q->with('media');
            }
        )
            ->orderBy('name')
            ->inRandomOrder()->take(26)->get();


        //subcategory with product
        $subcategory_with_product = SubCategory::withWhereHas('products', fn ($q) =>
        $q->with('media'))->orderBy('name')->inRandomOrder()->take(26)->get();
        // dd($subcategory_with_product->toArray());

        return view('site.home', compact('category',  'category_with_product', 'collection', 'Publicite_banniere', 'subcategory_with_product'));
    }
    //return previous page
    // public function back(){
    //     dd(url()->previous());
    // }


    /*********Get List category */

    public function categoryList()
    {

        $category = Category::with('media')->orderBy('name', 'ASC')->get();

        return view('site.pages.category-list', compact('category'));
    }


    /*********Get Sub category of category choice */

    public function subCategoryList()
    {
        try {
            $category = request('c');
            // dd($category);

            $subcategory = SubCategory::with('media')
                ->where('category_id', $category)
                ->orderBy('name', 'ASC')->get();

            //show title page
            $title = Category::whereId($subcategory[0]['category_id'])->first();
            return view('site.pages.subcategory-list', compact('subcategory', 'title'));
        } catch (\Throwable $e) {
            return redirect()->action([SiteController::class, 'categoryList']);
        }
    }

    /**********Get detail of product */
    public function product_detail(string $id)
    {
        try {
            $product = Product::whereId($id)
                ->with(['categories', 'collection', 'tailles', 'pointures', 'media'])
                ->firstOrFail();

            return view('site.pages.product-detail', compact('product'));
        } catch (Exception $error) {
            return redirect()->action([SiteController::class, 'shop']);
        }
    }

    /********** Get shop List of category  */
    public function shop(Request $request)
    {
        try {
            $category = request('category');
            $subcategory = request('subcategory');
            $collection = request('collection');

            if ($category) {

                $product = Product::whereHas(
                    'categories',
                    fn ($q) => $q->where('category_product.category_id', $category),

                )->with(['collection', 'media', 'categories'])
                    ->inRandomOrder()->paginate(36);


                //show title page
                $category = Category::whereId($category)->with('media')->first();
                $title =   $category = Category::whereId($category['id'])->with('media')->first();
                $title_name =  $title['name'];
            } else if ($subcategory) {

                $product = Product::with(['collection', 'media', 'categories'])
                    ->where('sub_category_id', $subcategory)->inRandomOrder()->paginate(36);


                //show title page
                $category = Category::whereId($category)->with('media')->first();
                $title = SubCategory::whereId($subcategory)->first();
                $title_name =  $title['name'];
            } else if ($collection) {

                $product = Product::with(['collection', 'media', 'categories'])
                    ->where('collection_id', $collection)->inRandomOrder()->paginate(36);


                //show title page
                $category = Category::whereId($category)->with('media')->first();
                $title = Collection::whereId($collection)->first();
                $title_name =  $title['name'];
            } else {
                $product = Product::inRandomOrder()->paginate(36);
                $title_name = 'Boutique';
                $title =   $category = Category::with('media')->get()->random(1);
            }

            return view('site.pages.shop', compact('product', 'category', 'title', 'title_name'));
        } catch (Exception $error) {
            return redirect()->action([SiteController::class, 'shop']);
        }
    }


    /***************Search product */

    public function searchProduct(Request $request)
    {

        $search = $request['search'];
        $product = Product::with(['categories', 'subcategorie', 'media'])
            ->where('title', 'Like', "%{$search}%")
            ->orderBy('created_at', 'desc')->paginate(36);
        $title = $search;
        return view('site.pages.shop', compact('product', 'title'));
    }
}
