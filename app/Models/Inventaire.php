<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_inventaire',
        'user_id',
        'resultat',
    ];

    public function lignes()
    {
        return $this->hasMany(InventoryLine::class, 'inventaire_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
