<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
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
        'status_pub',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
