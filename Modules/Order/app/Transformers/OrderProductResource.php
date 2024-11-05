<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Transformers\ProductResource;
use Modules\User\Transformers\UserResource;

class OrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {

        return [
            'name' => $this->name,
            'product_id' => $this->id,
            'quantity' => $this->pivot->quantity,
            'price' => $this->pivot->price,
            'price_per_unit' => $this->price,

        ];
    }
}
