<?php

namespace Modules\User\Tests\Unit;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Modules\Order\Models\Order;
use Modules\Order\Notifications\OrderConfirmationNotification;
use Modules\Product\Models\Product;
use Modules\User\Models\User;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    /**
     * Notification sent when order is confirmed.
     */
    public function test_notification_sent_when_order_confirmed(): void
    {
        Passport::$hashesClientSecrets = false;

        $this->artisan(
            'passport:client',
            ['--name' => config('app.name'), '--personal' => null]
        )->assertSuccessful();

        Notification::fake();

        $customerUser = User::where('email', 'customer@eshop.com')->first();

        Product::factory(1)->create();

        $payload1 = [
            "payment_method" => "cod",
            "shipping_address_line_1" => "123 ave habib bourguiba",
            "shipping_city" => "Tunis",
            "shipping_state" => "Kairouan",
            "shipping_postal_code" => "9227",
            "products" => [
                [
                    "product_id" => 1,
                    "quantity" => 1
                ]
            ]
        ];

        Passport::actingAs($customerUser);
        $this->postJson('/api/v1/orders', $payload1)->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            "shipping_address_line_1" => "123 ave habib bourguiba",
        ]);

        Notification::assertSentTo(
            $customerUser,
            OrderConfirmationNotification::class,
            function ($notification, $channels) {
                /**
                 *   Test email channel
                 */
                if (in_array('mail', $channels)) {
                    $order = Order::first();
                    $bookingPaidEmail = (object)$notification->toMail($order);
                    $this->assertEquals(
                        str(Arr::get($bookingPaidEmail->attachments, '0.file', ''))->afterLast('/')->value(),
                        "invoice_1.pdf"
                    );
                    Storage::disk('invoices')->delete('invoice_1.pdf');
                }
                return true;
            }
        );
    }
}
