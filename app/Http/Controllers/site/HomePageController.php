<?php

namespace App\Http\Controllers\site;

use App\Models\Product;
use App\Models\Publicite;
use App\Models\Temoignage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;

class HomePageController extends Controller
{
    //page accueil
    public function page_acceuil()
    {
        // Sliders : données peu changeantes, TTL 5 min
        $sliders = Cache::remember('sliders_active', 300, fn () =>
            Publicite::with('media')->whereType('slider')->whereStatus('active')->get()
        );

        // Arrière-plan : très stable, TTL 10 min
        $background = Cache::remember('background_active', 600, fn () =>
            Publicite::with('media')->whereType('arriere-plan')->whereStatus('active')->first()
        );

        // Produits Pack pour la section publicité
        $pack = Cache::remember('pack_products', 300, fn () =>
            Product::with(['media', 'categories', 'subcategorie'])
                ->whereHas('categories', fn ($q) => $q->whereName('Pack'))
                ->whereDisponibilite(1)
                ->orderBy('created_at', 'DESC')
                ->get()
        );

        // Top promo : TTL 5 min
        $top_promo = Cache::remember('top_promo_active', 300, fn () =>
            Publicite::with('media')->whereType('top-promo')->whereStatus('active')->first()
        );

        // Témoignages : données peu changeantes, TTL 10 min
        $feedback = Cache::remember('feedback_latest', 600, fn () =>
            Temoignage::orderBy('created_at', 'DESC')->get()
        );

        // $annonce est déjà fourni par le ViewComposer (AppServiceProvider → site.*)
        return view('site.pages.accueil', compact('sliders', 'background', 'top_promo', 'feedback', 'pack'));
    }
}
