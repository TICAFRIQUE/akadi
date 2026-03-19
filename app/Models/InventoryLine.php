<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventaire_id',
        'product_base_id',
        'stock_dernier_inventaire',
        'stock_ajoute',
        'stock_total',
        'stock_vendu',
        'stock_sortie',
        'stock_restant',
        'stock_physique',
        'ecart',
        'resultat',
    ];

    public function inventaire()
    {
        return $this->belongsTo(Inventaire::class, 'inventaire_id');
    }

    public function productBase()
    {
        return $this->belongsTo(ProductBase::class, 'product_base_id');
    }
}
