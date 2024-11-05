<?php

namespace Modules\Order\Actions;

use Illuminate\Support\Arr;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderProduct;

class UpdateOrder extends BaseOrderAction
{
    public function __invoke(OrderRequest $orderRequest, Order $order)
    {

        $order->shipping_address_line_1 = $orderRequest->input('shipping_address_line_1', $order->shipping_address_line_1);
        $order->shipping_address_line_2 = $orderRequest->input('shipping_address_line_2', $order->shipping_address_line_2);
        $order->shipping_city = $orderRequest->input('shipping_city', $order->shipping_city);
        $order->shipping_state = $orderRequest->input('shipping_state', $order->shipping_state);
        $order->shipping_postal_code = $orderRequest->input('shipping_postal_code', $order->shipping_postal_code);
        $order->billing_address_line_1 = $orderRequest->input('billing_address_line_1', $order->billing_address_line_1);
        $order->billing_address_line_2 = $orderRequest->input('billing_address_line_2', $order->billing_address_line_2);
        $order->billing_city = $orderRequest->input('billing_city', $order->billing_city);
        $order->billing_state = $orderRequest->input('billing_state', $order->billing_state);
        $order->billing_postal_code = $orderRequest->input('billing_postal_code', $order->billing_postal_code);
        $order->save();

        (new CreateOrderLog)(
            $order,
            'order_updated',
            'Order details has been updated.'
        );

        $this->updateOrderProduct($orderRequest, $order);

        // TODO: fire an event that order updated if needed

        $order->refresh();

        return $order;
    }

    private function updateOrderProduct(OrderRequest $orderRequest, $order)
    {
        $newOrderProducts = $orderRequest->input('products', []);
        if (empty($newOrderProducts)) return;

        $order->products()->detach();

        foreach ($orderRequest->input('products', []) as $productOrder) {

            $product_id = Arr::get($productOrder, 'product_id', 'null');
            $quantity  = Arr::get($productOrder, 'quantity', 'null');

            OrderProduct::create([
                'product_id' => $product_id,
                'order_id' => $order->id,
                'quantity' => $quantity,
            ]);

            // TODO: Fire an event to adjust stock when order is confirmed.

            (new CreateOrderLog)(
                $order,
                'update_order_product',
                "Order product with id [$product_id]x[$quantity] has been updated."
            );
        }
    }
}
