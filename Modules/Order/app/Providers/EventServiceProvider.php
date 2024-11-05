<?php

namespace Modules\Order\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Notifications\Events\NotificationSent;
use Modules\Order\Events\OrderPlacedEvent;
use Modules\Order\Events\ProcessPaymentEvent;
use Modules\Order\Listeners\DeleteMailAttachmentListener;
use Modules\Order\Listeners\ProcessPaymentListener;
use Modules\Product\Listeners\UpdateStockListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array<string, array<int, string>>
     */
    protected $listen = [
        OrderPlacedEvent::class => [
            UpdateStockListener::class,
        ],
        NotificationSent::class => [
            DeleteMailAttachmentListener::class,
        ],
        ProcessPaymentEvent::class => [
            ProcessPaymentListener::class,
        ]

    ];

    /**
     * Indicates if events should be discovered.
     *
     * @var bool
     */
    protected static $shouldDiscoverEvents = true;

    /**
     * Configure the proper event listeners for email verification.
     */
    protected function configureEmailVerification(): void
    {
        //
    }
}
