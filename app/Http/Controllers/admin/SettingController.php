<?php

namespace App\Http\Controllers\admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    //

    public function index(Request $request)
    {

        $setting = Setting::first();
        // dd($setting);
        return view('admin.pages.setting.index', compact('setting'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'about' => 'required',
            'localisation' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'icone' => 'required',
        ]);

        $data = Setting::FirstOrCreate($data);

        //upload logo
        if ($request->has('logo')) {
            $data->addMediaFromRequest('logo')->toMediaCollection('logo');
        }

        $setting = Setting::whereId($data['id'])->first();
        return back()->with([
            'setting'=>$setting,
            'success' =>'Information inserée avec success'
        ]);
    }
}
