<?php

namespace App\Listeners;

use App\Events\OrderSaved;
use App\Notifications\PaymentRequest;

class SendPaymentRequestNotification
{
    /**
     * Handle the event.
     */
    public function handle(OrderSaved $event): void
    {
        $order = $event->getOrder();

        // Check on payment_request_sent_at probably not the best choice
        // If an Order can get updated from multiple places
        // This might cause the PaymentRequest to be sent multiple times
        if ($order->isSelfPaidFreight() || $order->paymentRequestIsSend()) {
            return;
        }

        $order->notify(new PaymentRequest($order));
        $order->setPaymentRequestSentAt();
        $order->save();
    }
}
