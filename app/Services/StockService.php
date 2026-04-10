<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductBase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockService
{
    /**
     * Attacher un produit à une commande et décrémenter le stock
     * 
     * @param Order $order
     * @param int $productId
     * @param array $pivotData ['quantity', 'unit_price', 'total', 'options', 'available']
     * @return void
     */
    public function attachProductAndDecrementStock(Order $order, int $productId, array $pivotData)
    {
        $product = Product::with(['productBase', 'productBases'])->find($productId);

        if (!$product) {
            Log::warning('Produit introuvable', ['product_id' => $productId]);
            return;
        }

        // Ajouter le coefficient dans les données pivot (pour l'historique à partir de l'ancienne relation)
        if ($product->coefficient && $product->product_base_id) {
            $pivotData['coefficient'] = $product->coefficient;
        }

        // Attacher le produit à la commande
        $order->products()->attach($productId, $pivotData);

        // Chercher les multiples productBases via la relation pivot
        $productBases = $product->productBases()->get();

        // Si le produit a des productBases via la pivot, les traiter tous
        if ($productBases->count() > 0) {
            foreach ($productBases as $productBase) {
                $coefficient = $productBase->pivot->coefficient;
                $quantiteVendue = $pivotData['quantity'];
                $quantiteADecrementer = $quantiteVendue * $coefficient;

                $success = $productBase->decrementerStock($quantiteADecrementer);

                if ($success) {
                    Log::info('Stock décrémenté avec succès', [
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->title,
                        'product_base_id' => $productBase->id,
                        'product_base_nom' => $productBase->nom,
                        'quantite_vendue' => $quantiteVendue,
                        'coefficient' => $coefficient,
                        'quantite_decrementee' => $quantiteADecrementer,
                        'stock_restant' => $productBase->stock,
                    ]);
                } else {
                    Log::warning('Stock insuffisant lors de la vente', [
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'product_name' => $product->title,
                        'product_base_id' => $productBase->id,
                        'product_base_nom' => $productBase->nom,
                        'stock_actuel' => $productBase->stock,
                        'quantite_necessaire' => $quantiteADecrementer,
                    ]);
                }
            }
        }
        // Fallback: si pas de pivot, utiliser l'ancienne logique
        elseif ($product->productBase && $product->coefficient) {
            $quantiteVendue = $pivotData['quantity'];
            $quantiteADecrementer = $quantiteVendue * $product->coefficient;

            $success = $product->productBase->decrementerStock($quantiteADecrementer);

            if ($success) {
                Log::info('Stock décrémenté avec succès', [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->title,
                    'product_base_id' => $product->productBase->id,
                    'product_base_nom' => $product->productBase->nom,
                    'quantite_vendue' => $quantiteVendue,
                    'coefficient' => $product->coefficient,
                    'quantite_decrementee' => $quantiteADecrementer,
                    'stock_restant' => $product->productBase->stock,
                ]);
            } else {
                Log::warning('Stock insuffisant lors de la vente', [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->title,
                    'product_base_id' => $product->productBase->id,
                    'product_base_nom' => $product->productBase->nom,
                    'stock_actuel' => $product->productBase->stock,
                    'quantite_necessaire' => $quantiteADecrementer,
                ]);
            }
        }
    }

    /**
     * Attacher plusieurs produits à une commande et décrémenter les stocks
     * 
     * @param Order $order
     * @param array $cart Format: ['product_id' => ['quantity' => x, 'price' => y, ...], ...]
     * @return void
     */
    public function attachCartAndDecrementStock(Order $order, array $cart)
    {
        foreach ($cart as $productId => $item) {
            $this->attachProductAndDecrementStock($order, $productId, [
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
                'total' => $item['price'] * $item['quantity'],
                'options' => $item['options'] ?? null,
                'available' => $item['available'] ?? 'yes',
            ]);
        }
    }

    /**
     * Réincrémenter le stock lors de l'annulation d'une commande
     * 
     * @param Order $order
     * @return void
     */
    // public function reincrementStockOnCancellation(Order $order)
    // {
    //     foreach ($order->products as $product) {
    //         // Chercher les multiples productBases via la relation pivot
    //         $productBases = $product->productBases()->get();

    //         // Si le produit a des productBases via la pivot, les traiter tous
    //         if ($productBases->count() > 0) {
    //             foreach ($productBases as $productBase) {
    //                 $quantiteVendue = $product->pivot->quantity;
    //                 $coefficient = $productBase->pivot->coefficient;
    //                 $quantiteAReincrémenter = $quantiteVendue * $coefficient;

    //                 $productBase->incrementerStock($quantiteAReincrémenter);

    //                 Log::info('Stock réincrémenté suite à annulation', [
    //                     'order_id' => $order->id,
    //                     'product_id' => $product->id,
    //                     'product_base_id' => $productBase->id,
    //                     'quantite_reincrémentée' => $quantiteAReincrémenter,
    //                     'stock_actuel' => $productBase->stock,
    //                 ]);
    //             }
    //         }
    //         // Fallback: si pas de pivot, utiliser l'ancienne logique
    //         elseif ($product->productBase && $product->pivot->coefficient) {
    //             $quantiteVendue = $product->pivot->quantity;
    //             $coefficient = $product->pivot->coefficient;
    //             $quantiteAReincrémenter = $quantiteVendue * $coefficient;

    //             $product->productBase->incrementerStock($quantiteAReincrémenter);

    //             Log::info('Stock réincrémenté suite à annulation', [
    //                 'order_id' => $order->id,
    //                 'product_id' => $product->id,
    //                 'product_base_id' => $product->productBase->id,
    //                 'quantite_reincrémentée' => $quantiteAReincrémenter,
    //                 'stock_actuel' => $product->productBase->stock,
    //             ]);
    //         }
    //     }
    // }

    public function reincrementStockOnCancellation(Order $order)
    {
        // Utiliser les snapshots figés au moment de la vente
        $snapshots = DB::table('order_product_base')
            ->where('order_id', $order->id)
            ->get();

        if ($snapshots->isEmpty()) {
            // Fallback : ancienne logique pour les commandes avant la migration
            foreach ($order->products as $product) {
                $productBases = $product->productBases()->get();

                if ($productBases->count() > 0) {
                    foreach ($productBases as $productBase) {
                        $quantiteVendue        = $product->pivot->quantity;
                        $coefficient           = $productBase->pivot->coefficient;
                        $quantiteAReincrementer = $quantiteVendue * $coefficient;
                        $productBase->incrementerStock($quantiteAReincrementer);

                        Log::info('Stock réincrémenté (fallback) suite à annulation', [
                            'order_id'               => $order->id,
                            'product_id'             => $product->id,
                            'product_base_id'        => $productBase->id,
                            'quantite_reincrémentée' => $quantiteAReincrementer,
                        ]);
                    }
                } elseif ($product->productBase && $product->pivot->coefficient) {
                    $quantiteVendue        = $product->pivot->quantity;
                    $coefficient           = $product->pivot->coefficient;
                    $quantiteAReincrementer = $quantiteVendue * $coefficient;
                    $product->productBase->incrementerStock($quantiteAReincrementer);

                    Log::info('Stock réincrémenté (fallback ancien) suite à annulation', [
                        'order_id'               => $order->id,
                        'product_id'             => $product->id,
                        'product_base_id'        => $product->productBase->id,
                        'quantite_reincrémentée' => $quantiteAReincrementer,
                    ]);
                }
            }
            return;
        }

        // Nouvelle logique : utiliser les snapshots
        foreach ($snapshots as $snap) {
            $productBase = ProductBase::find($snap->product_base_id);
            if (!$productBase) continue;

            $productBase->incrementerStock($snap->quantity_consumed);

            Log::info('Stock réincrémenté (snapshot) suite à annulation', [
                'order_id'               => $order->id,
                'product_id'             => $snap->product_id,
                'product_base_id'        => $snap->product_base_id,
                'quantite_reincrémentée' => $snap->quantity_consumed,
                'coefficient_snapshot'   => $snap->coefficient,
                'stock_actuel'           => $productBase->fresh()->stock,
            ]);
        }
    }

    /**
     * Vérifier si le stock est suffisant avant une vente
     * 
     * @param array $cart
     * @return array ['success' => bool, 'message' => string, 'products_insuffisants' => array]
     */
    public function checkStockAvailability(array $cart)
    {
        $produitsInsuffisants = [];

        foreach ($cart as $productId => $item) {
            $product = Product::with(['productBase', 'productBases'])->find($productId);

            if (!$product) {
                continue;
            }

            // Chercher les multiples productBases via la relation pivot
            $productBases = $product->productBases()->get();

            // Si le produit a des productBases via la pivot, les vérifier tous
            if ($productBases->count() > 0) {
                foreach ($productBases as $productBase) {
                    $coefficient = $productBase->pivot->coefficient;
                    $quantiteNecessaire = $item['quantity'] * $coefficient;
                    $stockDisponible = $productBase->stock;

                    if ($stockDisponible < $quantiteNecessaire) {
                        $produitsInsuffisants[] = [
                            'product_id' => $product->id,
                            'product_name' => $product->title,
                            'product_base_nom' => $productBase->nom,
                            'stock_disponible' => $stockDisponible,
                            'quantite_necessaire' => $quantiteNecessaire,
                            'unite' => $productBase->unite,
                        ];
                    }
                }
            }
            // Fallback: si pas de pivot, utiliser l'ancienne logique
            elseif ($product->productBase && $product->coefficient) {
                $quantiteNecessaire = $item['quantity'] * $product->coefficient;
                $stockDisponible = $product->productBase->stock;

                if ($stockDisponible < $quantiteNecessaire) {
                    $produitsInsuffisants[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->title,
                        'product_base_nom' => $product->productBase->nom,
                        'stock_disponible' => $stockDisponible,
                        'quantite_necessaire' => $quantiteNecessaire,
                        'unite' => $product->productBase->unite,
                    ];
                }
            }
        }

        if (count($produitsInsuffisants) > 0) {
            return [
                'success' => false,
                'message' => 'Stock insuffisant pour certains produits',
                'products_insuffisants' => $produitsInsuffisants,
            ];
        }

        return [
            'success' => true,
            'message' => 'Stock suffisant pour tous les produits',
            'products_insuffisants' => [],
        ];
    }
}
