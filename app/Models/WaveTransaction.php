<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaveTransaction extends Model
{
    protected $fillable = [
        'transaction_ref',
        'wave_session_id',
        'user_id',
        'payment_method_id',
        'cart_data',
        'delivery_info',
        'status',
        'order_id',
    ];

    protected $casts = [
        'cart_data' => 'array',
        'delivery_info' => 'array',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
