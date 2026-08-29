<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AchatLigne extends Model
{
    use HasFactory;

    protected $fillable = [
        'achat_id',
        'product_base_id',
        'quantite',
        'prix_unitaire',
        'montant_ligne',
        'notes',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'montant_ligne' => 'decimal:2',
    ];

    /**
     * Boot method pour les événements du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Calculer le montant ligne avant la création
        static::creating(function ($ligne) {
            $ligne->montant_ligne = $ligne->quantite * $ligne->prix_unitaire;
        });

        // Recalculer si modification
        static::updating(function ($ligne) {
            if ($ligne->isDirty(['quantite', 'prix_unitaire'])) {
                $ligne->montant_ligne = $ligne->quantite * $ligne->prix_unitaire;
            }
        });

        // Incrémenter le stock après création
        static::created(function ($ligne) {
            $productBase = $ligne->productBase;
            if ($productBase) {
                $productBase->incrementerStock($ligne->quantite, [
                    'type'           => \App\Models\StockMovement::TYPE_ACHAT,
                    'reference_type' => 'achat_ligne',
                    'reference_id'   => $ligne->id,
                ]);
            }
        });

        // Ajuster le stock si modification de quantité
        static::updated(function ($ligne) {
            if ($ligne->isDirty('quantite')) {
                $productBase = $ligne->productBase;
                if ($productBase) {
                    $ancienneQuantite = $ligne->getOriginal('quantite');
                    $nouvelleQuantite = $ligne->quantite;
                    $difference = $nouvelleQuantite - $ancienneQuantite;

                    $meta = [
                        'type'           => \App\Models\StockMovement::TYPE_ACHAT,
                        'reference_type' => 'achat_ligne',
                        'reference_id'   => $ligne->id,
                        'note'           => 'Correction de quantité sur ligne d\'achat',
                    ];

                    if ($difference > 0) {
                        $productBase->incrementerStock($difference, $meta);
                    } else {
                        $productBase->decrementerStock(abs($difference), $meta);
                    }
                }
            }
        });

        // Décrémenter le stock si suppression
        static::deleted(function ($ligne) {
            $productBase = $ligne->productBase;
            if ($productBase) {
                $productBase->decrementerStock($ligne->quantite, [
                    'type'           => \App\Models\StockMovement::TYPE_ACHAT,
                    'reference_type' => 'achat_ligne',
                    'reference_id'   => $ligne->id,
                    'note'           => 'Suppression de la ligne d\'achat',
                ]);
            }
        });
    }

    /**
     * Relation avec l'achat
     */
    public function achat()
    {
        return $this->belongsTo(Achat::class, 'achat_id');
    }

    /**
     * Relation avec le produit de base
     */
    public function productBase()
    {
        return $this->belongsTo(ProductBase::class, 'product_base_id');
    }
}
