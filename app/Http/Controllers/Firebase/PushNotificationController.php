<?php

namespace App\Http\Controllers\Firebase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Http\HttpClientOptions;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class PushNotificationController extends Controller
{

    protected $messaging;

    public function __construct()
    {

        $options = HttpClientOptions::default()
            ->withGuzzleConfigOptions([
                'verify' => false,
            ]);

        $httpLogger = new Logger('firebase_http_logs');
        $httpLogger->pushHandler(new StreamHandler(storage_path('logs/firebase.log'), Logger::INFO));

        $firebaseCredentialsPath = config('fcm.credentials_file');

        $facory = (new Factory)
            ->withHttpLogger($httpLogger)
            ->withHttpClientOptions($options)
            ->withServiceAccount($firebaseCredentialsPath);

        $this->messaging = $facory->createMessaging();
    }

    public function send(array $data)
    {

        try {
            $message = CloudMessage::new ()
                ->withNotification(['title' => $data['topic'], 'body' => $data['body']])
                ->withDefaultSounds()
                // ->toToken($data['token']);

            // $message = CloudMessage::new () ->withNotification(Notification::create($data['topic'], $data['body']))
                // ->withData(['customKey' => 'customValue']) // Optional custom data
                ->withTarget('token', $data['token']);

            $this->messaging->send($message);
        } catch (MessagingException $e) {
            return $e->getMessage();
            // print_r($e->errors());
        }
    }


}
