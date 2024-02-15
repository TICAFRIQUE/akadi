<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    //page accueil
        public function page_acceuil(){
        //recuperer les sliders
        

        return view('site.pages.accueil');
    }
    
}
