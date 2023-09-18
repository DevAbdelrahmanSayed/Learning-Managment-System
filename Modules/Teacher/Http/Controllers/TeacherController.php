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
use Modules\Teacher\Actions\DeleteTeacherAction;
use Modules\Teacher\Actions\GetAllTeachersAction;
use Modules\Teacher\Actions\UpdateTeachersAction;
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

    public function update( Teacher $teacher ,UpdateTeacherRequest $request , UpdateTeachersAction $updateTeachersAction)
    {

        if ($request->has(['password', 'old_password'])) {
            if (! Hash::check($request->old_password, $teacher->password)) {
                return ApiResponse::sendResponse(401, 'The old password does not match.');
            }
        }
        $updatedTeacher = $updateTeachersAction->execute($teacher,$request->validated());

        return ApiResponse::sendResponse(200, 'User\'s data updated successfully.', new TeacherResource( $updatedTeacher));
    }


    public function destroy(Teacher $teacher , DeleteTeacherAction $deleteTeacherAction)
    {

         $deleteTeacherAction->execute($teacher);
        return ApiResponse::sendResponse(200, 'User deleted successfully .', []);

    }
}
