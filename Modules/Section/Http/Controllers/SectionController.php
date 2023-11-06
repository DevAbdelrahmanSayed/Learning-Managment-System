<?php

namespace Modules\Section\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Course\Entities\Course;
use Modules\Section\Actions\CreateSectionAction;
use Modules\Section\Actions\DeleteSectionAction;
use Modules\Section\Actions\GetSectionAction;
use Modules\Section\Actions\UpdateSectionAction;
use Modules\Section\Entities\Section;
use Modules\Section\Http\Requests\IndexSectionRequest;
use Modules\Section\Http\Requests\StoreSectionRequest;
use Modules\Section\Http\Requests\UpdateSectionRequest;
use Modules\Teacher\Transformers\SectionResource;

class SectionController extends Controller
{
    public function index(){}
    public function getSection(Course $course, IndexSectionRequest $request, GetSectionAction $getSectionAction)
    {
        $teacherId = (int)$request->input('teacher_id');
        $user = Auth::guard('teacher')->user();

        if (!$user) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, "You must be logged in as a teacher to access this action");
        }

        if ($request->has('teacher_id') ) {
            if ($teacherId!== $user->getKey())
                return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'You do not have permission to take this action');
        }

        $sections = $teacherId ? $getSectionAction->execute($course->id,['teacher_id' => $request->input('teacher_id')]) : $getSectionAction->execute($course->id);

        if ($sections->isEmpty()) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'No Sections found');
        }


        $data = SectionResource::collection($sections);

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Sections retrieved successfully.', $data);
    }




    public function store(StoreSectionRequest $request, CreateSectionAction $createSectionAction)
    {

        if (!Auth::guard('teacher')->check()) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not have permission to take this action');
        }


        $courseId = $request->input('course_id');
        $course = Course::find($courseId);

        if (!$course) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'Course not found');
        }

        if ($course->teacher_id !== Auth::guard('teacher')->user()->id) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not have permission to take this action');
        }

        $section = $createSectionAction->execute( $courseId, Auth::guard('teacher')->user()->id, $request->validated());

        return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Section created successfully.', ['section_id' => $section->id]);
    }


    public function update(Section $section, UpdateSectionRequest $request, UpdateSectionAction $updateSectionAction)
    {
        $teacherAuth = Auth::guard('teacher')->user()->id;
        if ($section->teacher_id !==$teacherAuth)  {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not have permission to update this section', []);
        }

        $section = $updateSectionAction->execute($section, $request->validated());

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Section updated successfully.', ['section_id'=>$section->id]);
    }

    public function destroy(Course $course, Section $section, DeleteSectionAction $deleteSectionAction)
    {
        if ($section->teacher_id !== Auth::guard('teacher')->user()->id) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not have permission to delete this section');
        }

        $deleteSectionAction->execute($section);

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Section deleted successfully', null);
    }
}
