<?php

namespace Modules\Order\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Transformers\ProductResource;
use Modules\User\Transformers\UserResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'uuid' => $this->order_number,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'shipping_address_line_1' => $this->shipping_address_line_1,
            'shipping_address_line_2' => $this->shipping_address_line_2,
            'shipping_city' => $this->shipping_city,
            'shipping_state' => $this->shipping_state,
            'shipping_postal_code' => $this->shipping_postal_code,
            'billing_address_line_1' => $this->billing_address_line_1,
            'billing_address_line_2' => $this->billing_address_line_2,
            'billing_city' => $this->billing_city,
            'billing_state' => $this->billing_state,
            'billing_postal_code' => $this->billing_postal_code,
            'total_amount' => $this->total_amount,
            'products' => OrderProductResource::collection($this->products),
            // 'logs' => $this->logs // this can be exposed to admin and order-manager just to have an idea about order life history.
        ];
    }
}
