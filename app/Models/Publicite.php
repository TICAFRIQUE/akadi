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

    protected $casts = [
        'date_debut_pub' => 'datetime',
        'date_fin_pub'   => 'datetime',
    ];

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

    /**
     * "status_pub" (bientot/en_cours/termine) était uniquement recalculé une fois par
     * jour par la commande planifiée app:update-site-status : une publicité créée ou
     * démarrant après ce recalcul quotidien affichait "terminée" jusqu'au lendemain
     * matin, même en plein milieu de sa période active. On le calcule maintenant à la
     * volée à chaque lecture, à partir des dates, pour que l'affichage soit toujours
     * exact — la commande planifiée continue de tourner, elle sert seulement à
     * archiver (status = desactive) les publicités terminées depuis longtemps.
     */
    public function getStatusPubAttribute($value)
    {
        if (!$this->date_debut_pub || !$this->date_fin_pub) {
            return $value;
        }

        $now = now();

        if ($this->date_debut_pub->gt($now)) {
            return 'bientot';
        }

        if ($this->date_fin_pub->lt($now)) {
            return 'termine';
        }

        return 'en_cours';
    }

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
