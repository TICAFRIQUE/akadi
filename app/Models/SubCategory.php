<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubCategory extends Model implements HasMedia
{
    use HasFactory,
    InteractsWithMedia;

   public $incrementing = false;


    protected $fillable = [
        'name',
        'category_id',
        // 'display_on_category', // afficher dans la zone de category
        'type_affichage',
        'created_at',
        'updated_at'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'sub_categories', 'length' => 10, 'prefix' =>mt_rand()]);
    
        });
    }
    

    public function products() : HasMany{

        return  $this->hasMany(Product::class);
    }
    

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
