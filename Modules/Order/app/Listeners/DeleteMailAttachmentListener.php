<?php

namespace Modules\Order\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\Order\Actions\CreateOrderLog;

class DeleteMailAttachmentListener implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(NotificationSent $event): void
    {
        // Let's make sure to delete the generate invoice to keep our storage clean :).
        $orderInvoiceFileName = 'invoice_' . $event->notification?->order?->id . '.pdf';
        if (Storage::disk('invoices')->exists($orderInvoiceFileName)) {
            Storage::disk('invoices')->delete($orderInvoiceFileName);
            (new CreateOrderLog)(
                $event?->notification?->order,
                'order_invoice_deleted',
                'Order invoice has been deleted successfully.'
            );
        }
    }
}
