<?php

namespace Modules\Order\Actions;

use Modules\Order\Models\Order;


class UpdateOrderAmount extends BaseOrderAction
{
    public function __invoke(Order $order)
    {
        $order->total_amount = $order->products->sum('pivot.price');
        $order->save();

        (new CreateOrderLog)(
            $order,
            'order_updated',
            'Order amount has been updated.'
        );

        return $order;
    }
}
