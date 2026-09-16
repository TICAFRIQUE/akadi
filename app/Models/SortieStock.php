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

        // Mettre à jour le stock lors de la création. Passe par decrementerStock()
        // (verrouillage de ligne + transaction) au lieu d'un lire-puis-écrire non
        // protégé : sans ça, une sortie manuelle concurrente à une vente pouvait
        // écraser silencieusement la décrémentation de la vente.
        static::created(function ($sortie) {
            $productBase = $sortie->productBase;
            if ($productBase) {
                $productBase->decrementerStock($sortie->quantite, [
                    'type'           => \App\Models\StockMovement::TYPE_SORTIE,
                    'reference_type' => 'sortie_stock',
                    'reference_id'   => $sortie->id,
                    'user_id'        => $sortie->user_id,
                    'note'           => $sortie->motif,
                ]);
            }
        });

        // Restaurer le stock lors de la suppression (même raisonnement : verrouillage
        // via incrementerStock() au lieu d'un +=/save() non protégé).
        static::deleted(function ($sortie) {
            $productBase = $sortie->productBase;
            if ($productBase) {
                $productBase->incrementerStock($sortie->quantite, [
                    'type'           => \App\Models\StockMovement::TYPE_SORTIE,
                    'reference_type' => 'sortie_stock',
                    'reference_id'   => $sortie->id,
                    'note'           => 'Suppression de la sortie de stock',
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
