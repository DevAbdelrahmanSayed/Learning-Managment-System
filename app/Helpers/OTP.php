<?php

namespace App\Helpers;

class OTP
{
    public static function generate($user)
    {
        $user->otp = rand(1000, 9999);
        $user->expire_at = now()->addMinutes(15);
        $user->save();
    }

    public static function verify($user, $otp)
    {

    }
}
