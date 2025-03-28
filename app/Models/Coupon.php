<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'code', //code du coupon
        // // 'montant_coupon',
        // 'pourcentage_coupon',
        // 'date_debut_coupon',
        // 'date_fin_coupon',
        // 'status_coupon', // en cour, terminer , bientot
        'nom',
        'code',
        'quantite', // nombre de bon a generer
        'utilisation_max',
        'type_remise',
        'valeur_remise',
        'montant_min',
        'montant_max',
        // 'expiration',
         'date_debut',
        'date_fin',
        'status',
        'type_coupon',
        'created_at',
        'updated_at',
        'deleted_at'
    ];


    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }


    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('nbre_utilisation')->withTimestamps();
        
    }


        // Relation avec les utilisateurs via la table pivot coupon_use
        public function userUse()
        {
            return $this->belongsToMany(User::class, 'coupon_use')->withPivot('use_count')->withTimestamps();
        }
}
