<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Achat extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero',
        'date_achat',
        'montant_total',
        'fournisseur',
        'fournisseur_id',
        'notes',
        'user_id',
    ];

    protected $casts = [
        'date_achat' => 'date',
        'montant_total' => 'decimal:2',
    ];

    /**
     * Boot method pour les événements du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Générer un numéro si absent
        static::creating(function ($achat) {
            if (empty($achat->numero)) {
                $achat->numero = 'ACH-' . date('YmdHis') . '-' . mt_rand(100, 999);
            }
        });

        // Recalculer le prix d'achat moyen pour tous les produits de base concernés
        static::created(function ($achat) {
            foreach ($achat->lignes as $ligne) {
                if ($ligne->productBase) {
                    $ligne->productBase->calculerPrixAchatMoyen();
                }
            }
        });

        // Recalculer après mise à jour
        static::updated(function ($achat) {
            foreach ($achat->lignes as $ligne) {
                if ($ligne->productBase) {
                    $ligne->productBase->calculerPrixAchatMoyen();
                }
            }
        });

        // Recalculer après suppression (les lignes seront supprimées en cascade)
        static::deleted(function ($achat) {
            // Les lignes déclenchent leur propre événement deleted
            // qui décrémente le stock et recalcule le prix moyen
        });
    }

    /**
     * Relation avec les lignes d'achat
     */
    public function lignes()
    {
        return $this->hasMany(AchatLigne::class, 'achat_id');
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le fournisseur
     */
    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }

    /**
     * Calculer le montant total à partir des lignes
     */
    public function calculerMontantTotal()
    {
        $this->montant_total = $this->lignes()->sum('montant_ligne');
        $this->save();
        return $this->montant_total;
    }
}
