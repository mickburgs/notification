<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Notifications\PaymentRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\SendQueuedNotifications;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class OrderPaymentRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_payment_request_notification_when_order_is_saved()
    {
        Queue::fake();

        $order = Order::factory()->create([
            'freight_payer_self' => false,
            'payment_request_sent_at' => null,
        ]);

        $order->save();

        Queue::assertPushed(SendQueuedNotifications::class, function ($job) use ($order) {
            return $job->notification instanceof PaymentRequest && $job->notification->getOrder()->is($order);
        });
        $this->assertNotNull($order->payment_request_sent_at);
        $this->assertGreaterThan(now()->subMinutes(1), $order->payment_request_sent_at);
    }

    public function test_do_not_send_notification_if_not_freight_payer_self()
    {
        Queue::fake();
        $order = Order::factory()->create([
            'freight_payer_self' => true,
            'payment_request_sent_at' => null,
        ]);

        $order->save();

        Queue::assertNothingPushed();
    }

    public function test_do_not_send_notification_if_payment_request_sent_at_is_filled()
    {
        Queue::fake();
        $order = Order::factory()->create([
            'freight_payer_self' => false,
            'payment_request_sent_at' => now(),
        ]);

        $order->save();

        Queue::assertNothingPushed();
    }
}
