<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Order\Models\Order;

class OrderPayment extends Model
{
    use HasFactory;

    protected $casts = [
        'transaction_payload' => 'encrypted:array',
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'amount',
        'status',
        'payment_method',
    ];

    /**
     * Get the order that own the order order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
