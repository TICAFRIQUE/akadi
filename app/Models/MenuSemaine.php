<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class MenuSemaine extends Model
{
    protected $table = 'menus_semaine';

    protected $fillable = [
        'titre',
        'date_debut',
        'date_fin',
        'actif',
        'prix_normal',
        'prix_reduit',
        'seuil_jours',
        'lien_token',
        'created_by',
    ];

    protected $casts = [
        'date_debut'  => 'date',
        'date_fin'    => 'date',
        'actif'       => 'boolean',
        'prix_normal' => 'double',
        'prix_reduit' => 'double',
        'seuil_jours' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (MenuSemaine $semaine) {
            if (empty($semaine->lien_token)) {
                $semaine->lien_token = Str::random(32);
            }
        });
    }

    public function menusJour(): HasMany
    {
        return $this->hasMany(MenuJour::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Prix par plat applicable pour un nombre de jours commandés donné.
     */
    public function prixPourJours(int $nombreJours): float
    {
        return $nombreJours >= $this->seuil_jours ? $this->prix_reduit : $this->prix_normal;
    }
}
