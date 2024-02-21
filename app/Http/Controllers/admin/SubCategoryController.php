<?php

namespace App\Http\Controllers\admin;

use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $subCategory = SubCategory::with(['products', 'media'])
            ->orderBy('name', 'ASC')->get();
        return view('admin.pages.subCategory.index', compact('subCategory'));
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
        $request->validate([
            'name' => ['required'],
            'category' => ['required'],
            // 'type_affichage' => ['required']

        ]);

        $subcategory = SubCategory::firstOrCreate([
            'name' => $request['name'],
            'category_id' => $request['category'],
            'type_affichage' => $request['type_affichage'],

        ]);

        //upload category_image
        if ($request->has('subcat_img')) {
            $subcategory->addMediaFromRequest('subcat_img')->toMediaCollection('subcategory_image');
        }

        //upload category_banner
        if ($request->has('subcat_banner')) {
            $subcategory->addMediaFromRequest('subcat_banner')->toMediaCollection('subcategory_banner');
        }

        return back()->with('success', 'Nouvelle sous categorie ajoutée avec success');
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
        $subCategory = SubCategory::whereId($id)->first();

        return view('admin.pages.subCategory.edit', compact('subCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $subcategory = tap(SubCategory::find($id))->update([
            'name' => $request['name'],
            'category_id' => $request['category'],
            'type_affichage' => $request['type_affichage'],
        ]);

        //upload category_image
        if ($request->has('subcat_image')) {
            $subcategory->clearMediaCollection('subcategory_image');
            $subcategory->addMediaFromRequest('subcat_image')->toMediaCollection('subcategory_image');
        }

        //upload category_banner
        if ($request->has('subcat_banner')) {
            $subcategory->clearMediaCollection('subcategory_banner');
            $subcategory->addMediaFromRequest('subcat_banner')->toMediaCollection('subcategory_banner');
        }

        return back()->withSuccess('Sous Categorie modifiée avec success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        //delete product  of this sub-category
        Product::where("sub_category_id", $id)->delete();

        SubCategory::whereId($id)->delete();
        return response()->json([
            'status' => 200
        ]);
    }
}
