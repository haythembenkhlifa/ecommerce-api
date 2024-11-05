<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Order\Models\Order;

/**
 * Simple model to trace all order operations maybe this can be exposed to order_admin and admin users.
 */
class OrderLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'context',
        'content',
    ];

    /**
     * Get the order that own the order order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
