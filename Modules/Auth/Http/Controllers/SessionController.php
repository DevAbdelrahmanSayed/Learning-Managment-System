<?php

namespace Modules\Auth\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Transformers\TeacherResource;
use Modules\Student\Transformers\StudentREsource;

class SessionController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        return ($request->type === 'teacher') ? $this->storeTeacherSession($request) : $this->storeStudentSession($request);
    }

    public function storeTeacherSession(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $tokenIfCredentialsWorks = Auth::guard('teacher')->attempt($credentials);

        if ($tokenIfCredentialsWorks) {
            $currentTeacher = Auth::guard('teacher')->user();
            $currentTeacher['token'] = $tokenIfCredentialsWorks;

            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Teacher logged in Successfully', new TeacherResource($currentTeacher));
        } else {
            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Teacher credentials do not work', []);
        }

    }

    public function storeStudentSession(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $tokenIfCredentialsWorks = Auth::guard('student')->attempt($credentials);
        if ($tokenIfCredentialsWorks) {
            $currentStudent = Auth::guard('student')->user();
            $currentStudent['token'] = $tokenIfCredentialsWorks;
            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Student logged in Successfully', new StudentREsource($currentStudent));
        } else {
            return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Student credentials do not work', []);
        }
    }

    public function destroy()
    {
        if (Route::is('teacher/logout')) {
            Auth::guard('teacher')->logout();
        } elseif (Route::is('student/logout')) {
            Auth::guard('student')->logout();
        }

        return ApiResponse::sendResponse(200, 'Student logged out successfully', []);
    }
}
