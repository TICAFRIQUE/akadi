<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\MenuJour;

class MenuPageController extends Controller
{
    /**
     * Afficher le menu du jour (page publique)
     */
    public function menuDuJour()
    {
        $menuJour = MenuJour::with(['menuProduits' => fn($q) => $q->where('disponible', true)])
            ->where('actif', true)
            ->whereDate('date', today())
            ->first();

        return view('site.pages.menu-du-jour', compact('menuJour'));
    }
}
