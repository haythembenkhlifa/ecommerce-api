<?php

namespace Modules\Order\Actions;

use Illuminate\Support\Facades\Storage;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;
use Modules\Order\Models\Order;


class GenerateOrderInvoice extends BaseOrderAction
{
    public function __invoke(Order $order)
    {
        // Create buyer instance.
        $buyer = new Buyer([
            'name'          => $order->user->name,
            'custom_fields' => [
                'email' => $order->user->email,
                'phone' => $order->user->phone_number,
                'shipping address' => $order->fullShippingAddress,
                'billing address' => $order->fullBillingAddress
            ],
        ]);

        // Create a seller instance
        $seller = new Party([
            'name' => env('APP_NAME', 'Haythem'),
            'custom_fields' => [
                'email' => env('MAIL_FROM_ADDRESS', 'sales_person@ecommerce.com'),
            ],
            'phone' => '99999999',
            'vat' => 'Tn001'
        ]);

        // Create items for the invoice
        $items = [];
        foreach ($order->products as $product) {

            $items[] = InvoiceItem::make($product->name)
                ->pricePerUnit($product->price)
                ->quantity($product->pivot->quantity);
        }
        // Create the invoice
        Invoice::make('invoice')
            ->buyer($buyer)
            ->seller($seller)
            ->addItems($items)
            ->currencyFraction('millimes')
            ->currencyCode('dinars')
            ->currencySymbol('TND')
            ->filename('invoice_' . $order->id)
            ->save('invoices');

        (new CreateOrderLog)(
            $order,
            'order_invoice_generated',
            'Order invoice successfully generated.'
        );

        return Storage::disk('invoices')->path('invoice_' . $order->id . '.pdf');
    }
}
