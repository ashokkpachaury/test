<?php

namespace App\Notifications;


use NotificationChannels\Apn\ApnChannel;
use NotificationChannels\Apn\ApnMessage;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    public $token = '';
    function __construct($token) {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return [ApnChannel::class];
    }

//    public function routeNotificationForApn()
//    {
//        return $this->token;
//    }


    public function toApn($notifiable)
    {
        return ApnMessage::create()
            ->badge(1)
            ->title('Account approved')
            ->body("Your {$notifiable->service} account was approved!");
    }
}
