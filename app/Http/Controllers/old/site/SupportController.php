<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    //Dashboard support
    public function index()
    {
        return view('site.pages.support.help');
    }

    //Devenir vendeur
    public function becomeVendor()
    {
        return view('site.pages.support.devenir-vendeur');
    }

    //Politique de confidentialité
    public function privacyPolicy()
    {
        return view('site.pages.support.politique-confidentialite');
    }

    //Assistance 
    public function assistance()
    {
        return view('site.pages.support.assistance');
    }
    
    //A propos de nous
    public function about()
    {
        return view('site.pages.support.a-propos-de-nous');
    }
}
