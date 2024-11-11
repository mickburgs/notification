<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Notifications\PaymentRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class OrderPaymentRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_payment_request_notification_when_order_is_saved()
    {
        Notification::fake();
        $order = Order::factory()->create([
            'freight_payer_self' => false,
            'payment_request_sent_at' => null,
        ]);

        $order->save();

        Notification::assertSentTo(
            [$order],
            PaymentRequest::class
        );
        $this->assertNotNull($order->payment_request_sent_at);
        $this->assertGreaterThan(now()->subMinutes(1), $order->payment_request_sent_at);
    }

    public function test_do_not_send_notification_if_not_freight_payer_self()
    {
        Notification::fake();
        $order = Order::factory()->create([
            'freight_payer_self' => true,
            'payment_request_sent_at' => null,
        ]);

        $order->save();

        Notification::assertNotSentTo(
            [$order], PaymentRequest::class
        );
    }

    public function test_do_not_send_notification_if_payment_request_sent_at_is_filled()
    {
        Notification::fake();
        $order = Order::factory()->create([
            'freight_payer_self' => false,
            'payment_request_sent_at' => now(),
        ]);

        $order->save();

        Notification::assertNotSentTo(
            [$order], PaymentRequest::class
        );
    }
}
