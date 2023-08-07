<?php

namespace Modules\Teacher\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Teacher\Entities\Teacher;
use Modules\Teacher\Http\Requests\LoginRequest;
use Modules\Teacher\Http\Requests\RegisterRequest;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;


class TeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:teacher', ['except' => ['login', 'register']]);
    }
    public function register(RegisterRequest $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ];
        $token = Auth::guard('teacher');
        // Insert the data
        $teacher = Teacher::create($data);
        $token = JWTAuth::fromUser($teacher);
        $responseData = [
            'token' =>  $token,
            'name' => $teacher->name,
            'email' => $teacher->email,

        ];

        return ApiResponse::sendResponse(201, 'Teacher Account Created Successfully', $responseData);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $token = Auth::guard('teacher')->attempt($credentials);

        if (Auth::guard('teacher')->attempt($credentials)) {
            $currentTeacher = Auth::guard('teacher')->user();
            $responseData = [
                'token' => $token,
                'name' => $currentTeacher->name,
                'email' => $currentTeacher->email,
                'teacherID'=>Auth::guard('teacher')->user()->id
            ];
            return ApiResponse::sendResponse(200, 'Teacher logged in Successfully', $responseData);
        } else {
            return ApiResponse::sendResponse(401, 'Teacher credentials do not exist', []);
        }
    }


    public function logout()
    {
        Auth::guard('teacher')->logout();
        return ApiResponse::sendResponse(200, 'Teacher logged out successfully', []);
    }

    public function refresh()
    {
        return response()->json([
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
