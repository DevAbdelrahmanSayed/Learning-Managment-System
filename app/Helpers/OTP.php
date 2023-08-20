<?php

namespace App\Helpers;

use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Mail;

class OTP
{
    public static function generate($user)
    {
        $user->otp = rand(1000, 9999);
        $user->expire_at = now()->addMinutes(15);
        $user->save();

        Mail::to($user->email)->send(new EmailVerification($user->otp, $user->name));
    }

    public static function verify($user, $otp)
    {
        if (now()->lt($user->expire_at) && $otp == $user->otp) {
            $user->email_verified_at = now();
            $user->otp = null;
            $user->save();

            return true;
        } else {
            return false;
        }

    }
}
