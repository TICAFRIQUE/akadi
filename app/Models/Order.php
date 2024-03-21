<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;
    
    public $incrementing = false;

    protected $fillable = [
        'code',
        'quantity_product',
        'subtotal',
        'delivery_price', // prix de la livraison
        'delivery_name', // Lieu de livraison
        'address',  //preciser le lieu de livraison (adresse, ville, code postal)
        'address_yango',  //preciser le lieu de livraison (adresse, ville, code postal)
        'discount',
        'total',
        'delivery_planned', //date de livraison prevue
        'delivery_date', //date de livraison
        'status', //livre , en attente
        'raison_annulation_cmd',
        'payment method',
        'mode_livraison', // domicile, sur place, yango
        'available_product', //disponibilite
        'user_id',
        'date_order',
        'created_at',
        'updated_at',
    ];

    public static function boot()
{
    parent::boot();
    self::creating(function ($model) {
        $model->id = IdGenerator::generate(['table' => 'orders', 'length' => 10, 'prefix' =>mt_rand()]);
        $model->code = IdGenerator::generate(['table' => 'orders', 'field' => 'code', 'length' => 10, 'prefix' =>mt_rand()]);

    });
}


    public function user()
    {
        return $this->belongsTo(User::class);
    }



    public function products():BelongsToMany {
        return $this->belongsToMany(Product::class)->withPivot(['quantity','unit_price','total', 'options','available'])->withTimestamps();
    }
}
