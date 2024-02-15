<?php

namespace App\Http\Controllers\admin;

use App\Models\Taille;
use App\Models\Product;
use App\Models\Category;
use App\Models\Pointure;
use App\Models\Collection;
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
        $product = Product::with(['categories', 'collection', 'tailles', 'pointures', 'media'])
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
        $collection = Collection::orderBy('name', 'DESC')->get();


        return view('admin.pages.product.add', compact('collection'));
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
        }else{
            $userId = Auth::user()->id;
        }

        $product = Product::create([
            'title' => $request['title'],
            'description' => $request['description'],
            'price' => $request['price'],
            'collection_id' => $request['collection'],
            'sub_category_id' => $request['subcategories'],
            'user_id' =>$userId
        ]);

        //insert category in pivot table
        if ($request->has('categories')) {
            $product->categories()->attach($request['categories']);

            // DB::table('category_product')->insert([
            //     'category_id' => $request['categories'],
            //     'product_id' => $product->id
            // ]);

        }

        // if ($request->has('category_section')) {

        //     // DB::table('category_product')->insert([
        //     //     'category_id' => $request['category_section'],
        //     //     'product_id' => $product->id
        //     // ]);
        //     $product->categories()->attach($request['category_section']);
        // }

        //insert taille in table
        // if ($request->has('tailles')) {
        //     foreach ($request->input('tailles') as $key => $value) {
        //         Taille::create(['taille' => $value, 'product_id' => $product->id]);
        //     }
        // }

        //insert pointure in table
        // if ($request->has('pointures')) {
        //     foreach ($request->input('pointures') as $key => $value) {
        //         Pointure::create(['pointure' => $value, 'product_id' => $product->id]);
        //     }
        // }

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
        $collection = Collection::orderBy('name', 'DESC')->get();

        $product = Product::with([
            'categories', 'subcategorie', 'collection', 'tailles', 'pointures', 'media'
        ])
            ->whereId($id)
            ->first();

        //sub cat of category selected
        $cat  = Product::with([
            'categories' => fn ($q) => $q->whereType('principale'), 'subcategorie', 'collection', 'tailles', 'pointures', 'media'
        ])
            ->whereId($id)
            ->first();
        $catId = $cat['categories'][0]['id'];
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
            'collection',
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
            'collection_id' => $request['collection'],
            'sub_category_id' => $request['subcategories']

        ]);


        //insert category in pivot table
        if ($request->has('categories')) {
            $product->categories()->detach();
            // $product->categories()->detach($request['categories']);
            $product->categories()->attach($request['categories']);
        }

        // if ($request->has('category_section')) {
        //     $product->categories()->attach($request['category_section']);
        // }

        //upload images with spatie
        if ($request->has('files')) {
            foreach ($request->file('files') as $value) {
                $product->addMedia($value)
                    ->toMediaCollection('product_image');
            }
        }

        //insert taille in table
        // if ($request->has('tailles')) {
        //     Taille::where('product_id', $id)->delete();

        //     foreach ($request->input('tailles') as $key => $value) {
        //         Taille::create(['taille' => $value, 'product_id' => $product->id]);
        //     }
        // } else {
        //     Taille::where('product_id', $id)->delete();
        // }

        //insert pointure in table
        // if ($request->has('pointures')) {
        //     Pointure::where('product_id', $id)->delete();

        //     foreach ($request->input('pointures') as $key => $value) {
        //         Pointure::create(['pointure' => $value, 'product_id' => $product->id]);
        //     }
        // } else {
        //     Pointure::where('product_id', $id)->delete();
        // }


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
}
