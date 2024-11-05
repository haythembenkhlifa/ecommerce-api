<?php

namespace Modules\Order\Actions;

use Modules\Order\Enums\OrderStatus;
use Modules\Order\Enums\PaymentStatus;
use Modules\Order\Events\OrderPlacedEvent;
use Modules\Order\Models\Order;
use Modules\Order\Notifications\OrderConfirmationNotification;

class MarkOrderAsPaid extends BaseOrderAction
{
    public function __invoke(Order $order)
    {
        $order->status = OrderStatus::PAID->value;
        $order->save();

        $order->payment->status = PaymentStatus::PAID->value;

        // let's say that the data returned contains sensitive data, let's encrypt it.
        $order->payment->transaction_payload = ['transaction_id' => '1234567890', 'status' => 'paid', 'amount' => $order->total_amount, 'order_id' =>  $order->id, 'customer' => $order->user->name];
        $order->payment->transaction_id = '1234567890';
        $order->payment->save();

        (new CreateOrderLog)(
            $order,
            'order_paid',
            'Order has been paid successfully [' . $order->payment_method . '].'
        );

        OrderPlacedEvent::dispatch($order);

        $order->user?->notify(new OrderConfirmationNotification($order));
    }
}
