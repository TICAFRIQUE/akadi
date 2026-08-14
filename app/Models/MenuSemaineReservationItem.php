<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuSemaineReservationItem extends Model
{
    protected $fillable = [
        'menu_semaine_reservation_id',
        'date',
        'menu_produit_id',
        'quantity',
        'prix_unitaire',
    ];

    protected $casts = [
        'date'          => 'date',
        'quantity'      => 'integer',
        'prix_unitaire' => 'double',
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(MenuSemaineReservation::class, 'menu_semaine_reservation_id');
    }

    public function menuProduit(): BelongsTo
    {
        return $this->belongsTo(MenuProduit::class);
    }
}
