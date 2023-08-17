<?php

namespace Modules\Auth\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Mail\EmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Modules\Teacher\Entities\Teacher;
use Illuminate\Validation\Rules\Password;
use Illuminate\Contracts\Support\Renderable;
use Modules\Auth\Transformers\TeacherResource;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{


    public function __invoke(Request $request)
    {
        $request->validate([
            'type' => ['required', Rule::in(['teacher', 'student'])],
        ]);
        return ($request->type === 'teacher') ? $this->storeTeacher($request) : $this->storeStudent($request);
    }


    public function storeTeacher(Request $request)
    {
        $teacherData = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:25'],
            'email' => ['required', 'email', 'unique:teachers,email'],
            'password' => ['required', 'max:255', Password::defaults()]
        ]);
        $teacherData = Teacher::create($teacherData);
        $this->otpGenerate($teacherData);
        $teacherData['token'] = JWTAuth::fromUser($teacherData);

        Mail::to( $teacherData->email)->send(new EmailVerification($teacherData->otp,$teacherData->name));
        return ApiResponse::sendResponse(201, 'Teacher Account Created Successfully', new TeacherResource($teacherData));
    }


    public function storeStudent(Request $request)
    {
        dd('Register Student');
    }

    public function otpGenerate($modelData)
    {
        $modelData->otp = rand(1000, 9999);
        $modelData->expire_at = now()->addMinutes(15);
        $modelData->save();
    }

    public function destroy($id)
    {
        //! Remove account
    }
}
