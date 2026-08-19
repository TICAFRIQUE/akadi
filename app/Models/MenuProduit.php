<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuProduit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'menu_jour_id',
        'plat_id',
        'nom',
        'description',
        'prix',
        'prix_normal',
        'prix_reduit',
        'disponible',
    ];

    protected $casts = [
        'prix'        => 'double',
        'prix_normal' => 'double',
        'prix_reduit' => 'double',
        'disponible'  => 'boolean',
    ];

    public function menuJour(): BelongsTo
    {
        return $this->belongsTo(MenuJour::class);
    }

    /** Prix applicable pour ce plat selon le nombre de jours commandés (menu de la semaine) */
    public function prixPourJours(int $nombreJours, int $seuilJours): float
    {
        if ($nombreJours >= $seuilJours && $this->prix_reduit !== null) {
            return $this->prix_reduit;
        }
        return $this->prix_normal ?? $this->prix;
    }

    public function plat(): BelongsTo
    {
        return $this->belongsTo(Plat::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_menu_produit')
            ->withPivot(['quantity', 'unit_price', 'discount', 'type_discount', 'prix_apres_remise', 'total'])
            ->withTimestamps();
    }
}
