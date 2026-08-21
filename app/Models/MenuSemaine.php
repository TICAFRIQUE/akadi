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
        'seuil_jours',
        'lien_token',
        'created_by',
    ];

    protected $casts = [
        'date_debut'  => 'date',
        'date_fin'    => 'date',
        'actif'       => 'boolean',
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

    public function reservations(): HasMany
    {
        return $this->hasMany(MenuSemaineReservation::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Titre affichable : le titre saisi, sinon la période formatée */
    public function getTitreAfficheAttribute(): string
    {
        if (!empty($this->titre)) {
            return $this->titre;
        }
        return 'Semaine du ' . $this->date_debut->format('d/m/Y') . ' au ' . $this->date_fin->format('d/m/Y');
    }
}
