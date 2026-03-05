<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'code',
        'actif',
        'icone',
        'position',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true)->orderBy('position');
    }
}
