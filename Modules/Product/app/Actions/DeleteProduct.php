<?php

namespace Modules\Product\Actions;

use Modules\Product\Models\Product;

class DeleteProduct extends BaseProductAction
{
    public function __invoke(Product $product)
    {
        $this->deleteProductImages($product);
        $product->delete();
        $this->clearProductsCache();

        // TODO: fire an event that product deleted if needed
    }
}
