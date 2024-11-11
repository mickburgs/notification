<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Notifications\PaymentRequest;

// Endpoints used for manual testing, should be removed before going to production
class OrderController extends Controller
{
    public function sendPaymentRequest($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->notify(new PaymentRequest($order));
        return response()->json(['message' => 'Payment request sent!']);
    }

    public function markOrderForPaymentRequestNotification($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->payment_request_sent_at = null;
        $order->freight_payer_self = false;
        $order->save();
        return response()->json(['message' => 'Order is saved!']);
    }
}
