<?php

namespace App\Observers;

use App\Models\ProductBase;
use Illuminate\Support\Facades\Cache;

class ProductBaseObserver
{
    /**
     * Vide le cache après la création
     */
    public function created(ProductBase $productBase): void
    {
        $this->flushCache($productBase);
    }

    /**
     * Vide le cache après la mise à jour
     */
    public function updated(ProductBase $productBase): void
    {
        $this->flushCache($productBase);
    }

    /**
     * Vide le cache après la suppression
     */
    public function deleted(ProductBase $productBase): void
    {
        $this->flushCache($productBase);
    }

    /**
     * Vide le cache après la restauration
     */
    public function restored(ProductBase $productBase): void
    {
        $this->flushCache($productBase);
    }

    /**
     * Vide uniquement le cache du ProductBase modifié
     */
    private function flushCache(ProductBase $productBase): void
    {
        // Vider le cache de ce produit de base spécifique
        Cache::forget('product_base_' . $productBase->id);
        
        // Vider le cache de la liste des produits de base
        Cache::forget('product_bases_list');
        
        // Si tu uses des tags, tu peux aussi faire :
        // Cache::tags(['product_base', 'product_base_' . $productBase->id])->flush();
    }
}
