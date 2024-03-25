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
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
