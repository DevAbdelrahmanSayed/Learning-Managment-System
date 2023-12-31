<?php

namespace Modules\Auth\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Helpers\OTP;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Modules\Auth\Transformers\TeacherResource;
use Modules\Student\Entities\Student;
use Modules\Student\Transformers\StudentREsource;
use Modules\Teacher\Entities\Teacher;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required', Rule::in(['teacher','student'])],
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Validation Errors', $validator->errors());
        }

        return ($request->type === 'teacher') ? $this->storeTeacher($request) : $this->storeStudent($request);
    }

    public function storeTeacher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:3', 'max:25'],
            'email' => ['required', 'email', 'unique:teachers,email'],
            'password' => ['required', 'max:255', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Validation Errors', $validator->errors());
        }

        $teacherData = Teacher::create($validator->validated());
        OTP::generate($teacherData);

        $teacherData['token'] = JWTAuth::fromUser($teacherData);

        return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Teacher Account Created Successfully', new TeacherResource($teacherData));
    }

    public function storeStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'min:3', 'max:25'],
            'email' => ['required', 'email', 'unique:teachers,email'],
            'password' => ['required', 'max:255', Password::defaults()],
        ]);

        if ($validator->fails()) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNPROCESSABLE_ENTITY, 'Validation Errors', $validator->errors());
        }

        $studentData = Student::create($validator->validated());
              OTP::generate($studentData);

        $studentData['token'] = JWTAuth::fromUser($studentData);

        return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Student Account Created Successfully', new StudentREsource($studentData));
    }

    public function destroy($id)
    {
        //! Remove account
    }
}
