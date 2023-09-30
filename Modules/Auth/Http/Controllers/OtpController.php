<?php

namespace Modules\Auth\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\OTP;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function verify(Request $request)
    {
        $otpData = $request->validate([
            'code' => ['required', 'integer'],
        ]);
        $currentUser =Auth::guard('teacher')->check() ? Auth::guard('teacher')->user() : Auth::guard('student')->user();

        if (OTP::verify($currentUser, $otpData['code'])) {

            return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Your account has been verified successfully.', ['is_verified' => true]);
        } else {
            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'There is an error in OTP', null);
        }
    }

    public function resendOtp()
    {
        $currentUser =Auth::guard('teacher')->check() ? Auth::guard('teacher')->user() : Auth::guard('student')->user();
        if (!$currentUser) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'User not found', null);
        }

        $currentUser->otp()->delete();
        OTP::generate($currentUser);

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'A new OTP has been sent to your email.', null);
    }
}
