<?php

namespace Modules\Auth\Services;

class PasswordResetService
{
    public static function resetPassword ($user , $password)
    {
        $user->password =  bcrypt($password);
        $user->save();
    }

}
