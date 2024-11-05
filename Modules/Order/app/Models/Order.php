<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Order\Database\Factories\OrderFactory;
use Modules\Order\Models\Scopes\OrderScope;
use Modules\Product\Models\Product;
use Modules\User\Models\User;

class Order extends Model
{
    use HasFactory;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['products'];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'shipping_address_line_1',
        'shipping_address_line_2',
        'shipping_state',
        'shipping_city',
        'shipping_postal_code',
        'billing_address_line_1',
        'billing_address_line_2',
        'billing_state',
        'billing_city',
        'billing_postal_code',
        'note'
    ];

    /**
     * Get the orders's full billing address.
     */
    protected function fullBillingAddress(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->billing_address_line_1 .
                ($this->billing_address_line_1 ? ' ,' . $this->billing_address_line_2 : '') .
                ' ,' . $this->billing_state .
                ' ,' . $this->billing_city .
                ' ,' . $this->billing_postal_code,
        );
    }

    /**
     * Get the orders's full shipping address.
     */
    protected function fullShippingAddress(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->shipping_address_line_1 .
                ($this->shipping_address_line_2 ? ' ,' . $this->shipping_address_line_2 : '') .
                ' ,' . $this->shipping_state .
                ' ,'  . $this->shipping_city .
                ' ,' . $this->shipping_postal_code,
        );
    }

    /**
     * Get the user that owns to the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The products that belongs to the order.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('quantity', 'price')
            ->using(OrderProduct::class)
            ->withTimestamps();
    }

    /**
     * The payment that belongs to the order.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(OrderPayment::class);
    }

    /**
     * The logs that belongs to the order.
     */
    public function logs(): HasMany
    {
        return $this->hasMany(OrderLog::class);
    }

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($order) {
            $order->order_number = str()->uuid();
        });

        self::addGlobalScope(OrderScope::class);
    }
}
