<?php

namespace Modules\Product\Listeners;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Actions\CreateOrderLog;
use Modules\Order\Events\OrderPlacedEvent;
use Throwable;

class UpdateStockListener implements ShouldQueue
{

    /**
     * Connection for queue
     * @var string
     */
    public string $connection = 'rabbitmq';

    /**
     * Handle the event.
     */
    public function handle(OrderPlacedEvent $event): void
    {
        $event?->order?->products?->each(fn($p) => $p->decrement('stock', $p->pivot->quantity));

        (new CreateOrderLog)(
            $event?->order,
            'order_product_stock_updated',
            'Order products has been updated successfully.'
        );
    }
}
