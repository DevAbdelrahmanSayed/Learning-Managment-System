<?php

namespace Modules\Auth\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Transformers\TeacherResource;

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

            return ApiResponse::sendResponse(200, 'Teacher logged in Successfully', new TeacherResource($currentTeacher));
        } else {
            return ApiResponse::sendResponse(401, 'Teacher credentials do not work', []);
        }

    }

    public function storeStudentSession(Request $request)
    {
        dd('Student login');
    }

    public function destroy()
    {
        if (Route::is('teacher/logout')) {
            Auth::guard('teacher')->logout();
        } elseif (Route::is('student/logout')) {
            Auth::guard('student')->logout();
        }

        return ApiResponse::sendResponse(200, 'User logged out successfully', []);
    }
}