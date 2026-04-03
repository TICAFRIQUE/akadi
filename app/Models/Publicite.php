<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;

class Publicite extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'type',
        'url',
        'texte',
        'discount', //remise
        'button_name', // nom du boutton
        'status',  //active ou desactiver
        'date_debut_pub',
        'date_fin_pub',
        'status_pub', // en cour, bientot, termine
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        // Version WebP optimisée pour le slider (desktop)
        $this->addMediaConversion('slider')
            ->width(1920)
            ->height(810)
            ->format('webp')
            ->quality(80)
            ->nonQueued();

        // Version WebP pour l'arrière-plan (plus légère)
        $this->addMediaConversion('background')
            ->width(1920)
            ->height(810)
            ->format('webp')
            ->quality(70)
            ->nonQueued();

        // Thumbnail pour le back-office
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(200)
            ->format('webp')
            ->quality(60)
            ->nonQueued();
    }
}
