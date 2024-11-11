<?php

namespace App\Models;

use App\Events\OrderSaved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;

class Order extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'bl_release_date',
        'bl_release_user_id',
        'freight_payer_self',
        'contract_number',
        'bl_number',
        'payment_request_sent_at',
    ];

    protected $casts = [
        'bl_release_date' => 'datetime',
        'payment_request_sent_at' => 'datetime',
    ];

    protected $dispatchesEvents = [
        'saved' => OrderSaved::class,
    ];

    /**
     * Route notifications for the mail channel.
     *
     * @return  array<string, string>|string
     */
    public function routeNotificationForMail(Notification $notification): array|string
    {
        return 'mick.burgs@gmail.com';
    }

    public function isSelfPaidFreight(): bool
    {
        return $this->attributes['freight_payer_self'] ?? false;
    }

    public function setPaymentRequestSentAt(): void
    {
        $this->payment_request_sent_at = now();
    }

    public function paymentRequestIsSend(): bool
    {
        return $this->payment_request_sent_at !== null;
    }
}
