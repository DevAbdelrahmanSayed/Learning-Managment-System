<?php

namespace Modules\Teacher\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Transformers\TeacherResource;
use Modules\Course\Entities\Course;
use Modules\Section\Entities\Section;
use Modules\Teacher\Entities\Teacher;
use Modules\Teacher\Http\Requests\UpdateTeacherRequest;
use Modules\Teacher\Transformers\CourseResource;
use Modules\Teacher\Transformers\FileResource;
use Modules\Teacher\Transformers\SectionResource;
use Modules\Teacher\Transformers\VideosFilesResource;
use Modules\Video\Entities\Video;

class TeacherController extends Controller
{

    public function update(UpdateTeacherRequest $request)
    {
        if ($request->has(['password', 'old_password']))
            if (!Hash::check($request->old_password, auth()->user()->password)) {
                return ApiResponse::sendResponse(401, 'The old password does not match .', []);
            }

        Auth::user()->update(
            $request->validated() + ['password' => Hash::make($request->password)]
        );

        return ApiResponse::sendResponse(200, 'User\'s data updated successfully .', new TeacherResource(Auth::user()));
    }
    public function getCoursesCreatedByTeacher()
    {
        $user = auth()->user()->id;
        $teacher = Teacher::with('courses')->find($user);

        if (!$teacher) {
            return ApiResponse::sendResponse(404, 'Teacher not found', []);
        }



        if ($teacher->id !== $user) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to access this course', []);
        }

        // Return the courses associated with the teacher
        return ApiResponse::sendResponse(200, 'Courses retrieved successfully',  CourseResource::collection($teacher->courses));
    }
    public function getSectionCreatedByTeacher($courseId)
    {

        $course = Course::with('sections')->find($courseId);

        if (!$course) {
            return ApiResponse::sendResponse(404, 'Course not found', []);
        }

        $user = auth()->user();

        if ($course->teacher_id !== $user->id) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to access this course', []);
        }

        return ApiResponse::sendResponse(200, 'Courses retrieved successfully',  SectionResource::collection($course->sections));
    }
    public function getVideoCreatedByTeacher($sectionId)
    {
        $section = Section::with('Videos')->find($sectionId);

        if (!$section) {
            return ApiResponse::sendResponse(404, 'Section not found', []);
        }

        $user = auth()->user();

        if ($section->teacher_id !== $user->id) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to access this course', []);
        }



        return ApiResponse::sendResponse(200, 'Videos retrieved successfully',  VideosFilesResource::collection($section->Videos));
    }
    public function getFilesCreatedByTeacher($sectionId)
    {
        $section = Section::with('files')->find($sectionId);

        if (!$section) {
            return ApiResponse::sendResponse(404, 'Section not found', []);
        }

        $user = auth()->user();

        if ($section->teacher_id !== $user->id) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to access this course', []);
        }



        return ApiResponse::sendResponse(200, 'files retrieved successfully',  FileResource::collection($section->files));
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
