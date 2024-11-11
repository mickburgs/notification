<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentRequest extends Notification implements ShouldQueue
{
    use Queueable;

    private $order;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\Order $order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // No need to add ‘freight_payer_self’ since this mail is only being sent if this is true
        // I haven't made any effort to style it further because I assume this is handled within the platform
        return (new MailMessage())
            ->subject('Payment Request for Order ' . $this->order->id)
            ->greeting('Hello!')
            ->line('You have a payment request for the following Order:')
            ->line('Order ID: ' . $this->order->id)
            ->line('Contract Number: ' . $this->order->contract_number)
            ->line('Bill of Lading Number: ' . $this->order->bl_number)
            ->line('Release Date: ' . $this->order->bl_release_date->toDateTimeString())
            ->line('Released by User ID: ' . $this->order->bl_release_user_id)
            ->from('mick.burgs@gmail.com', 'Notification App');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
