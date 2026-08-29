<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Gérer l'événement "created" pour Order
     * Cette méthode sera appelée après la création de la commande
     */
    public function created(Order $order)
    {
        OrderStatusHistory::create([
            'order_id'   => $order->id,
            'old_status' => null,
            'new_status' => $order->status,
            'user_id'    => Auth::id(),
        ]);
    }

    public function updated(Order $order)
    {
        if ($order->isDirty('status')) {
            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'old_status' => $order->getOriginal('status'),
                'new_status' => $order->status,
                'user_id'    => Auth::id(),
            ]);
        }
    }

    /**
     * NOTE : il n'y a volontairement plus de hook "saved" qui décrémente le stock ici.
     *
     * Avant, `saved()` redécrémentait le stock des product_bases à CHAQUE
     * `$order->save()`/`update()` dès que la relation `products` était chargée
     * (eager-load ou lazy-load) — pas seulement à la création. Comme la
     * décrémentation à la création est déjà gérée explicitement par
     * StockService::attachProductAndDecrementStock() (appelé depuis
     * PosController/PaymentController, à un moment où products n'est de toute
     * façon pas encore attaché donc ce hook ne servait jamais à la création),
     * ce hook ne faisait que sur-décrémenter le stock à chaque modification
     * ultérieure d'une commande : édition au POS, ajout d'acompte, mise à jour
     * du statut de paiement Wave... Résultat : le stock système divergeait de
     * plus en plus du stock physique. Supprimé pour corriger ce bug.
     */

    /**
     * Gérer l'événement "deleting" pour Order
     * Réincrémenter le stock si la commande est annulée/supprimée
     */
    public function deleting(Order $order)
    {
        // Optionnel: Réincrémenter le stock si la commande est annulée
        foreach ($order->products as $product) {
            // Chercher les multiples productBases via la relation pivot
            $productBases = $product->productBases()->get();

            // Si le produit a des productBases via la pivot, les traiter tous
            if ($productBases->count() > 0) {
                foreach ($productBases as $productBase) {
                    $quantiteVendue = $product->pivot->quantity;
                    $coefficient = $productBase->pivot->coefficient;
                    $quantiteAReincrémenter = $quantiteVendue * $coefficient;

                    $productBase->incrementerStock($quantiteAReincrémenter);

                    Log::info('Stock réincrémenté suite à annulation', [
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_base_id' => $productBase->id,
                        'quantite_reincrémentée' => $quantiteAReincrémenter,
                    ]);
                }
            }
            // Fallback: si pas de pivot, utiliser l'ancienne logique
            elseif ($product->productBase && $product->pivot->coefficient) {
                $quantiteVendue = $product->pivot->quantity;
                $coefficient = $product->pivot->coefficient; // Utiliser le coefficient historique
                $quantiteAReincrémenter = $quantiteVendue * $coefficient;

                $productBase = $product->productBase;
                $productBase->incrementerStock($quantiteAReincrémenter);

                Log::info('Stock réincrémenté suite à annulation', [
                    'order_id' => $order->id,
                    'product_base_id' => $productBase->id,
                    'quantite_reincrémentée' => $quantiteAReincrémenter,
                ]);
            }
        }
    }

    /**
     * Gérer l'événement "updating" pour Order
     * Gérer le changement de statut (ex: annulée)
     */
    public function updating(Order $order)
    {
        // Si le statut passe à "annulée", réincrémenter le stock
        if ($order->isDirty('status') && $order->status === Order::STATUS_ANNULEE) {
            foreach ($order->products as $product) {
                // Chercher les multiples productBases via la relation pivot
                $productBases = $product->productBases()->get();

                // Si le produit a des productBases via la pivot, les traiter tous
                if ($productBases->count() > 0) {
                    foreach ($productBases as $productBase) {
                        $quantiteVendue = $product->pivot->quantity;
                        $coefficient = $productBase->pivot->coefficient;
                        $quantiteAReincrémenter = $quantiteVendue * $coefficient;

                        $productBase->incrementerStock($quantiteAReincrémenter);

                        Log::info('Stock réincrémenté - commande annulée', [
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'product_base_id' => $productBase->id,
                            'quantite_reincrémentée' => $quantiteAReincrémenter,
                        ]);
                    }
                }
                // Fallback: si pas de pivot, utiliser l'ancienne logique
                elseif ($product->productBase && $product->pivot->coefficient) {
                    $quantiteVendue = $product->pivot->quantity;
                    $coefficient = $product->pivot->coefficient;
                    $quantiteAReincrémenter = $quantiteVendue * $coefficient;

                    $productBase = $product->productBase;
                    $productBase->incrementerStock($quantiteAReincrémenter);

                    Log::info('Stock réincrémenté - commande annulée', [
                        'order_id' => $order->id,
                        'product_base_id' => $productBase->id,
                        'quantite_reincrémentée' => $quantiteAReincrémenter,
                    ]);
                }
            }
        }
    }
}
