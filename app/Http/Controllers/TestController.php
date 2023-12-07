<?php

namespace App\Http\Controllers;

use App\Helpers\IOSNotification;
use App\Notifications\TestNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\Apn\ApnMessage;

class TestController extends Controller
{
    public function test () {
//        $user = User::query()->limit(1)->get();
//        Notification::send($user, new TestNotification('test'));

        IOSNotification::send();


    }
}
