<?php

namespace App\Http\Controllers\admin;

use App\Models\Taille;
use App\Models\Product;
use App\Models\Category;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $product = Product::with(['categories',  'media'])
            ->orderBy('created_at', 'DESC')
            ->get();
        // dd($product->toArray());
        return view('admin.pages.product.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        // $category = Category::orderBy('name', 'ASC')
        // ->whereType('principale')
        // ->get();
 


        return view('admin.pages.product.add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        // dd($request->toArray());
        $data = $request->validate([
            'title' => ['required'],
            'description' => '',
            'price' => ['required'],
            'categories' => ['required'],
        ]);

        if (request('user')) {
            $userId  = request('user');
        } else {
            $userId = Auth::user()->id;
        }

        $product = Product::firstOrCreate([
            'title' => $request['title'],
            'description' => $request['description'],
            'price' => $request['price'],
            'sub_category_id' => $request['subcategories'],
            'montant_remise' => $request['montant_remise'],
            'pourcentage_remise' => $request['pourcentage_remise'],
            'date_debut_remise' => $request['date_debut_remise'],
            'date_fin_remise' => $request['date_fin_remise'],
            'status_remise' => '',
            'user_id' => $userId
        ]);

        //insert category in pivot table
        if ($request->has('categories')) {
            $product->categories()->attach($request['categories']);

            // DB::table('category_product')->insert([
            //     'category_id' => $request['categories'],
            //     'product_id' => $product->id
            // ]);

        }



        //upload principal image
        if ($request->has('principal_img')) {
            $product->addMediaFromRequest('principal_img')->toMediaCollection('principal_img');
        }

        //upload images with spatie
        if ($request->has('files')) {
            foreach ($request->file('files') as $value) {
                $product->addMedia($value)
                    ->toMediaCollection('product_image');
            }
        }

        // return response()->json($request);


        return back()->withSuccess('nouveau produit ajouté avec success');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        //
        // $collection = Collection::orderBy('name', 'DESC')->get();

        $product = Product::with([
            'categories', 'subcategorie',  'media'
        ])
            ->whereId($id)
            ->first();
        $catId = $product['categories'][0]['id'];


        // dd($catId);


        //sub cat of category selected
        // $cat  = Product::with([
        //     'categories' => fn ($q) => $q->whereType('principale'), 'subcategorie', 'collection', 'tailles', 'pointures', 'media'
        // ])
        //     ->whereId($id)
        //     ->first();
        $subcategory_exist = SubCategory::where('category_id', $catId)
            ->orderBy('name', 'ASC')
            ->get();

        //get Image from database
        $images = [];

        foreach ($product->media as $value) {
            array_push($images, $value);
        }
        // dd($cat->toArray());

        return view('admin.pages.product.edit', compact(
            'product',
            'subcategory_exist',
            // 'collection',
            'images'
        ));
    }


    /**
     * delete image on edit product.
     */
    public function deleteImage($id)
    {
        //
        $delete = DB::table('media')->whereId($id)->delete();

        return response()->json("suppression réussi");
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        // dd($request->toArray());
        $data = $request->validate([
            'title' => ['required'],
            'description' => '',
            'price' => ['required'],
            'categories' => ['required'],
        ]);

        $product = tap(Product::find($id))->update([
            'title' => $request['title'],
            'description' => $request['description'],
            'price' => $request['price'],
            'sub_category_id' => $request['subcategories'],
            'montant_remise' => $request['montant_remise'],
            'pourcentage_remise' => $request['pourcentage_remise'],
            'date_debut_remise' => $request['date_debut_remise'],
            'date_fin_remise' => $request['date_fin_remise'],
            'status_remise' => '',

        ]);


        //insert category in pivot table
        if ($request->has('categories')) {
            $product->categories()->detach();
            // $product->categories()->detach($request['categories']);
            $product->categories()->attach($request['categories']);
        }

        if ($request->has('principal_img')) {
            $product->clearMediaCollection('principal_img');
            $product->addMediaFromRequest('principal_img')->toMediaCollection('principal_img');
        }

        //upload images with spatie
        if ($request->has('files')) {
            foreach ($request->file('files') as $value) {
                $product->addMedia($value)
                    ->toMediaCollection('product_image');
            }
        }



        return back()->withSuccess('Produit modifié avec success ');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Product::whereId($id)->delete();
        return response()->json([
            'status' => 200
        ]);
    }






    /*********OTHER FUNCTION */

    public function loadSubcat($id)
    {
        //
        $data = SubCategory::where('category_id', $id)->get();

        return response()->json($data);
    }


    public function availableProduct(Request $request, $id)
    {
        //
        $valueChecked = $request['value'];
        $data = Product::whereId($id)->update([
            'disponibilite' => $valueChecked
        ]);

        return response()->json([
            'valueChecked' => $valueChecked,
            'status' => 200,

        ]);
    }
}
