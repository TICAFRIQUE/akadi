<?php

namespace App\Http\Controllers\admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductBase;
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
        $product = Product::with('categories', 'productBase', 'subcategorie')
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

        $subcategories = SubCategory::orderBy('name')->get();
        $allProductBases = ProductBase::orderBy('nom')->get();


        return view('admin.pages.product.add', compact('subcategories', 'allProductBases'));
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
            'stock' => $request->input('stock') !== '' ? $request->input('stock') : null,
            'stock_alerte' => $request->input('stock_alerte', 5),
            'product_base_id' => null,  // On ne stocke jamais le tableau ici, utiliser productBases() à la place
            'coefficient' => null,      // Idem
            'user_id' => $userId
        ]);

        // Attacher les multiples productBases via la pivot si fourni
        if ($request->has('product_base_id') && is_array($request->input('product_base_id'))) {
            $this->attachProductBasesToPivot($product, $request);
        }

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
    // public function edit(string $id)
    // {
    //     $product = Product::with([
    //         'categories',
    //         'subcategorie',
    //         'media',
    //         'productBases'
    //     ])
    //         ->whereId($id)
    //         ->first();

    //     // Récupérer l'ID de la catégorie correctement (depuis l'objet)
    //     $catId = $product->categories->first()?->id;

    //     // Sous-catégories existantes
    //     $subcategory_exist = SubCategory::where('category_id', $catId)
    //         ->orderBy('name', 'ASC')
    //         ->get();

    //     // Toutes les catégories pour le formulaire
    //     $category_backend = Category::orderBy('name', 'ASC')->get();

    //     // Images du produit
    //     $images = $product->media->toArray();

    //     // Tous les produits de base pour le formulaire
    //     $allProductBases = ProductBase::orderBy('nom')->get();
    //     $productBases = $product->productBases;

    //     // dd($productBases->toArray());

    //     return view('admin.pages.product.edit', compact(
    //         'product',
    //         'category_backend',
    //         'subcategory_exist',
    //         'images',
    //         'allProductBases',
    //         'productBases'
    //     ));
    // }
    public function edit(string $id)
    {
        $product = Product::with([
            'categories',
            'subcategorie',
            'media',
            'productBases'
        ])
            ->whereId($id)
            ->first();

        // Récupérer l'ID de la catégorie correctement (depuis l'objet)
        $catId = $product->categories->first()?->id;

        // Sous-catégories existantes
        $subcategory_exist = SubCategory::where('category_id', $catId)
            ->orderBy('name', 'ASC')
            ->get();

        // Toutes les catégories pour le formulaire
        $category_backend = Category::orderBy('name', 'ASC')->get();

        // Images du produit
        $images = $product->media->toArray();

        // Tous les produits de base pour le formulaire
        $allProductBases = ProductBase::orderBy('nom')->get();
        $productBases    = $product->productBases;

        // Préparer les données des liaisons existantes pour le composant Blade
        $productBasesData = $productBases->map(function ($pb) {
            return [
                'id'          => $pb->id,
                'nom'         => $pb->nom,
                'unite'       => $pb->unite,
                'coefficient' => $pb->pivot->coefficient ?? 0,
            ];
        })->values();

        return view('admin.pages.product.edit', compact(
            'product',
            'category_backend',
            'subcategory_exist',
            'images',
            'allProductBases',
            'productBases',
            'productBasesData'
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
            'stock' => $request->input('stock') !== '' ? $request->input('stock') : null,
            'stock_alerte' => $request->input('stock_alerte', 5),
            'product_base_id' => null,  // On ne stocke jamais le tableau ici, utiliser productBases() à la place
            'coefficient' => null,      // Idem
        ]);

        // Mettre à jour les productBases dans la pivot
        if ($request->has('product_base_id') && is_array($request->input('product_base_id'))) {
            $this->attachProductBasesToPivot($product, $request);
        } else {
            // Si pas d'array, détacher tous les productBases (fallback)
            $product->productBases()->detach();
        }


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

    /**
     * Attacher les productBases au produit via la pivot avec leurs coefficients
     */
    private function attachProductBasesToPivot(Product $product, Request $request)
    {
        // Récupérer les IDs et coefficients du formulaire
        $productBaseIds = $request->input('product_base_id', []);
        $coefficients = $request->input('coefficient', []);

        // Détacher tous les productBases existants
        $product->productBases()->detach();

        // Attacher les nouveaux productBases avec leurs coefficients
        foreach ($productBaseIds as $index => $baseId) {
            if (!empty($baseId) && !empty($coefficients[$index])) {
                $product->productBases()->attach($baseId, ['coefficient' => $coefficients[$index]]);
            }
        }
    }
}
