<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Notifications\PaymentRequest;
use Illuminate\Notifications\Messages\MailMessage;
use PHPUnit\Framework\TestCase;

class PaymentRequestTest extends TestCase
{
    public function test_mail_message_content()
    {
        $order = \Mockery::mock(Order::class);
        $order->shouldReceive('getAttribute')->with('id')->andReturn(123);
        $order->shouldReceive('getAttribute')->with('contract_number')->andReturn('CONTRACT123');
        $order->shouldReceive('getAttribute')->with('bl_number')->andReturn('BL123');
        $order->shouldReceive('getAttribute')->with('bl_release_date')->andReturn(now());
        $order->shouldReceive('getAttribute')->with('bl_release_user_id')->andReturn(1);

        $notification = new PaymentRequest($order);
        $mailMessage = $notification->toMail($order);

        $this->assertInstanceOf(MailMessage::class, $mailMessage);
        $this->assertStringContainsString('Payment Request for Order 123', $mailMessage->subject);
        $this->assertStringContainsString('Hello!', $mailMessage->greeting);
        $this->assertStringContainsString('You have a payment request for the following Order:', $mailMessage->introLines[0]);
        $this->assertStringContainsString('Order ID: 123', $mailMessage->introLines[1]);
        $this->assertStringContainsString('Contract Number: CONTRACT123', $mailMessage->introLines[2]);
        $this->assertStringContainsString('Bill of Lading Number: BL123', $mailMessage->introLines[3]);
        $this->assertStringContainsString(now()->toDateTimeString(), $mailMessage->introLines[4]);
        $this->assertStringContainsString('Released by User ID: 1', $mailMessage->introLines[5]);
        $this->assertStringContainsString('mick.burgs@gmail.com', $mailMessage->from[0]);
    }
}
