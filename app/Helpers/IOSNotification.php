<?php

namespace App\Helpers;

use Pushok\AuthProvider;
use Pushok\Client;
use Pushok\Notification;
use Pushok\Payload;
use Pushok\Payload\Alert;

class  IOSNotification
{

    public static function send_demo()
    {


        $options = [
            'key_id' => 'KHR5BX2ZZZ', // The Key ID obtained from Apple developer account
            'team_id' => 'H2WQCQJUT7', // The Team ID obtained from Apple developer account
            'app_bundle_id' => 'com.ReDiscoverTV', // The bundle ID for app obtained from Apple developer account
            'private_key_path' => base_path('AuthKey_KHR5BX2ZZZ.p8'), // Path to private key
            'private_key_secret' => 'KHR5BX2ZZZ' // Private key secret
        ];

// Be aware of thing that Token will stale after one hour, so you should generate it again.
// Can be useful when trying to send pushes during long-running tasks
        $authProvider = AuthProvider\Token::create($options);

        $alert = Alert::create()->setTitle('Hello!');
        $alert = $alert->setBody('First push notification');

        $payload = Payload::create()->setAlert($alert);

//set notification sound to default
        $payload->setSound('default');

//add custom value to your notification, needs to be customized
        $payload->setCustomValue('key', 'value');

        $deviceTokens = [
            '3c6bb01a1aaeea69e641db5ce735526a519d056b1410622df7ce17c0d69c19f5',
            '63c7d3bf31b0d632c651cd33dc27eea90276c1c4753f7765628915103f5c713d',
            '625a28382b4878cdf764b0f292731da3306de771af93f64d8d585d6381a2f876',
        ];

        $notifications = [];
        foreach ($deviceTokens as $deviceToken) {
            $notifications[] = new Notification($payload, $deviceToken);
        }

// If you have issues with ssl-verification, you can temporarily disable it. Please see attached note.
// Disable ssl verification
// $client = new Client($authProvider, $production = false, [CURLOPT_SSL_VERIFYPEER=>false] );
        $client = new Client($authProvider, $production = true);
        $client->addNotifications($notifications);


        $responses = $client->push(); // returns an array of ApnsResponseInterface (one Response per Notification)

        foreach ($responses as $response) {
            dump($response);
            // The device token
            $response->getDeviceToken();
            // A canonical UUID that is the unique ID for the notification. E.g. 123e4567-e89b-12d3-a456-4266554400a0
            $response->getApnsId();

            // Status code. E.g. 200 (Success), 410 (The device token is no longer active for the topic.)
            $response->getStatusCode();
            // E.g. The device token is no longer active for the topic.
            $response->getReasonPhrase();
            // E.g. Unregistered
            $response->getErrorReason();
            // E.g. The device token is inactive for the specified topic.
            $response->getErrorDescription();
            $response->get410Timestamp();
        }
    }

    public static function send($deviceTokens, $title, $body, $imageUrl = null, $is_database_token = true)
    {
        $options = [
            'key_id' => 'KHR5BX2ZZZ', // The Key ID obtained from Apple developer account
            'team_id' => 'H2WQCQJUT7', // The Team ID obtained from Apple developer account
            'app_bundle_id' => 'com.ReDiscoverTV', // The bundle ID for app obtained from Apple developer account
            'private_key_path' => base_path('AuthKey_KHR5BX2ZZZ.p8'), // Path to private key
            'private_key_secret' => 'KHR5BX2ZZZ' // Private key secret
        ];

// Be aware of thing that Token will stale after one hour, so you should generate it again.
// Can be useful when trying to send pushes during long-running tasks
        $authProvider = AuthProvider\Token::create($options);

        $alert = Alert::create()->setTitle($title);
        $alert = $alert->setBody($body);

        $payload = Payload::create()->setAlert($alert);

//set notification sound to default
        $payload->setSound('default');

//add custom value to your notification, needs to be customized
        if ($imageUrl) $payload->setCustomValue('imageUrl', $imageUrl);


        $notifications = [];
        foreach ($deviceTokens as $deviceToken) {
            $notifications[] = new Notification($payload, $deviceToken);
        }

// $client = new Client($authProvider, $production = false, [CURLOPT_SSL_VERIFYPEER=>false] );
        $client = new Client($authProvider, $production = true);
        $client->addNotifications($notifications);

        $responses = $client->push(); // returns an array of ApnsResponseInterface (one Response per Notification)

        foreach ($responses as $response) {

            $token = $response->getStatusCode();
            if ($token != 200) {

            }

            // The device token
            $response->getDeviceToken();
            // A canonical UUID that is the unique ID for the notification. E.g. 123e4567-e89b-12d3-a456-4266554400a0
            $response->getApnsId();

            // Status code. E.g. 200 (Success), 410 (The device token is no longer active for the topic.)
            $response->getStatusCode();
            // E.g. The device token is no longer active for the topic.
            $response->getReasonPhrase();
            // E.g. Unregistered
            $response->getErrorReason();
            // E.g. The device token is inactive for the specified topic.
            $response->getErrorDescription();
            $response->get410Timestamp();
        }
    }
}
