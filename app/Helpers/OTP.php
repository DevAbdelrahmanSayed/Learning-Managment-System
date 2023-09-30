<?php

namespace App\Helpers;

use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Mail;

class OTP
{
    public static function generate($user)
    {
        $otp = $user->otp()->create([
            'code' => rand(1000, 9999),
            'expire_at' => now()->addMinutes(15),
        ]);

        Mail::to($user->email)->send(new EmailVerification($otp->code, $user->name));
    }


    public static function verify($user, $otp)
    {
        $userOtp = $user->otp()->first();

        if ($userOtp && now()->lt($userOtp->expire_at) && $otp == $userOtp->code) {
            $user->email_verified_at = now();
            $user->save(); // Save the user to update email_verified_at
            $userOtp->update(['code' => null]);
            return true;
        }

        return false;
    }


}
