<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductBase extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Relation avec les ventes (order_product pivot)
     */
    public function ventes()
    {
        // On suppose que la table pivot s'appelle order_product et relie product_base_id à une commande
        // Si la relation doit être ajustée, il faudra adapter ici
        return $this->hasManyThrough(
            \App\Models\OrderProduct::class, // modèle pivot
            \App\Models\Product::class,      // modèle intermédiaire
            'product_base_id',                 // clé étrangère sur products
            'product_id',                      // clé étrangère sur order_product
            'id',                              // clé locale sur product_bases
            'id'                               // clé locale sur products
        );
    }
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'nom',
        'description',
        'stock',
        'stock_alerte',
        'unite',
        'prix_achat_moyen',
        'actif',
    ];

    protected $casts = [
        'stock' => 'decimal:2',
        'stock_alerte' => 'decimal:2',
        'prix_achat_moyen' => 'decimal:2',
        'actif' => 'boolean',
    ];

    /**
     * Relation avec les produits vendus
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'product_base_id');
    }

    /**
     * Relation avec les lignes d'achat
     */
    public function achatLignes()
    {
        return $this->hasMany(AchatLigne::class, 'product_base_id');
    }

    /**
     * Relation avec les sorties de stock
     */
    public function sorties()
    {
        return $this->hasMany(SortieStock::class, 'product_base_id');
    }

    /**
     * Relation avec les achats (via les lignes)
     */
    public function achats()
    {
        return $this->hasManyThrough(
            Achat::class,
            AchatLigne::class,
            'product_base_id', // Clé étrangère dans achat_lignes
            'id',               // Clé primaire dans achats
            'id',               // Clé primaire dans product_bases
            'achat_id'          // Clé étrangère dans achat_lignes pointant vers achats
        );
    }

    /**
     * Incrémenter le stock lors d'un achat
     */
    public function incrementerStock($quantite)
    {
        $this->stock += $quantite;
        $this->save();
    }

    /**
     * Décrémenter le stock lors d'une vente
     */
    public function decrementerStock($quantite)
    {
        if ($this->stock >= $quantite) {
            $this->stock -= $quantite;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Vérifier si le stock est en alerte
     */
    public function isStockFaible()
    {
        if ($this->stock_alerte !== null) {
            return $this->stock <= $this->stock_alerte;
        }
        return false;
    }

    /**
     * Calculer le prix d'achat moyen
     */
    public function calculerPrixAchatMoyen()
    {
        $lignes = $this->achatLignes()->get();
        
        if ($lignes->count() === 0) {
            $this->prix_achat_moyen = 0;
            $this->save();
            return 0;
        }

        $totalQuantite = $lignes->sum('quantite');
        $totalMontant = $lignes->sum('montant_ligne');

        if ($totalQuantite > 0) {
            $this->prix_achat_moyen = $totalMontant / $totalQuantite;
            $this->save();
            return $this->prix_achat_moyen;
        }

        $this->prix_achat_moyen = 0;
        $this->save();
        return 0;
    }
}
