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

        // Check on payment_request_sent_at probably not the best choice if an Order can get updated from multiple places
        // This might cause the PaymentRequest to be sent multiple times
        // TODO Cleanup and try to use isSelfPaidFreight
        if ($order->freight_payer_self === false && !$order->payment_request_sent_at) {
            $order->notify(new PaymentRequest($order));
            $order->payment_request_sent_at = now();
            $order->save();
        }
    }
}
