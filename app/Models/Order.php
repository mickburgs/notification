<?php

namespace App\Models;

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
    ];

    protected $casts = [
        'bl_release_date' => 'datetime',  // Casts the bl_release_date to a Carbon instance
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
}
