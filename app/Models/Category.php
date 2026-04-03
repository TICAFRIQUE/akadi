<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media; // ← le bon import
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public $incrementing = false;

    protected $fillable = [
        'name',
        'type',
        'type_affichage',
        'is_active',
        'created_at',
        'updated_at'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate([
                'table'  => 'categories',
                'length' => 10,
                'prefix' => mt_rand()
            ]);
        });
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(400)
            ->fit(\Spatie\Image\Enums\Fit::Contain)
            ->quality(90);

        $this->addMediaConversion('bigthumb')
            ->width(800)
            ->height(800)
            ->fit(\Spatie\Image\Enums\Fit::Contain)
            ->quality(90);
    }
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

    public function subcategories(): HasMany
    {
        return $this->hasMany(SubCategory::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
