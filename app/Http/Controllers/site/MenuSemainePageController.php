<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Models\MenuJour;
use App\Models\MenuProduit;
use App\Models\MenuSemaine;
use App\Models\MenuSemaineReservation;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Services\StockService;
use App\Services\WavePaymentService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class MenuSemainePageController extends Controller
{
    protected WavePaymentService $waveService;
    protected StockService $stockService;

    public function __construct(WavePaymentService $waveService, StockService $stockService)
    {
        $this->waveService = $waveService;
        $this->stockService = $stockService;
    }

    /**
     * Carte menu indisponible : aucune semaine active pour le moment (lien nav générique, sans token).
     */
    public function carteMenuIndisponible()
    {
        return response()->view('site.pages.carte-menu-indisponible');
    }

    /**
     * Carte menu publique (lien partageable par token).
     */
    public function carteMenu(string $token)
    {
        $menuSemaine = MenuSemaine::where('lien_token', $token)
            ->with(['menusJour' => function ($q) {
                $q->where('actif', true)->orderBy('date');
            }, 'menusJour.menuProduits' => function ($q) {
                $q->where('disponible', true);
            }])
            ->first();

        if (!$menuSemaine || !$menuSemaine->actif) {
            return response()->view('site.pages.carte-menu-indisponible');
        }

        $cartJours = $this->cartSemaine($menuSemaine->id);
        $cart = $cartJours; // { date: { menu_produit_id: quantity } } pour préremplir les compteurs
        $cartSummary = $this->calculerSelection($cartJours, $menuSemaine);

        return view('site.pages.carte-menu', compact('menuSemaine', 'cart', 'cartSummary'));
    }

    /**
     * Ajouter/mettre à jour/retirer (quantity=0) un plat d'un jour dans le panier semaine (AJAX).
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'menu_semaine_id' => 'required|integer|exists:menus_semaine,id',
            'date'            => 'required|date',
            'menu_produit_id' => 'required|integer|exists:menu_produits,id',
            'quantity'        => 'required|integer|min:0',
        ]);

        // Le plat doit bien appartenir à un jour actif de cette semaine, à cette date précise.
        $menuJour = MenuJour::where('menu_semaine_id', $request->menu_semaine_id)
            ->where('date', $request->date)
            ->where('actif', true)
            ->first();

        if (!$menuJour || !$menuJour->menuProduits()->where('id', $request->menu_produit_id)->where('disponible', true)->exists()) {
            return response()->json(['message' => 'Plat indisponible pour ce jour.'], 422);
        }

        $cart = Session::get('cart_menu_semaine', []);

        // Un panier ne concerne qu'une seule semaine à la fois.
        if (($cart['menu_semaine_id'] ?? null) != $request->menu_semaine_id) {
            $cart = ['menu_semaine_id' => (int) $request->menu_semaine_id, 'jours' => []];
        }

        if ($request->quantity > 0) {
            $cart['jours'][$request->date][$request->menu_produit_id] = (int) $request->quantity;
        } else {
            unset($cart['jours'][$request->date][$request->menu_produit_id]);
            if (empty($cart['jours'][$request->date])) {
                unset($cart['jours'][$request->date]);
            }
        }

        Session::put('cart_menu_semaine', $cart);

        $menuSemaine = MenuSemaine::findOrFail($request->menu_semaine_id);
        $calcul = $this->calculerSelection($cart['jours'] ?? [], $menuSemaine);

        return response()->json([
            'message'       => 'Panier mis à jour',
            'nombre_jours'  => $calcul['nombreJours'],
            'prix_unitaire' => $calcul['prixUnitaire'],
            'montant_total' => $calcul['montantTotal'],
            'nombre_lignes' => count($calcul['items']),
        ]);
    }

    /**
     * Page panier semaine : détail jour par jour, tarif appliqué, total.
     */
    public function panier()
    {
        $cart = Session::get('cart_menu_semaine', []);

        if (empty($cart['jours'])) {
            return redirect()->route('page-acceuil')->with('error', 'Votre panier menu de la semaine est vide.');
        }

        $menuSemaine = MenuSemaine::with('menusJour')->findOrFail($cart['menu_semaine_id']);
        $calcul = $this->calculerSelection($cart['jours'], $menuSemaine);

        if (empty($calcul['items'])) {
            return redirect()->route('page-acceuil')->with('error', 'Votre panier menu de la semaine est vide.');
        }

        // Regroupement par date pour l'affichage
        $joursAffiches = collect($calcul['items'])->groupBy('date');

        return view('site.pages.panier-semaine', [
            'menuSemaine'   => $menuSemaine,
            'joursAffiches' => $joursAffiches,
            'nombreJours'   => $calcul['nombreJours'],
            'prixUnitaire'  => $calcul['prixUnitaire'],
            'montantTotal'  => $calcul['montantTotal'],
        ]);
    }

    /**
     * Valider le panier semaine : crée la réservation + ses lignes, puis initialise le paiement Wave.
     * Le paiement doit être confirmé avant que la commande ne soit "validée" (créée) — voir webhook.
     */
    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour réserver le menu de la semaine.')
                ->with('intended', route('menu-semaine.panier'));
        }

        $request->validate([
            'mode_livraison'  => 'required|in:yango,recuperer',
            'address_yango'   => 'required_if:mode_livraison,yango|nullable|string|max:255',
            'payment_method'  => 'nullable|in:wave,cash',
        ], [
            'mode_livraison.required' => 'Veuillez choisir un mode de livraison.',
            'address_yango.required_if' => 'Veuillez préciser la destination.',
        ]);

        $cart = Session::get('cart_menu_semaine', []);
        if (empty($cart['jours'])) {
            return redirect()->route('page-acceuil')->with('error', 'Votre panier menu de la semaine est vide.');
        }

        $menuSemaine = MenuSemaine::findOrFail($cart['menu_semaine_id']);
        $calcul = $this->calculerSelection($cart['jours'], $menuSemaine);

        if (empty($calcul['items'])) {
            return redirect()->route('page-acceuil')->with('error', 'Votre panier menu de la semaine est vide.');
        }

        $user = Auth::user();
        $modeLivraison = $request->input('mode_livraison');
        $addressYango = $modeLivraison === 'yango' ? $request->input('address_yango') : null;

        // ── TEMPORAIRE (test local — Wave indisponible en local) : paiement espèces direct.
        // À retirer une fois les tests de réception "Commandes Menu" terminés.
        if ($request->input('payment_method') === 'cash') {
            $cashMethod = PaymentMethod::where('code', 'cash')->where('actif', true)->first();
            if (!$cashMethod) {
                return back()->with('error', "Le paiement en espèces n'est pas disponible.");
            }

            $reservation = DB::transaction(function () use ($menuSemaine, $calcul, $cashMethod, $user, $modeLivraison, $addressYango) {
                $reservation = MenuSemaineReservation::create([
                    'menu_semaine_id'        => $menuSemaine->id,
                    'user_id'                => $user->id,
                    'client_name'            => $user->name,
                    'client_phone'           => $user->phone ?? '',
                    'nombre_jours'           => $calcul['nombreJours'],
                    'prix_unitaire_applique' => $calcul['prixUnitaire'],
                    'montant_total'          => $calcul['montantTotal'],
                    'mode_livraison'         => $modeLivraison === 'yango' ? 'Livraison Yango Moto' : 'Je passe récupérer',
                    'address_yango'          => $addressYango,
                    'payment_method_id'      => $cashMethod->id,
                    'payment_status'         => 'completed',
                    'statut'                 => MenuSemaineReservation::STATUT_PAYEE,
                ]);

                foreach ($calcul['items'] as $item) {
                    $reservation->items()->create([
                        'date'            => $item['date'],
                        'menu_produit_id' => $item['menu_produit_id'],
                        'quantity'        => $item['quantity'],
                        'prix_unitaire'   => $item['prix_unitaire'],
                    ]);
                }

                $reservation->load('items.menuProduit');
                foreach ($reservation->items->groupBy(fn ($item) => $item->date->format('Y-m-d')) as $date => $itemsDuJour) {
                    $totalJour = $itemsDuJour->sum(fn ($item) => $item->quantity * $item->prix_unitaire);

                    $order = Order::create([
                        'menu_semaine_reservation_id' => $reservation->id,
                        'user_id'          => $reservation->user_id,
                        'client_name'      => $reservation->client_name,
                        'client_phone'     => $reservation->client_phone,
                        'quantity_product' => $itemsDuJour->sum('quantity'),
                        'subtotal'         => 0,
                        'total_menu'       => $totalJour,
                        'total'            => $totalJour,
                        'delivery_price'   => 0,
                        'mode_livraison'   => $reservation->mode_livraison,
                        'address_yango'    => $reservation->address_yango,
                        'status'           => $date > now()->format('Y-m-d') ? Order::STATUS_PRECOMMANDE : Order::STATUS_ATTENTE,
                        'source'           => Order::SOURCE_WEB,
                        'type_order'       => 'normal',
                        'payment_method_id' => $cashMethod->id,
                        'payment_status'   => 'completed',
                        'payment_completed_at' => now(),
                        'acompte'          => $totalJour,
                        'date_order'       => $date,
                    ]);

                    $cartShape = [];
                    foreach ($itemsDuJour as $item) {
                        $cartShape[$item->menu_produit_id] = [
                            'quantity' => $item->quantity,
                            'price'    => $item->prix_unitaire,
                        ];
                    }
                    $this->stockService->attachMenuCartToOrder($order, $cartShape);
                }

                return $reservation;
            });

            Session::forget('cart_menu_semaine');

            return redirect()->route('menu-semaine.reservation-success', $reservation->id)
                ->with('success', 'Réservation enregistrée (paiement espèces).');
        }

        $waveMethod = PaymentMethod::where('code', 'wave')->where('actif', true)->first();
        if (!$waveMethod) {
            return back()->with('error', "Le paiement en ligne n'est pas disponible pour le moment.");
        }

        try {
            $reservation = DB::transaction(function () use ($menuSemaine, $calcul, $waveMethod, $user, $modeLivraison, $addressYango) {
                $reservation = MenuSemaineReservation::create([
                    'menu_semaine_id'        => $menuSemaine->id,
                    'user_id'                => $user->id,
                    'client_name'            => $user->name,
                    'client_phone'           => $user->phone ?? '',
                    'nombre_jours'           => $calcul['nombreJours'],
                    'prix_unitaire_applique' => $calcul['prixUnitaire'],
                    'montant_total'          => $calcul['montantTotal'],
                    'mode_livraison'         => $modeLivraison === 'yango' ? 'Livraison Yango Moto' : 'Je passe récupérer',
                    'address_yango'          => $addressYango,
                    'payment_method_id'      => $waveMethod->id,
                    'payment_status'         => 'pending',
                    'statut'                 => MenuSemaineReservation::STATUT_EN_ATTENTE_PAIEMENT,
                ]);

                foreach ($calcul['items'] as $item) {
                    $reservation->items()->create([
                        'date'            => $item['date'],
                        'menu_produit_id' => $item['menu_produit_id'],
                        'quantity'        => $item['quantity'],
                        'prix_unitaire'   => $item['prix_unitaire'],
                    ]);
                }

                return $reservation;
            });

            $waveResponse = $this->waveService->createCheckoutSession(
                $calcul['montantTotal'],
                'XOF',
                'RESA_' . $reservation->id
            );

            if (!$waveResponse['success']) {
                $reservation->items()->delete();
                $reservation->delete();

                return back()->with('error', "Impossible d'initialiser le paiement Wave: " . ($waveResponse['error'] ?? 'erreur inconnue'));
            }

            $reservation->update(['wave_session_id' => $waveResponse['session_id']]);

            Log::info('Menu Semaine Reservation - Wave Payment Initiated', [
                'reservation_id'   => $reservation->id,
                'wave_session_id'  => $waveResponse['session_id'],
                'user_id'          => $user->id,
            ]);

            return redirect()->away($waveResponse['wave_launch_url']);
        } catch (Exception $e) {
            Log::error('Menu Semaine Checkout Error', ['error' => $e->getMessage(), 'user_id' => $user->id]);

            return back()->with('error', "Une erreur est survenue lors de la réservation: " . $e->getMessage());
        }
    }

    /**
     * Page de confirmation d'une réservation menu semaine payée.
     */
    public function reservationSuccess(int $id)
    {
        $reservation = MenuSemaineReservation::with(['menuSemaine', 'items.menuProduit', 'orders'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $joursAffiches = $reservation->items->groupBy(fn ($item) => $item->date->format('Y-m-d'));

        return view('site.pages.reservation-menu-success', compact('reservation', 'joursAffiches'));
    }

    /**
     * État actuel du panier semaine pour un menu donné (pour préremplir la carte au chargement).
     */
    private function cartSemaine(int $menuSemaineId): array
    {
        $cart = Session::get('cart_menu_semaine', []);
        if (($cart['menu_semaine_id'] ?? null) != $menuSemaineId) {
            return [];
        }

        return $cart['jours'] ?? [];
    }

    /**
     * Calcule, à partir d'une sélection { date: { menu_produit_id: quantity } }, le nombre de
     * jours distincts commandés, puis le prix de chaque ligne (normal ou réduit selon le seuil
     * de la semaine, propre à chaque plat). Ne fait jamais confiance à un prix envoyé par le
     * client — toujours recalculé ici à partir de MenuProduit::prixPourJours().
     */
    private function calculerSelection(array $cartJours, MenuSemaine $menuSemaine): array
    {
        $menuProduitIds = collect($cartJours)->flatMap(fn ($lignes) => array_keys($lignes))->unique();
        $menuProduits = MenuProduit::whereIn('id', $menuProduitIds)->where('disponible', true)->get()->keyBy('id');

        $nombreJours = 0;
        $itemsBruts = [];

        foreach ($cartJours as $date => $lignes) {
            $totalJour = 0;
            foreach ($lignes as $menuProduitId => $quantity) {
                $quantity = (int) $quantity;
                if ($quantity <= 0 || !isset($menuProduits[$menuProduitId])) {
                    continue;
                }
                $totalJour += $quantity;
                $itemsBruts[] = [
                    'date'            => $date,
                    'menu_produit_id' => (int) $menuProduitId,
                    'menu_produit'    => $menuProduits[$menuProduitId],
                    'quantity'        => $quantity,
                ];
            }
            if ($totalJour > 0) {
                $nombreJours++;
            }
        }

        $montantTotal = 0;
        $quantiteTotale = 0;
        $items = [];

        foreach ($itemsBruts as $item) {
            $prixUnitaire = $item['menu_produit']->prixPourJours($nombreJours, $menuSemaine->seuil_jours);
            $total = $item['quantity'] * $prixUnitaire;
            $montantTotal += $total;
            $quantiteTotale += $item['quantity'];
            $items[] = $item + ['prix_unitaire' => $prixUnitaire, 'total' => $total];
        }

        return [
            'nombreJours'  => $nombreJours,
            'items'        => $items,
            // Prix moyen pondéré, à titre informatif uniquement — chaque ligne a son propre prix (voir 'items').
            'prixUnitaire' => $quantiteTotale > 0 ? $montantTotal / $quantiteTotale : 0,
            'montantTotal' => $montantTotal,
        ];
    }
}
