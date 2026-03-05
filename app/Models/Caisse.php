<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Caisse extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'statut',
        'actif',
        'user_id',
        'prise_en_charge_at',
    ];

    protected $casts = [
        'actif'             => 'boolean',
        'prise_en_charge_at' => 'datetime',
    ];

    // Statuts possibles
    const STATUT_DISPONIBLE = 'disponible';
    const STATUT_OCCUPEE    = 'occupee';
    const STATUT_FERMEE     = 'fermee';

    /**
     * L'utilisateur actuellement sur cette caisse
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Toutes les commandes passées par cette caisse
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeDisponible($query)
    {
        return $query->where('statut', self::STATUT_DISPONIBLE)->where('actif', true);
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    /**
     * Attribuer la caisse à un utilisateur
     */
    public function prendreEnCharge(User $user): void
    {
        $this->update([
            'statut'             => self::STATUT_OCCUPEE,
            'user_id'            => $user->id,
            'prise_en_charge_at' => now(),
        ]);
    }

    /**
     * Libérer la caisse
     */
    public function liberer(): void
    {
        $this->update([
            'statut'             => self::STATUT_DISPONIBLE,
            'user_id'            => null,
            'prise_en_charge_at' => null,
        ]);
    }
}
