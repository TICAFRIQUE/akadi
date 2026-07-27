<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plat extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'prix',
        'actif',
    ];

    protected $casts = [
        'prix'  => 'double',
        'actif' => 'boolean',
    ];

    public function menuProduits(): HasMany
    {
        return $this->hasMany(MenuProduit::class);
    }
}
