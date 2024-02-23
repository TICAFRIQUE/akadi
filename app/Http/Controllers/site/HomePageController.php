<?php

namespace App\Http\Controllers\site;

use App\Models\Publicite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomePageController extends Controller
{
    //page accueil
        public function page_acceuil(){

        //recuperer les publicite type slider
        $sliders = Publicite::whereType('slider')->get();

        // recuperer les publicite type arriere-plan
        $background = Publicite::whereType('arriere-plan')->first();

        // recuperer les publicités small card
        $small_card = Publicite::whereType('small-card')->get();



        

        return view('site.pages.accueil' ,  compact('sliders','background', 'small_card'));
    }
    
}
