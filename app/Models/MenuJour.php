<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuJour extends Model
{
    protected $table = 'menus_jour';

    protected $fillable = [
        'date',
        'actif',
        'note',
        'created_by',
    ];

    protected $casts = [
        'date'  => 'date',
        'actif' => 'boolean',
    ];

    public function menuProduits(): HasMany
    {
        return $this->hasMany(MenuProduit::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Le menu du jour actuel (aujourd'hui) */
    public function scopeDuJour($query)
    {
        return $query->whereDate('date', today());
    }
}
