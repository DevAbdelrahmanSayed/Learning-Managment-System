<?php

namespace Modules\Section\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Course\Entities\Course;
use Modules\Section\Http\Requests\SectionRequest;
use Modules\Section\Transformers\CourseResource;

class SectionController extends Controller
{

    public function index()
    {
        //
    }


    public function store(SectionRequest $request)
    {
        $course = Course::find($request->course_id);
        if (!$course) {
            return ApiResponse::sendResponse(200, 'courseID not found', []);
        }

        $authenticatedTeacher = Auth::guard('teacher')->user()->id;
        if ($course->teacher_id !== $authenticatedTeacher) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to access this section', []);
        }

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'course_id' => $request->course_id,
            'teacher_id' => $authenticatedTeacher,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        $sectionInsert = DB::table('sections')->insert($data);
        if ($sectionInsert) {
            return ApiResponse::sendResponse(201, 'Section created successfully', []);
        }
        return ApiResponse::sendResponse(200, 'Failed to create the section', []);

    }


    public function show($courseId)
    {
        $course = Course::where('id', $courseId)->with('sections.videos', 'sections.files', 'teachers')->get();

        if (!$course) {
            return ApiResponse::sendResponse(200, 'Course not found', []);
        }
        $authenticatedTeacherId = Auth::guard('teacher')->id();

        if ($course->teacher_id !== $authenticatedTeacherId) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to access this course', []);
        }

        return ApiResponse::sendResponse(200, 'Sections and videos and files for the course retrieved successfully', CourseResource::collection($course));
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
