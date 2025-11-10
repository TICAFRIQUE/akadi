<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model implements HasMedia
{
    use HasFactory,

        InteractsWithMedia,
        SoftDeletes,
        Sluggable;

    public $incrementing = false;



    protected $fillable = [
        'code',
        'title',
        'slug',
        'price',
        'description',
        'collection_id',
        'sub_category_id',
        'disponibilite',
        'user_id',
        'montant_remise',
        'pourcentage_remise',
        'date_debut_remise',
        'date_fin_remise',
        'status_remise', // en cour, terminer , bientot
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'products', 'length' => 10, 'prefix' => mt_rand()]);
            $model->code = IdGenerator::generate(['table' => 'products', 'field' => 'code', 'length' => 10, 'prefix' => 'Z-' . mt_rand()]);
        });
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }


    public function subcategorie()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function tailles()
    {
        return $this->hasMany('App\Models\Taille');
    }

    public function pointures()
    {
        return $this->hasMany('App\Models\Pointure');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot(['quantity', 'unit_price', 'total', 'options', 'available'])
            ->withTimestamps();
    }

    public function commentaires(): HasMany
    {
        return $this->hasMany(Commentaire::class);
    }


    public function coupon()
    {
        return $this->belongsToMany(Coupon::class);
    }


    /*******resize image */

    //     public function registerMediaConversions(Media $media = null)
    // {
    //     $this->addMediaConversion('thumb')
    //         ->width(150)
    //         ->height(100);
    //     $this->addMediaConversion('bigthumb')
    //         ->width(300)
    //         ->height(100);
    // }

}
