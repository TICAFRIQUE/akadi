<?php

namespace App\Http\Controllers\admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $category = Category::orderBy('name', 'ASC')->get();

        return view('admin.pages.category.index', compact('category'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data =  $request->validate([
            'name' => 'required',
            // 'type' => 'required',
            // 'type_affichage' => 'required',



        ]);

        // $count_category = Category::max('id');
        // dd($request);

        $category = Category::firstOrCreate([
            // 'id' => Str::random(9),
            'name' => $request['name'],
            'type' => $request['type'],
            'type_affichage' => $request['type_affichage'],

        ]);

        //upload category_image
        if ($request->has('cat_img')) {
            $category->addMediaFromRequest('cat_img')->toMediaCollection('category_image');
        }

        //upload category_banner
        if ($request->has('cat_banner')) {
            $category->addMediaFromRequest('cat_banner')->toMediaCollection('category_banner');
        }

        return back()->with('success', 'Nouvelle categorie ajoutée avec success');
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
        $category = Category::whereId($id)->first();

        return view('admin.pages.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $data =  $request->validate([
            'name' => 'required',
            // 'position' => '',
        ]);

        // $new_position = 0;
        // if ($request['position']) {
        //     $new_position =   $request['position'] + 1;
        // }
        $category = tap(Category::find($id))->update([
            'name' => $request['name'],
            'type' => $request['type'],
            'type_affichage' => $request['type_affichage'],
            // 'position' => $new_position,

        ]);


        //upload category_image
        if ($request->has('cat_img')) {
            $category->clearMediaCollection('category_image');
            $category->addMediaFromRequest('cat_img')->toMediaCollection('category_image');
        }

        //upload category_banner
        if ($request->has('cat_banner')) {
            $category->clearMediaCollection('category_banner');
            $category->addMediaFromRequest('cat_banner')->toMediaCollection('category_banner');
        }

        return back()->withSuccess('Categorie modifiée avec success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        //delete subcategorie of this category
        SubCategory::where('category_id', $id)->delete();



        //delete product of this catgeory
        Product::whereHas(
            'categories',
            fn ($q) => $q->where('category_product.category_id', $id),

        )->with(['collection', 'media', 'categories', 'subcategorie'])
            ->delete();

        //delete category
        Category::whereId($id)->delete();


        return response()->json([
            'status' => 200
        ]);
    }
}
