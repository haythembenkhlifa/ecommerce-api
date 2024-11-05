<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\Order\Actions\UpdateOrderAmount;
use Modules\Order\Database\Factories\OrderFactory;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;

class OrderProduct extends Pivot
{
    use HasFactory;

    protected $table = 'order_product';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * Get the order that own the order order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the order that own the order order.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($orderProduct) {
            $orderProduct->price = $orderProduct->product?->price * $orderProduct->quantity;
        });

        self::created(function ($orderProduct) {
            (new UpdateOrderAmount)($orderProduct->order);
        });


        self::updating(function ($orderProduct) {
            $orderProduct->price = $orderProduct->product?->price * $orderProduct->quantity;
        });

        self::updated(function ($orderProduct) {
            (new UpdateOrderAmount)($orderProduct->order);
        });


        self::deleted(function ($orderProduct) {
            $orderProduct->price = $orderProduct->product?->price * $orderProduct->quantity;
            (new UpdateOrderAmount)($orderProduct->order);
        });
    }
}
