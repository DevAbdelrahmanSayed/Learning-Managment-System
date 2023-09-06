<?php

namespace Modules\Teacher\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Transformers\TeacherResource;
use Modules\Course\Entities\Course;
use Modules\Teacher\Entities\Teacher;
use Modules\Teacher\Http\Requests\UpdateTeacherRequest;
use Modules\Teacher\Transformers\CourseResource;
use Modules\Teacher\Transformers\SectionResource;

class TeacherController extends Controller
{

    public function update(UpdateTeacherRequest $request)
    {
        if ($request->has(['password', 'old_password']))
            if (!Hash::check($request->old_password, auth()->user()->password)) {
                return ApiResponse::sendResponse(401, 'The old password does not match .', null);
            }

        Auth::user()->update(
            $request->validated() + ['password' => Hash::make($request->password)]
        );

        return ApiResponse::sendResponse(200, 'User\'s data updated successfully .', new TeacherResource(Auth::user()));
    }
    public function getCoursesCreatedByTeacher($teacherId)
    {
        $teacher = Teacher::with('courses')->find($teacherId);

        if (!$teacher) {
            return ApiResponse::sendResponse(404, 'Teacher not found', null);
        }

        $user = auth()->user();

        if ($teacher->id !== $user->id) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to access this course', []);
        }

        // Return the courses associated with the teacher
        return ApiResponse::sendResponse(200, 'Courses retrieved successfully',  CourseResource::collection($teacher->courses));
    }
    public function getSectionCreatedByTeacher($courseId)
    {

        $course = Course::with('sections')->find($courseId);

        if (!$course) {
            return ApiResponse::sendResponse(404, 'Course not found', null);
        }

        $user = auth()->user();

        if ($course->teacher_id !== $user->id) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to access this course', []);
        }

        return ApiResponse::sendResponse(200, 'Courses retrieved successfully',  SectionResource::collection($course->sections));
    }



    public function destroy($id)
    {
        $user = Teacher::find($id);

        if ($id != Auth::user()->id) {
            return ApiResponse::sendResponse(403, 'You do not allowed to take this action. ', null);
        }

        if (!$user) {
            return ApiResponse::sendResponse(200, 'User not found', null);
        }

        $user->delete();
        Auth::guard('teacher')->logout();

        return ApiResponse::sendResponse(200, 'User deleted successfully .', null);

    }
}
