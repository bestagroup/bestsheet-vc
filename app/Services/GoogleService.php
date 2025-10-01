<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;

class GoogleService
{
    public static function getClient($user)
    {
        $client = new Client();
        $client->setApplicationName('Laravel Calendar Integration');
        $client->setScopes(Calendar::CALENDAR);
        $client->setAccessType('offline');

        // دسترسی‌ها از دیتابیس یا session
        $client->setAccessToken($user->google_token);

        // در صورت منقضی شدن refresh شود
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $user->google_token = $client->getAccessToken();
            $user->save();
        }

        return new Calendar($client);
    }
}
