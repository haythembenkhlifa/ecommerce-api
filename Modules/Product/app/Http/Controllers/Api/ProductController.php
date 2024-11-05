<?php

namespace Modules\Product\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Cache;
use Modules\Order\Models\Order;
use Modules\Product\Actions\CreateProduct;
use Modules\Product\Actions\DeleteProduct;
use Modules\Product\Actions\UpdateProduct;
use Modules\Product\Http\Requests\ProductRequest;
use Modules\Product\Models\Product;
use Modules\Product\Transformers\ProductResource;
use Illuminate\Routing\Controllers\Middleware;


class ProductController extends Controller implements HasMiddleware
{
    use AuthorizesRequests;




    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['index', 'show']),
        ];
    }


    public function index()
    {
        return ProductResource::collection(
            Cache::remember(
                'products.' . request('page', 1),
                3600,
                fn() => Product::paginate(
                    config('product.products_per_page', 100)
                )
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $productRequest)
    {
        return new ProductResource((new CreateProduct)($productRequest));
    }

    /**
     * Show the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ProductRequest $productRequest,
        Product $product
    ) {
        return new ProductResource((new UpdateProduct)($productRequest, $product));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        (new DeleteProduct)($product);
    }
}
