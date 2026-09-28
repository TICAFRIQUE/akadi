<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\Publicite;

class PromoPageController extends Controller
{
    // Page de détail d'une publicité de type "top-promo", accessible depuis le
    // bouton "Voir le détail" du bloc promo affiché sur la page d'accueil.
    public function show(Publicite $publicite)
    {
        abort_unless($publicite->type === 'top-promo', 404);

        return view('site.pages.promo.show', ['promo' => $publicite]);
    }
}
