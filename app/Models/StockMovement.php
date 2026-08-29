<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    const TYPE_VENTE               = 'vente';
    const TYPE_ANNULATION_VENTE    = 'annulation_vente';
    const TYPE_ACHAT               = 'achat';
    const TYPE_SORTIE              = 'sortie';
    const TYPE_CORRECTION_INVENTAIRE = 'correction_inventaire';
    const TYPE_AJUSTEMENT_MANUEL   = 'ajustement_manuel';

    protected $fillable = [
        'product_base_id',
        'type',
        'quantity',
        'stock_apres',
        'reference_type',
        'reference_id',
        'user_id',
        'note',
    ];

    protected $casts = [
        'quantity'    => 'decimal:2',
        'stock_apres' => 'decimal:2',
    ];

    public function productBase(): BelongsTo
    {
        return $this->belongsTo(ProductBase::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
