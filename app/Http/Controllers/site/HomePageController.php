<?php

namespace App\Http\Controllers\site;

use App\Models\Publicite;
use App\Models\Temoignage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomePageController extends Controller
{
    //page accueil
    public function page_acceuil()
    {

        //recuperer les publicite type slider
        $sliders = Publicite::whereType('slider')->whereStatus('active')->get();

        // recuperer les publicite type arriere-plan
        $background = Publicite::whereType('arriere-plan')->whereStatus('active')->first();

        // recuperer les publicités small card
        $small_card = Publicite::whereType('small-card')->whereStatus('active')->get();

        // recuperer la publicité Top promo
        $top_promo = Publicite::whereType('top-promo')->whereStatus('active')->first();

        //recuperer la liste des temoignages
        $feedback = Temoignage::orderBy('created_at', 'DESC')->get();


        return view('site.pages.accueil',  compact('sliders', 'background', 'small_card', 'top_promo', 'feedback'));
    }


 
}
