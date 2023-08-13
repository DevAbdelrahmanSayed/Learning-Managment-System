<?php

namespace Modules\Section\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Course\Entities\Course;
use Modules\Section\Entities\Section;
use Modules\Section\Http\Requests\SectionRequest;
use Modules\Section\Http\Requests\SectionUpdateRequest;
use Modules\Section\Transformers\CourseResource;

class SectionController extends Controller
{
    public function store(SectionRequest $request)
    {
        $course = Course::find($request->course_id);

        if ($course->teacher_id !== auth()->user()->id) {
            return ApiResponse::sendResponse(403 , 'Unauthorized: You do not allowed to take this action' , null);
        }

        $insertedSection = DB::table('sections')->insert([
            'title' => $request->title,
            'description' => $request->description,
            'course_id' => $request->course_id,
            'teacher_id' => auth()->user()->id,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        if ($insertedSection) {
            return ApiResponse::sendResponse(201, 'Section created successfully', []);
        }
        return ApiResponse::sendResponse(200, 'Failed to create the section', []);

    }

    public function show($courseId)
    {
        $course = Course::where('id', $courseId)->with('sections.Videos', 'sections.files', 'teachers')->first();

        if (!$course) {
            return ApiResponse::sendResponse(200, 'Course not found', null);
        }

        if ($course->teacher_id !== auth()->user()->id) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to access this course', []);
        }

        return ApiResponse::sendResponse(200, 'Data retrieved successfully. ', new CourseResource($course));
    }


    public function update(SectionUpdateRequest $request, $sectionId)
    {

        $section = Section::find($sectionId);

        if (!$section) {
            return ApiResponse::sendResponse(200, 'Section not found', []);
        }
        $authenticatedTeacher = Auth::guard('teacher')->user()->id;
        if ($section->teacher_id !== $authenticatedTeacher) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to update this section', []);
        }
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'updated_at' => now(),
        ];
        $sectionUpdate = DB::table('sections')->where('id', $sectionId)->update($data);
        if ($sectionUpdate) {
            return ApiResponse::sendResponse(200, 'Section updated successfully', []);
        }

        return ApiResponse::sendResponse(200, 'Failed to update the section', []);
    }


    public function destroy($sectionId)
    {
        $section = Section::find($sectionId);

        if (!$section) {
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
