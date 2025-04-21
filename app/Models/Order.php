<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'pickup_date' => 'datetime: l, d F Y H:i:s',
        'estimated_date' => 'datetime: l, d F Y',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function orderDetail(): HasOne {
        return $this->hasOne(OrderDetail::class, 'order_id', 'id');
    }
}
