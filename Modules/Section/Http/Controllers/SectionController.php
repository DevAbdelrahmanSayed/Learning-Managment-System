<?php

namespace Modules\Section\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Course\Entities\Course;
use Modules\Section\Http\Requests\SectionRequest;
use Modules\Section\Transformers\SectionResource;

class SectionController extends Controller
{
    // #--- create a new section ---#
    public function create(SectionRequest $request, $courseId)
    {
        $course = Course::find($courseId);
        if (!$course) {
            return ApiResponse::sendResponse(200, 'courseID not found', []);
        }

        $authenticatedTeacher = Auth::guard('teacher')->user()->id;
        if ($course->teacher_id !== $authenticatedTeacher || empty($authenticatedTeacher)) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to access this section', []);
        }

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'course_id' => $courseId,
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


    // Import the Auth facade at the top of your file.

    public function allCourse($courseId)
    {
        // Find the course by its ID
        $course = Course::find($courseId);

        // Check if the course exists
        if (!$course) {
            return ApiResponse::sendResponse(404, 'Course not found', []);
        }

        // Get the authenticated teacher's ID
        $authenticatedTeacherId = Auth::guard('teacher')->id();

        // Check if the authenticated teacher's ID matches the teacher ID associated with the course
        if ($course->teacher_id !== $authenticatedTeacherId) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to access this course', []);
        }

        // Get the course with relation one to many (sections and videos)
        $courseWithSectionsAndVideos = Course::where('id', $courseId)->with('sections.videos', 'teachers')->first();


        // Check if the course exists and belongs to the authenticated teacher
        if (!$courseWithSectionsAndVideos) {
            return ApiResponse::sendResponse(404, 'Course not found', []);
        }

        return ApiResponse::sendResponse(200, 'Sections and videos for the course retrieved successfully', SectionResource::collection($courseWithSectionsAndVideos));
    }


}
