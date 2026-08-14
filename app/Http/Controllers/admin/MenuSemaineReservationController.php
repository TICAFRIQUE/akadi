<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MenuJour;
use App\Models\MenuSemaineReservation;
use App\Models\MenuSemaineReservationItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuSemaineReservationController extends Controller
{
    /**
     * Liste des réservations menu de la semaine (payées ou en attente de paiement).
     */
    public function index()
    {
        $reservations = MenuSemaineReservation::with(['menuSemaine', 'orders'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.pages.commandes-menu.index', compact('reservations'));
    }

    /**
     * Détail d'une réservation : une commande par jour, avec possibilité de
     * changer le plat d'un jour non encore livré.
     */
    public function show(MenuSemaineReservation $reservation)
    {
        $reservation->load(['menuSemaine', 'items.menuProduit', 'orders.menuProduits']);

        $joursAffiches = $reservation->items->groupBy(fn ($item) => $item->date->format('Y-m-d'));

        // Pour chaque jour, plats disponibles ce jour-là (pour le select de remplacement)
        $platsDisponiblesParJour = MenuJour::where('menu_semaine_id', $reservation->menu_semaine_id)
            ->with(['menuProduits' => fn ($q) => $q->where('disponible', true)])
            ->get()
            ->keyBy(fn ($mj) => $mj->date->format('Y-m-d'));

        return view('admin.pages.commandes-menu.show', compact('reservation', 'joursAffiches', 'platsDisponiblesParJour'));
    }

    /**
     * Change le plat livré pour un jour donné (tant que la commande de ce jour
     * n'est pas déjà livrée). Le prix/quantité déjà payés sont conservés — seul
     * le plat change.
     */
    public function modifierPlat(Request $request, Order $order)
    {
        $request->validate([
            'ancien_menu_produit_id'  => 'required|integer|exists:menu_produits,id',
            'nouveau_menu_produit_id' => 'required|integer|exists:menu_produits,id',
        ]);

        if (!$order->menu_semaine_reservation_id) {
            return back()->with('error', "Cette commande n'est pas liée à une réservation menu semaine.");
        }

        if ($order->status === Order::STATUS_LIVREE) {
            return back()->with('error', 'Impossible de modifier le plat : ce jour est déjà livré.');
        }

        $pivotExiste = DB::table('order_menu_produit')
            ->where('order_id', $order->id)
            ->where('menu_produit_id', $request->ancien_menu_produit_id)
            ->exists();

        if (!$pivotExiste) {
            return back()->with('error', 'Plat introuvable sur cette commande.');
        }

        DB::table('order_menu_produit')
            ->where('order_id', $order->id)
            ->where('menu_produit_id', $request->ancien_menu_produit_id)
            ->update(['menu_produit_id' => $request->nouveau_menu_produit_id, 'updated_at' => now()]);

        // Garder la ligne de réservation cohérente avec la commande générée
        MenuSemaineReservationItem::where('menu_semaine_reservation_id', $order->menu_semaine_reservation_id)
            ->where('date', $order->date_order)
            ->where('menu_produit_id', $request->ancien_menu_produit_id)
            ->update(['menu_produit_id' => $request->nouveau_menu_produit_id]);

        return back()->with('success', 'Plat modifié avec succès.');
    }
}
