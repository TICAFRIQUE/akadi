<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Depense extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = false;

    protected $fillable = [
        'libelle',
        'montant',
        'date_depense',
        'description',
        'categorie_depense_id',
        'libelle_depense_id',
        'user_id',
        'paie_id',
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'depenses', 'length' => 10, 'prefix' =>
            mt_rand()]);
        });
    }



    public function categorie_depense()
    {
        return $this->belongsTo(CategorieDepense::class, 'categorie_depense_id');
    }

    public function libelle_depense()
    {
        return $this->belongsTo(LibelleDepense::class, 'libelle_depense_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
