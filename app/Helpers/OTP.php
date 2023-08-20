<?php

namespace App\Helpers;

class OTP{

    static function generate($user){
        $user->otp = rand(1000, 9999);
        $user->expire_at = now()->addMinutes(15);
        $user->save();
    }

    static function verify($user , $otp){
        
    }

}
