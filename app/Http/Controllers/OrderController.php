<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Notifications\PaymentRequest;

class OrderController extends Controller
{
    public function sendPaymentRequest($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->notify(new PaymentRequest($order));
        return response()->json(['message' => 'Payment request sent!']);
    }
}
