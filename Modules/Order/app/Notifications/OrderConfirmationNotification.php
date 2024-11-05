<?php

namespace Modules\Order\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Order\Actions\GenerateOrderInvoice;
use Modules\Order\Models\Order;

class OrderConfirmationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {

        $invoicePath = (new GenerateOrderInvoice)($this->order);

        $message = (new MailMessage)
            ->subject(__('order::messages.order_confirmation_subject', ['id' => $this->order->id]))
            ->greeting(__('order::messages.greeting', ['name' => $this->order->user->name]))
            ->line(__('order::messages.thank_you_order'))
            ->line(__('order::messages.order_details'))
            ->line(__('order::messages.order_id', ['id' => $this->order->id]))
            ->line(__('order::messages.total', ['amount' => number_format($this->order->total_amount, 2)]))
            ->line(__('order::messages.payment_method', ['method' => $this->order->payment_method]))
            ->line(__('order::messages.items'));

        foreach ($this->order->products as $product) {
            $message->line(__('order::messages.quantity', ['quantity' => $product->pivot->quantity, 'product_name' => $product->name]));
        }

        if ($invoicePath) {
            $message->attach($invoicePath, [
                'as' => 'invoice.pdf',
                'mime' => 'application/pdf',
            ]);
        }

        $message->line(__('order::messages.attached_invoice'));

        $message->line(__('order::messages.thank_you_service'));

        return $message;
    }
}
