<?php

namespace Modules\Order\Actions;

use Modules\Order\Models\Order;

class DeleteOrder extends BaseOrderAction
{
    public function __invoke(Order $order)
    {
        $order->delete();
        // TODO: fire an event that order deleted if needed
    }
}
