<?php

namespace App\Http\Controllers\admin;

use App\Models\Publicite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PubliciteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $publicite = Publicite::orderBy('created_at', 'DESC')->get();

        return view('admin.pages.publicite.index', compact('publicite'));
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
        // dd($request->toArray());
        $data =  $request->validate([
            'type' => '',
        ]);


        $publicite = Publicite::create([
            'type' => $request['type'],
            'url' => $request['url'],

        ]);

             //upload category_image
             if ($request->has('image')) {
                $publicite->addMediaFromRequest('image')->toMediaCollection('publicite_image');
            }
    

        return back()->with('success', 'Nouvelle Publicite ajoutée avec success');
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
        $publicite = Publicite::whereId($id)->first();

        return view('admin.pages.publicite.edit', compact('publicite'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
         //
         $data =  $request->validate([
            'type' => '',
        ]);


        $publicite = tap(Publicite::find($id))->update([
            'type' => $request['type'],
            'url' => $request['url'],
        ]);

        //upload category_image 
        if ($request->has('image')) {
            $publicite->clearMediaCollection('publicite_image');
            $publicite->addMediaFromRequest('image')->toMediaCollection('publicite_image');
            }
            
        return back()->withSuccess('Publicite modifiée avec success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Publicite::whereId($id)->delete();
        return response()->json([
            'status'=>200
        ]);
    }
}
