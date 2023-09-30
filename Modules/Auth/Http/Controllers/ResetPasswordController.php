<?php

namespace Modules\Auth\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\OTP;
use App\Mail\EmailVerification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Modules\Auth\Services\PasswordResetService;
use Modules\Student\Entities\Student;
use Modules\Teacher\Entities\Teacher;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class ResetPasswordController extends Controller
{

    public function resetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Validation Errors', $validator->errors());
        }

        $user = Teacher::where('email', $request->email)->first() ?? Student::where('email', $request->email)->first();

        if ($user) {
            OTP::generate($user);
            $verificationToken = JWTAuth::fromUser($user);

            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'OTP has been sent to your email.', ['token' => $verificationToken]);
        }

        return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'User not found', []);
    }


    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Validation Errors', $validator->errors());
        }
        $currentUser =Auth::guard('teacher')->check() ? Auth::guard('teacher')->user() : Auth::guard('student')->user();
        if($currentUser){
            $currentUser->update(['password'=>$request->password]);
//            PasswordResetService::resetPassword($currentUser, $request->password);

            return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Password updated Successfully', []);
        }

        return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'User not found', []);
    }
}
