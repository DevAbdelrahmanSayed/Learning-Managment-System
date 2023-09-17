<?php

namespace Modules\Section\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Course\Entities\Course;
use Modules\Section\Actions\CreateSectionAction;
use Modules\Section\Actions\DeleteSectionAction;
use Modules\Section\Actions\UpdateSectionAction;
use Modules\Section\Entities\Section;
use Modules\Section\Http\Requests\StoreSectionRequest;
use Modules\Section\Http\Requests\UpdateSectionRequest;
use Modules\Teacher\Transformers\SectionResource;

class SectionController extends Controller
{
    public function index(Course $course)
    {
        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Section retrieved successfully.', SectionResource::collection($course->sections));
    }

    public function store(Course $course, StoreSectionRequest $request, CreateSectionAction $createSectionAction)
    {
        if ($course->teacher_id !== Auth::guard('teacher')->user()->id) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not allowed to take this action', null);
        }

        $section = $createSectionAction->execute($course, $request->validated());

        return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Section created successfully.', new SectionResource($section));
    }

    public function update(Course $course, Section $section, UpdateSectionRequest $request, UpdateSectionAction $updateSectionAction)
    {

        if ($section->teacher_id !== Auth::guard('teacher')->user()->id) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not have permission to update this section', []);
        }

        $section = $updateSectionAction->execute($section, $request->validated());

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Section updated successfully.', new SectionResource($section));
    }

    public function destroy(Course $course, Section $section, DeleteSectionAction $deleteSectionAction)
    {
        if ($section->teacher_id !== Auth::guard('teacher')->user()->id) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not have permission to delete this section');
        }

        $deleteSectionAction->execute($section);

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Section deleted successfully',null);
    }
}
