<?php

namespace Modules\Auth\Http\Controllers;

use App\Helpers\OTP;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Mail\EmailVerification;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;


class OtpController extends Controller
{
    public function verify(Request $request)
    {
        $otpData = $request->validate([
            'otp' => ['required', 'integer'],
        ]);

        $user = Auth::guard('teacher')->user();

        if ($request->otp == $user->otp) {
            if (now()->lt($user->expire_at)) {
                $user->email_verified_at = now();
                $user->otp = null;
                $user->save();
                $token = JWTAuth::fromUser($user);
                $responseData = [
                    'token' => $token,
                ];

                return ApiResponse::sendResponse(200, 'OTP has verified Successfully', $responseData);
            } else {
                return ApiResponse::sendResponse(200, 'OTP has expired', []);
            }
        }

        return ApiResponse::sendResponse(400, 'Invalid OTP ', []);
    }
    public function resendOtp()
    {
        $user = Auth::guard('teacher')->user();
        if ($user) {
            OTP::generate($user); // Resend OTP

            Mail::to($user->email)->send(new EmailVerification($user->otp, $user->name));
            return ApiResponse::sendResponse(200, 'A new OTP has been sent to your email.', []);
        }
        return ApiResponse::sendResponse(400, 'User not found', []);
    }


}
