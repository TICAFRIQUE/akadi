<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Gérer l'événement "created" pour Order
     * Cette méthode sera appelée après la création de la commande
     */
    public function created(Order $order)
    {
        // La décrémentation se fera lors de l'attachement des produits
        // via saved() qui est appelé après l'association
    }

    /**
     * Gérer l'événement "saved" pour Order
     * Décrémente automatiquement le stock des produits de base
     */
    public function saved(Order $order)
    {
        // Vérifier si la commande a des produits
        if ($order->products && $order->products->count() > 0) {
            $this->decrementerStockProduitBase($order);
        }
    }

    /**
     * Décrémenter le stock des produits de base
     */
    private function decrementerStockProduitBase(Order $order)
    {
        foreach ($order->products as $product) {
            // Récupérer le produit de base associé et le coefficient
            if ($product->productBase && $product->coefficient) {
                $quantiteVendue = $product->pivot->quantity;
                $coefficient = $product->coefficient;
                $quantiteADecrementer = $quantiteVendue * $coefficient;

                // Décrémenter le stock du produit de base
                $productBase = $product->productBase;
                $success = $productBase->decrementerStock($quantiteADecrementer);

                if (!$success) {
                    Log::warning('Stock insuffisant pour produit de base', [
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_base_id' => $productBase->id,
                        'product_base_nom' => $productBase->nom,
                        'stock_actuel' => $productBase->stock,
                        'quantite_necessaire' => $quantiteADecrementer,
                    ]);
                }

                Log::info('Stock décrémenté', [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_base_id' => $productBase->id,
                    'product_base_nom' => $productBase->nom,
                    'quantite_decrementee' => $quantiteADecrementer,
                    'stock_restant' => $productBase->stock,
                ]);
            }
        }
    }

    /**
     * Gérer l'événement "deleting" pour Order
     * Réincrémenter le stock si la commande est annulée/supprimée
     */
    public function deleting(Order $order)
    {
        // Optionnel: Réincrémenter le stock si la commande est annulée
        foreach ($order->products as $product) {
            if ($product->productBase && $product->pivot->coefficient) {
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
                if ($product->productBase && $product->pivot->coefficient) {
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
