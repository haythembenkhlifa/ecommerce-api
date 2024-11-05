<?php

namespace Modules\Order\Actions;

use Modules\Order\Models\Order;
use Modules\Order\Models\OrderLog;

class CreateOrderLog extends BaseOrderAction
{
    public function __invoke(Order $order, string $context, string $content)
    {
        return OrderLog::create([
            'order_id' => $order->id,
            'user_id' => request()?->user()?->id ?? $order->user_id,
            'context' => $context,
            'content' => $content
        ]);
    }
}
