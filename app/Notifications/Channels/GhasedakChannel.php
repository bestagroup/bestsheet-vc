<?php


namespace App\Notifications\Channels;


use Ghasedak\Exceptions\ApiException;
use Ghasedak\Exceptions\HttpException;
use Ghasedak\GhasedakApi;
use Illuminate\Notifications\Notification;

class GhasedakChannel
{
    public function send($notifiable , Notification $notification)
    {
        if(! method_exists($notification , 'toGhasedakSms')) {
            throw new \Exception('toGhasedakSms not found');
        }

        $data = $notification->toGhasedakSms($notifiable);

        $message    = $data['text'];
        $receptor   = $data['phone'];
        $code       = $data['code'];
        $lineNumber = "10008642";
        $apiKey        = 'ilvYYKKVEXlM+BAmel+hepqt8fliIow1g0Br06rP4ko';

        try
        {
            $api = new GhasedakApi($apiKey);
            $api->Verify($receptor,1,'dadvarzanuser',$code);

            //$api->SendSimple($receptor,$message,$lineNumber);
        }
        catch(ApiException $e){
            throw $e;
        }
    }
}
