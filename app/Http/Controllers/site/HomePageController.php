<?php

namespace App\Http\Controllers\site;

use App\Models\Product;
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
        $sliders = Publicite::with('media')->whereType('slider')->whereStatus('active')->get();

        // recuperer les publicite type arriere-plan
        $background = Publicite::with('media')->whereType('arriere-plan')->whereStatus('active')->first();

        // recuperer les publicités small card ******changé en produit category pack
        // $small_card = Publicite::with('media')->whereType('small-card')->whereStatus('active')->get();
        $pack = Product::with(['media', 'categories', 'subcategorie'])
            ->whereHas('categories', fn ($q) => $q->whereName('Pack'))
            ->whereDisponibilite(1)
            ->orderBy('created_at', 'DESC')
            ->get();

        // recuperer la publicité Top promo
        $top_promo = Publicite::with('media')->whereType('top-promo')->whereStatus('active')->first();

        //recuperer la liste des temoignages
        $feedback = Temoignage::orderBy('created_at', 'DESC')->get();

        // foreach ($sliders as $key => $value) {

        // }
        // dd($sliders->toArray());
        return view('site.pages.accueil',  compact('sliders', 'background', 'top_promo', 'feedback', 'pack'));
    }
}
