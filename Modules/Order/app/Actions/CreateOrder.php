<?php

namespace Modules\Order\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Modules\Order\Enums\PaymentMethods;
use Modules\Order\Events\OrderPlacedEvent;
use Modules\Order\Http\Requests\OrderRequest;
use Modules\Order\Models\Order;
use Modules\Order\Notifications\OrderConfirmationNotification;

class CreateOrder extends BaseOrderAction
{
    public function __invoke(OrderRequest $orderRequest): Order
    {
        $order = new Order();
        $order->user_id = $orderRequest->user()?->id ?? $orderRequest->user_id;
        $order->status = $orderRequest->input('payment_method', PaymentMethods::COD->value) === PaymentMethods::CARD->value ? 'pending' : 'confirmed';
        $order->payment_method = $orderRequest->input('payment_method');
        $order->shipping_address_line_1 = $orderRequest->input('shipping_address_line_1');
        $order->shipping_address_line_2 = $orderRequest->input('shipping_address_line_2');
        $order->shipping_city = $orderRequest->input('shipping_city');
        $order->shipping_state = $orderRequest->input('shipping_state');
        $order->shipping_postal_code = $orderRequest->input('shipping_postal_code');
        $order->billing_address_line_1 = $orderRequest->input('billing_address_line_1', $order->shipping_address_line_1);
        $order->billing_address_line_2 = $orderRequest->input('billing_address_line_2', $order->shipping_address_line_2);
        $order->billing_city = $orderRequest->input('billing_city', $order->shipping_city);
        $order->billing_state = $orderRequest->input('billing_state', $order->shipping_state);
        $order->billing_postal_code = $orderRequest->input('billing_postal_code', $order->shipping_postal_code);
        $order->save();

        $order->refresh();

        (new CreateOrderLog)(
            $order,
            'order_created',
            'Order has been created successfully with payment method [' . $order->payment_method . '].'
        );

        $this->createOrderProducts($orderRequest, $order);

        $this->createOrderPayment($order);

        if ($order->payment_method === PaymentMethods::COD->value) {
            OrderPlacedEvent::dispatch($order);
            $order->user?->notify(new OrderConfirmationNotification($order));
        } else {
            // Here the payment made using e-card i will call the end point 
            // that the payment gateway should call when payment has been processed and they receive the amount.
            dispatch(fn() => Http::post(route('api.payment', ['order' => $order->order_number])));
        }

        $order->refresh();

        return $order;
    }

    private function createOrderProducts(OrderRequest $orderRequest, $order)
    {
        foreach ($orderRequest->input('products', []) as $productOrder) {
            $product_id = Arr::get($productOrder, 'product_id', null);
            $quantity  = Arr::get($productOrder, 'quantity', 0);
            $order->products()->attach($product_id, ['quantity' => $quantity]);
            (new CreateOrderLog)(
                $order,
                'adding_order_product',
                "Order product with id [$product_id] added [$quantity] time(s)."
            );
        }
    }

    private function createOrderPayment(Order $order): void
    {
        $order->payment()->create([
            'amount' => $order->total_amount,
            'payment_method' => $order->payment_method,
            'status' => $order->payment_method === PaymentMethods::CARD->value ? 'pending' : 'paid',
        ]);
        (new CreateOrderLog)(
            $order,
            'payment_created',
            'Payment of [' . $order->total_amount . '] with [' . $order->payment_method . '] payment method has been created successfully.'
        );
    }
}
