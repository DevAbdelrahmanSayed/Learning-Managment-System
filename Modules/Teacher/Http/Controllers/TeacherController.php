<?php

namespace Modules\Teacher\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Modules\Course\Entities\Course;
use Modules\Section\Entities\Section;
use Modules\Teacher\Actions\GetAllTeachersAction;
use Modules\Teacher\Entities\Teacher;
use Modules\Teacher\Http\Requests\UpdateTeacherRequest;
use Modules\Teacher\Transformers\FileResource;
use Modules\Teacher\Transformers\SectionResource;
use Modules\Teacher\Transformers\TeacherResource;
use Modules\Teacher\Transformers\VideosFilesResource;

class TeacherController extends Controller
{
    public function index(GetAllTeachersAction $getAllTeachersAction)
    {
        $teachers = $getAllTeachersAction->execute();
        if (! $teachers) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'No teachers found');
        }

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Teachers retrieved successfully.', TeacherResource::collection($teachers));
    }

    public function update(UpdateTeacherRequest $request)
    {
        $teacher = Auth::guard('teacher')->user();

        if ($request->has(['password', 'old_password'])) {
            if (! Hash::check($request->old_password, $teacher->password)) {
                return ApiResponse::sendResponse(401, 'The old password does not match.', []);
            }
        }

        if ($request->file('profile')) {
            $profilePath = $request->file('profile')->storePublicly('profile_photos/photo', 's3');
            $existingPhotoPath = basename(parse_url($teacher->profile, PHP_URL_PATH));
            if (Storage::disk('s3')->exists("profile_photos/photo/{$existingPhotoPath}")) {
                Storage::disk('s3')->delete("profile_photos/photo/{$existingPhotoPath}");
            }
        }
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'profile' => "https://online-bucket.s3.amazonaws.com/$profilePath",
            'about' => $request->about,
            'password' => Hash::make($request->password),
            'updated_at' => now(),
        ];
        $teacher->update($data);

        return ApiResponse::sendResponse(200, 'User\'s data updated successfully.', new TeacherResource($teacher));
    }

    public function getSectionCreatedByTeacher($courseId)
    {

        $course = Course::with('sections')->find($courseId);

        if (! $course) {
            return ApiResponse::sendResponse(404, 'Course not found', []);
        }

        if ($course->teacher_id !== Auth::guard('teacher')->user()->getKey()) {
            return ApiResponse::sendResponse(403, 'You do not allowed to take this action.', []);
        }

        return ApiResponse::sendResponse(200, 'Courses retrieved successfully', SectionResource::collection($course->sections));
    }

    public function getVideoCreatedByTeacher($sectionId)
    {
        $section = Section::with('Videos')->find($sectionId);

        if (! $section) {
            return ApiResponse::sendResponse(404, 'Section not found', []);
        }

        if ($section->teacher_id !== Auth::guard('teacher')->user()->getKey()) {
            return ApiResponse::sendResponse(403, 'You do not allowed to take this action.', []);
        }

        return ApiResponse::sendResponse(200, 'Videos retrieved successfully', VideosFilesResource::collection($section->Videos));
    }

    public function getFilesCreatedByTeacher($sectionId)
    {
        $section = Section::with('files')->find($sectionId);

        if (! $section) {
            return ApiResponse::sendResponse(404, 'Section not found', []);
        }

        $user = auth()->user();

        if ($section->teacher_id !== Auth::guard('teacher')->user()->getKey()) {
            return ApiResponse::sendResponse(403, 'You do not allowed to take this action.', []);
        }

        return ApiResponse::sendResponse(200, 'files retrieved successfully', FileResource::collection($section->files));
    }

    public function destroy()
    {
        $teacher = Auth::guard('teacher')->user()->getKey();
        $user = Teacher::find($teacher);

        if (! $user) {
            return ApiResponse::sendResponse(200, 'User not found', []);
        }

        $user->delete();
        Auth::guard('teacher')->logout();

        return ApiResponse::sendResponse(200, 'User deleted successfully .', []);

    }
}
