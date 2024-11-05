<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderProduct;
use Modules\Product\Database\Factories\ProductFactory;

class Product extends Model
{
    use HasFactory;

    protected $casts = [
        'images' => 'array'
    ];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'images'
    ];

    /**
     * Get the product's images urls.
     */
    protected function imagesUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => array_map(fn($imageName) => Storage::disk(config('product.products_image_disk', 'public'))->url('products/' . $imageName), $this->images ?? []),
        );
    }

    /**
     * The orders that belong to the product.
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class)
            ->using(OrderProduct::class)
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }
}
