<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', //code du coupon
        // 'montant_coupon',
        'pourcentage_coupon',
        'date_debut_coupon',
        'date_fin_coupon',
        'status_coupon', // en cour, terminer , bientot
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
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
