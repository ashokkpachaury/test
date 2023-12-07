<?php

namespace App\Helpers;

use App\Models\NotifyToken;
use App\PushNotification;

class Notify
{
    static public function send(PushNotification $notification)
    {
        Notify::sendToIos($notification);
    }

    static public function sendToIos(PushNotification $notification)
    {
        $iosTokens = NotifyToken::where('device_type', 'ios')->get()->pluck('token');

        IOSNotification::send($iosTokens, $notification->name, $notification->message, ($notification->image ? asset('/upload/' . $notification->image) : null));

    }
}
