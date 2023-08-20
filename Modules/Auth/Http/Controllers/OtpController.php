<?php

namespace Modules\Auth\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\OTP;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function verify(Request $request)
    {
        $otpData = $request->validate([
            'otp' => ['required', 'integer'],
        ]);

        if (OTP::verify(Auth::guard('teacher')->user(), $otpData['otp'])) {
            return ApiResponse::sendResponse(200, 'Your account has been verified successfully.', ['is_verified' => true]);
        } else {
            return ApiResponse::sendResponse(200, 'There is an error in OTP', null);
        }
    }

    public function resendOtp()
    {
        $user = Auth::guard('teacher')->user();
        if ($user) {
            OTP::generate($user); // Resend OTP

            return ApiResponse::sendResponse(200, 'A new OTP has been sent to your email.', []);
        }

        return ApiResponse::sendResponse(400, 'User not found', []);
    }
}
