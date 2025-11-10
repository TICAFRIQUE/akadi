<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model implements HasMedia
{
    use HasFactory,
        InteractsWithMedia;

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
            $model->id = IdGenerator::generate(['table' => 'categories', 'length' => 10, 'prefix' =>
        mt_rand()]);
    
        });
    }
    

    public function products(): BelongsToMany
    {
        return  $this->belongsToMany(Product::class)->withTimestamps();
    }

    public function subcategories() : HasMany {
        return $this->hasMany(SubCategory::class);
    }

    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
