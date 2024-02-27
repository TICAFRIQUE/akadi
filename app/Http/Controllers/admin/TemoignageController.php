<?php

namespace App\Http\Controllers\admin;

use App\Models\Temoignage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TemoignageController extends Controller
{
    //
    public function index()
    {
        $feedback = Temoignage::orderBy('created_at', 'DESC')->get();
        return view('admin.pages.feedback.index', compact('feedback'));
    }



    public function store(Request $request)
    {
        $data =  $request->validate([
            'nom' => '',
            'description' => 'required',
        ]);



        $feedback = Temoignage::firstOrCreate([
            'nom' => $request['nom'],
            'description' => $request['description'],
        ]);

        return back()->with('success', 'Nouveau temoignage ajoutée avec success');
    }


    public function edit(Request $request, $id)
    {
        $feedback = Temoignage::whereId($id)->first();

        return view('admin.pages.feedback.edit', compact('feedback'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => '',
            'description' => 'required',
        ]);


        Temoignage::whereId($id)->update([
            'nom' => $request['nom'],
            'description' => $request['description'],

        ]);

        return back()->withSuccess('Temoignage modifié avec success');
    }



    public function destroy(string $id)
    {
        //
        Temoignage::whereId($id)->delete();
        return response()->json([
            'status' => 200
        ]);
    }
}
