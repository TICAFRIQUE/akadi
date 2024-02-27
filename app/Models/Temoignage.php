<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temoignage extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
