<?php

namespace Modules\Product\Actions;

use Illuminate\Support\Facades\Storage;
use Modules\Product\Http\Requests\ProductRequest;
use Modules\Product\Models\Product;

class CreateProduct extends BaseProductAction
{
    public function __invoke(ProductRequest $createProductRequest): Product
    {
        $product = new Product();
        $product->name = $createProductRequest->input('name');
        $product->description = $createProductRequest->input('description');
        $product->price = $createProductRequest->input('price');
        $product->stock = $createProductRequest->input('stock');
        $product->images = $this->storeProductImages($createProductRequest);
        $product->save();


        $this->clearProductsCache();

        // TODO: fire an event that product created if needed

        return $product;
    }
}
