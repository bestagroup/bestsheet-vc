<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes(['https://www.googleapis.com/auth/calendar'])
            ->with([
                'access_type' => 'offline',
                'prompt'      => 'consent',
            ])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'          => $googleUser->getName(),
                'password'      => Hash::make('123456789'),
                'level'           => 'applicant',
                'status'          => 4,
                'role_id'         => 5,
                'change_password' => 1,
            ]
        );

        Auth::login($user);

        $user->update([
            'google_token'         => $googleUser->token,
            'google_refresh_token' => $googleUser->refreshToken,
        ]);

        return redirect('/');
    }
}
