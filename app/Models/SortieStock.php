<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SortieStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero',
        'date_sortie',
        'product_base_id',
        'quantite',
        'motif',
        'description',
        'user_id',
    ];

    protected $casts = [
        'date_sortie' => 'date',
        'quantite' => 'decimal:2',
    ];

    //creer des libelle motif de sortie
    public static function getMotifs()
    {
        return [
            'vente' => 'Vente',
            'erreur-achat' => 'Erreur d\'achat',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        // Mettre à jour le stock lors de la création
        static::created(function ($sortie) {
            $productBase = $sortie->productBase;
            if ($productBase) {
                $productBase->stock -= $sortie->quantite;
                $productBase->save();

                \App\Models\StockMovement::create([
                    'product_base_id' => $productBase->id,
                    'type'            => \App\Models\StockMovement::TYPE_SORTIE,
                    'quantity'        => -$sortie->quantite,
                    'stock_apres'     => $productBase->stock,
                    'reference_type'  => 'sortie_stock',
                    'reference_id'    => $sortie->id,
                    'user_id'         => $sortie->user_id,
                    'note'            => $sortie->motif,
                ]);
            }
        });

        // Restaurer le stock lors de la suppression
        static::deleted(function ($sortie) {
            $productBase = $sortie->productBase;
            if ($productBase) {
                $productBase->stock += $sortie->quantite;
                $productBase->save();

                \App\Models\StockMovement::create([
                    'product_base_id' => $productBase->id,
                    'type'            => \App\Models\StockMovement::TYPE_SORTIE,
                    'quantity'        => $sortie->quantite,
                    'stock_apres'     => $productBase->stock,
                    'reference_type'  => 'sortie_stock',
                    'reference_id'    => $sortie->id,
                    'note'            => 'Suppression de la sortie de stock',
                ]);
            }
        });
    }

    /**
     * Relation avec le produit de base
     */
    public function productBase()
    {
        return $this->belongsTo(ProductBase::class);
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
