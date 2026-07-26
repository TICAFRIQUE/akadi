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
        'nom',
        'description',
        'prix',
        'disponible',
    ];

    protected $casts = [
        'prix'       => 'double',
        'disponible' => 'boolean',
    ];

    public function menuJour(): BelongsTo
    {
        return $this->belongsTo(MenuJour::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_menu_produit')
            ->withPivot(['quantity', 'unit_price', 'discount', 'type_discount', 'prix_apres_remise', 'total'])
            ->withTimestamps();
    }
}
