<?php

namespace Modules\Section\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Course\Entities\Course;
use Modules\Section\Actions\CreateSectionAction;
use Modules\Section\Entities\Section;
use Modules\Section\Http\Requests\SectionUpdateRequest;
use Modules\Section\Http\Requests\StoreSectionRequest;
use Modules\Teacher\Transformers\SectionResource;

class SectionController extends Controller
{
    public function index(Course $course)
    {
        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Section retrieved successfully.', SectionResource::collection($course->sections));
    }

    public function store(Course $course, StoreSectionRequest $request, CreateSectionAction $createSectionAction)
    {
        if ($course->teacher_id !== auth()->user()->id) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not allowed to take this action', null);
        }

        $section = $createSectionAction->execute($course, $request->validated());

        return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Section created successfully.', new SectionResource($section));
    }

    public function update(SectionUpdateRequest $request, $sectionId)
    {

        $section = Section::find($sectionId);

        if (! $section) {
            return ApiResponse::sendResponse(200, 'Section not found', []);
        }
        $authenticatedTeacher = Auth::guard('teacher')->user()->id;
        if ($section->teacher_id !== $authenticatedTeacher) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to update this section', []);
        }
        $data = [
            'title' => $request->title,
            'updated_at' => now(),
        ];
        $sectionUpdate = DB::table('sections')->where('id', $sectionId)->update($data);
        if ($sectionUpdate) {
            return ApiResponse::sendResponse(200, 'Section updated successfully', ['Section_id' => $sectionId]);
        }

        return ApiResponse::sendResponse(200, 'Failed to update the section', []);
    }

    public function destroy($sectionId)
    {
        $section = Section::find($sectionId);

        if (! $section) {
            return ApiResponse::sendResponse(200, 'Section not found', []);
        }
        $authenticatedTeacher = Auth::guard('teacher')->user()->id;
        if ($section->teacher_id !== $authenticatedTeacher) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to delete this section', []);
        }
        $section->delete();

        return ApiResponse::sendResponse(200, 'Section deleted successfully', []);
    }
}
