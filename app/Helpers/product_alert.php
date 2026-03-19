<?php
// Helper pour compter les produits en alerte
use App\Models\ProductBase;
use Illuminate\Support\Facades\DB;

if (!function_exists('count_product_alertes')) {
    function count_product_alertes() {
        return ProductBase::where('stock', '<=', DB::raw('stock_alerte'))
            ->where('actif', 1)
            ->count();
    }
}
