<?php

namespace Modules\Order\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Order\Actions\CreateOrderLog;
use Modules\Order\Actions\MarkOrderAsPaid;
use Modules\Order\Events\ProcessPaymentEvent;

class ProcessPaymentListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(ProcessPaymentEvent $event): void
    {
        (new MarkOrderAsPaid)($event->order);
    }
}
