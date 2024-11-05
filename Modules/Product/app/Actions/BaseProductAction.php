<?php

namespace Modules\Product\Actions;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Modules\Product\Http\Requests\ProductRequest;
use Modules\Product\Models\Product;

class BaseProductAction
{
    /**
     * This function will clear all products in redis cache.
     */
    protected function clearProductsCache()
    {
        Log::info('let`s delete products cache.');
        foreach (Redis::connection('cache')->keys('*products*') as $cacheKey) {
            Log::info("KEY TO DELETE :" . str($cacheKey)->after('laravel_database_')->value());
            Cache::forget(str($cacheKey)->after('laravel_database_')->value());
        }
    }

    /**
     * This function will delete all stored product images.
     */
    protected function storeProductImages(ProductRequest $productRequest)
    {
        $images = [];
        if ($productRequest->hasFile('images')) {
            foreach ($productRequest->file('images') as $image) {
                $uuid = str()->uuid();
                $extension = $image->getClientOriginalExtension();
                $filename = $uuid . '.' . $extension;
                $image->storeAs('products', $filename, config('product.products_image_disk', 'public')); // Store with the UUID name
                $images[] =  $filename;
            }
        }
        return $images;
    }

    /**
     * This function will stored product images.
     */
    protected function deleteProductImages(Product $product)
    {
        foreach ($product->images ?? [] as $image) {
            Storage::disk(config('product.products_image_disk', 'public'))->delete('products/' . $image);
        }
    }
}
