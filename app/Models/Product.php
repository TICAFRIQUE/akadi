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
        'product_base_id',  // Produit de base associé
        'coefficient',      // Coefficient de consommation
        'montant_remise',
        'pourcentage_remise',
        'date_debut_remise',
        'date_fin_remise',
        'status_remise', // en cour, terminer , bientot
        'stock',          // null = infini
        'stock_alerte',   // seuil alerte stock bas
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

    public function productBase()
    {
        return $this->belongsTo(ProductBase::class, 'product_base_id');
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
        return $this->belongsToMany(Order::class)->withPivot(['quantity', 'unit_price', 'total', 'options', 'available', 'coefficient'])
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



    /**
     * Retourne le stock disponible pour ce produit selon la nouvelle logique.
     * - Si lié à un product_base et coefficient > 0 : stock calculé
     * - Sinon : stock infini
     */
    public function getStockDisponible()
    {
        // Si le produit est lié à un product_base et a un coefficient > 0
        if ($this->product_base_id && $this->coefficient > 0 && $this->productBase) {
            // Stock du produit de base ajusté par le coefficient
            return floor($this->productBase->stock / $this->coefficient);
        }
        // Sinon, stock infini
        return INF;
    }

    /**
     * Indique si le stock est considéré comme infini (non géré)
     */
    public function isStockInfini()
    {
        return !$this->product_base_id || !$this->coefficient || $this->coefficient <= 0;
    }

    /**
     * Retourne le seuil d'alerte pour ce produit (via product_base)
     */
    public function getStockAlerte()
    {
        if ($this->product_base_id && $this->productBase) {
            return $this->productBase->stock_alerte;
        }
        return null;
    }

    /**
     * Retourne true si le stock est en alerte (via product_base)
     */
    public function isStockAlerte()
    {
        $alerte = $this->getStockAlerte();
        $stock = $this->getStockDisponible();
        if ($alerte !== null && $stock !== INF) {
            return $stock <= $alerte;
        }
        return false;
    }

    //Conversion & optimisation d'image avec Spatie Media Library

    // public function registerMediaConversions(?Media $media = null): void
    // {
    //     $this->addMediaConversion('thumb')
    //         ->width(400)
    //         ->height(400)
    //         ->fit('contain')   // string en v10
    //         ->queued();

    //     $this->addMediaConversion('webp')
    //         ->width(800)
    //         ->height(800)
    //         ->fit('contain')
    //         ->queued();
    // }

    // public function registerMediaConversions(?Media $media = null): void
    // {
    //     $this->addMediaConversion('thumb')
    //         ->width(400)
    //         ->height(400)
    //         ->fit('crop')
    //         ->queued();

    //     $this->addMediaConversion('bigthumb')
    //         ->width(800)
    //         ->height(800)
    //         ->fit('crop')
    //         ->queued();
    // }


    public function registerMediaConversions(?Media $media = null): void
{
    $this->addMediaConversion('thumb')
        ->width(400)
        ->height(400)
        ->fit(\Spatie\Image\Enums\Fit::Max)
        ->quality(90)
        ->queued();

    $this->addMediaConversion('bigthumb')
        ->width(800)
        ->height(800)
        ->fit(\Spatie\Image\Enums\Fit::Max)
        ->quality(90)
        ->queued();
}
}
