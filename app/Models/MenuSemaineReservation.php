<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuSemaineReservation extends Model
{
    const STATUT_EN_ATTENTE_PAIEMENT = 'en_attente_paiement';
    const STATUT_PAYEE               = 'payee';
    const STATUT_ANNULEE             = 'annulee';

    protected $fillable = [
        'menu_semaine_id',
        'user_id',
        'client_name',
        'client_phone',
        'nombre_jours',
        'prix_unitaire_applique',
        'montant_total',
        'mode_livraison',
        'address_yango',
        'payment_method_id',
        'wave_session_id',
        'payment_status',
        'statut',
    ];

    protected $casts = [
        'nombre_jours'           => 'integer',
        'prix_unitaire_applique' => 'double',
        'montant_total'          => 'double',
    ];

    public function menuSemaine(): BelongsTo
    {
        return $this->belongsTo(MenuSemaine::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(MenuSemaineReservationItem::class);
    }

    /** Commandes (une par jour) générées une fois le paiement confirmé */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
