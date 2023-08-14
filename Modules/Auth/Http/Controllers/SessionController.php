<?php

namespace Modules\Auth\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Transformers\TeacherResource;

class SessionController extends Controller
{


    public function __invoke(LoginRequest $request)
    {
        return ($request->type === 'teacher') ? $this->storeTeacherSession($request) : $this->storeTeacherSession($request);
    }

    public function storeStudentSession(Request $request)
    {
        dd('Student login');
    }

    public function storeTeacherSession(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token = Auth::guard('teacher')->attempt($credentials);

        if ($token) {
            $currentTeacher = Auth::guard('teacher')->user();
            $currentTeacher['token'] = $token;

            return ApiResponse::sendResponse(200, 'Teacher logged in Successfully', new TeacherResource($currentTeacher));
        } else {
            return ApiResponse::sendResponse(401, 'Teacher credentials do not work', []);
        }

    }


    public function destroy($id)
    {
        //
    }
}
