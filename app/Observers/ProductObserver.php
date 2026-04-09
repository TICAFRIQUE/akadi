<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{
    /**
     * Vide le cache après la création
     */
    public function created(Product $product): void
    {
        $this->flushCache($product);
    }

    /**
     * Vide le cache après la mise à jour
     */
    public function updated(Product $product): void
    {
        $this->flushCache($product);
    }

    /**
     * Vide le cache après la suppression
     */
    public function deleted(Product $product): void
    {
        $this->flushCache($product);
    }

    /**
     * Vide le cache après la restauration
     */
    public function restored(Product $product): void
    {
        $this->flushCache($product);
    }

    /**
     * Vide uniquement le cache du Product modifié
     */
    private function flushCache(Product $product): void
    {
        // Vider le cache de ce produit spécifique
        Cache::forget('product_' . $product->id);
        Cache::forget('product_slug_' . $product->slug);
        
        // Vider le cache de la liste des produits
        Cache::forget('products_list');
        
        // Vider le cache des catégories si le produit les a
        if ($product->subcategorie) {
            Cache::forget('subcategory_' . $product->sub_category_id . '_products');
        }
        
        // Si tu uses des tags, tu peux aussi faire :
        // Cache::tags(['products', 'product_' . $product->id])->flush();
    }
}
