<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LibelleDepense extends Model
{
    use HasFactory, SoftDeletes, sluggable;

    public $incrementing = false;

    protected $fillable = [
        'libelle',
        'slug',
        'description',
        'categorie_depense_id',
        'user_id',

    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'libelle_depenses', 'length' => 10, 'prefix' =>
            mt_rand()]);
        });
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'libelle'
            ]
        ];
    }




    public function categorie_depense()
    {
        return $this->belongsTo(CategorieDepense::class, 'categorie_depense_id');
    }


    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
