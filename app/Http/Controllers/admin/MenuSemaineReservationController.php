<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\MenuJour;
use App\Models\MenuProduit;
use App\Models\MenuSemaine;
use App\Models\MenuSemaineReservation;
use App\Models\MenuSemaineReservationItem;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuSemaineReservationController extends Controller
{
    /**
     * Liste des menus semaine ayant au moins une réservation, regroupés.
     */
    public function index()
    {
        $menus = MenuSemaine::withCount([
                'reservations',
                'reservations as reservations_payees_count'   => fn ($q) => $q->where('statut', 'payee'),
                'reservations as reservations_attente_count'  => fn ($q) => $q->where('statut', 'en_attente_paiement'),
                'reservations as reservations_annulees_count' => fn ($q) => $q->where('statut', 'annulee'),
            ])
            ->withSum('reservations as montant_total_encaisse', 'montant_total')
            ->has('reservations')
            ->orderBy('date_debut', 'desc')
            ->paginate(15);

        return view('admin.pages.commandes-menu.index', compact('menus'));
    }

    /**
     * Réservations d'un menu semaine précis, avec KPIs.
     */
    public function byMenu(MenuSemaine $menuSemaine)
    {
        $reservations = MenuSemaineReservation::with(['user', 'orders', 'paymentMethod'])
            ->where('menu_semaine_id', $menuSemaine->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $allOrders = $reservations->flatMap(fn ($r) => $r->orders);
        $joursLivres  = $allOrders->where('status', Order::STATUS_LIVREE)->count();
        $joursTotal   = $allOrders->count();

        $kpis = [
            'total'         => $reservations->count(),
            'ca'            => $reservations->where('statut', 'payee')->sum('montant_total'),
            'jours_livres'  => $joursLivres,
            'jours_restants'=> $joursTotal - $joursLivres,
        ];

        return view('admin.pages.commandes-menu.by-menu', compact('menuSemaine', 'reservations', 'kpis'));
    }

    /**
     * Fiche de préparation : quantités à préparer par jour et par plat.
     */
    public function fichePreparation(MenuSemaine $menuSemaine)
    {
        // Tous les items des réservations payées de ce menu
        $items = MenuSemaineReservationItem::whereHas(
                'reservation',
                fn ($q) => $q->where('menu_semaine_id', $menuSemaine->id)->where('statut', 'payee')
            )
            ->with('menuProduit')
            ->get();

        // Jours triés → plats → quantité totale
        $parJour = $items
            ->groupBy(fn ($i) => $i->date->format('Y-m-d'))
            ->sortKeys()
            ->map(fn ($group) =>
                $group->groupBy('menu_produit_id')->map(fn ($g) => [
                    'plat'     => $g->first()->menuProduit,
                    'quantite' => $g->sum('quantity'),
                ])->values()
            );

        // Total général par plat (toutes dates confondues) pour le résumé
        $totalParPlat = $items
            ->groupBy('menu_produit_id')
            ->map(fn ($g) => [
                'plat'     => $g->first()->menuProduit,
                'quantite' => $g->sum('quantity'),
            ])
            ->sortByDesc('quantite')
            ->values();

        $pdf = Pdf::loadView(
            'admin.pages.commandes-menu.preparation',
            compact('menuSemaine', 'parJour', 'totalParPlat')
        )->setPaper('a4', 'portrait');

        $slug = \Illuminate\Support\Str::slug($menuSemaine->titre_affiche);
        return $pdf->download("preparation-{$slug}.pdf");
    }

    /**
     * Export PDF de la liste des clients d'un menu semaine.
     */
    public function downloadClients(MenuSemaine $menuSemaine)
    {
        $reservations = MenuSemaineReservation::with('paymentMethod')
            ->where('menu_semaine_id', $menuSemaine->id)
            ->where('statut', 'payee')
            ->orderBy('client_name')
            ->get();

        $pdf = Pdf::loadView('admin.pages.commandes-menu.clients-pdf', compact('menuSemaine', 'reservations'))
            ->setPaper('a4', 'landscape');

        $slug = \Illuminate\Support\Str::slug($menuSemaine->titre_affiche);
        return $pdf->download("clients-{$slug}.pdf");
    }

    /**
     * Ticket de caisse PDF pour une commande (un jour) d'un menu semaine.
     */
    public function ticketOrder(Order $order)
    {
        $order->load(['menuSemaineReservation.menuSemaine', 'menuSemaineReservation.paymentMethod', 'menuProduits']);

        $pdf = Pdf::loadView('admin.pages.commandes-menu.ticket', compact('order'))
            ->setPaper([0, 0, 226.77, 650], 'portrait'); // 80 mm de large

        $code = $order->code ?? $order->id;
        return $pdf->download("ticket-{$code}.pdf");
    }

    /**
     * Facture PDF d'une réservation complète.
     */
    public function facture(MenuSemaineReservation $reservation)
    {
        $reservation->load(['menuSemaine', 'items.menuProduit', 'orders', 'paymentMethod', 'user']);

        $pdf = Pdf::loadView('admin.pages.commandes-menu.facture', compact('reservation'))
            ->setPaper('a4', 'portrait');

        $nom = \Illuminate\Support\Str::slug($reservation->client_name);
        return $pdf->download("facture-menu-{$nom}.pdf");
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
