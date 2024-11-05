<?php

namespace Modules\Product\Actions;

use Illuminate\Support\Facades\Log;
use Modules\Product\Http\Requests\ProductRequest;
use Modules\Product\Models\Product;


class UpdateProduct extends BaseProductAction
{
    public function __invoke(ProductRequest $updateProductRequest, Product $product)
    {

        $product->name = $updateProductRequest->input('name', $product->name);
        $product->description = $updateProductRequest->input('description', $product->description);
        $product->price = $updateProductRequest->input('price', $product->price);
        $product->stock = $updateProductRequest->input('stock', $product->stock);
        $product->images = $this->storeProductImages($updateProductRequest);
        $product->save();

        Log::info('Product Updated let`s clear products cache.');

        $this->clearProductsCache();

        // TODO: fire an event that product updated if needed

        return $product;
    }
}
