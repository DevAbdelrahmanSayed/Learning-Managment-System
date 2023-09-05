<?php

namespace Modules\Course\Http\Controllers;

use App\Helpers\ApiResponse;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Modules\Course\Entities\Course;
use Modules\Course\Http\Requests\CourseRequest;
use Modules\Section\Transformers\CourseResource;

class CourseController extends Controller
{
    public function index()
    {
        $allCourses = Course::with('teachers')->latest()->paginate(2);
        if (count($allCourses) > 0) {
            if ($allCourses->total() > $allCourses->perPage()) {
                $data = [
                    'records' => CourseResource::collection($allCourses),
                    'pagination' => [
                        'currentPage' => $allCourses->currentPage(),
                        'perPage' => $allCourses->perPage(),
                        'total' => $allCourses->total(),
                        'links' => [
                            'first' => $allCourses->url(1),
                            'last' => $allCourses->url($allCourses->lastPage()),
                            'prev' => $allCourses->previousPageUrl(),
                            'next' => $allCourses->nextPageUrl(),
                        ],
                    ],
                ];
            } else {
                $data = CourseResource::collection($allCourses);
            }

            return ApiResponse::sendResponse(200, 'All Courses retrieved successfully', $data);
        }

        return ApiResponse::sendResponse(200, 'No courses Available', []);
    }

    public function store(CourseRequest $request)
    {

        $photoPath = $request->file('photo')->storePublicly('course_photos/photo', 's3');
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'photo' => "https://online-bucket.s3.amazonaws.com/$photoPath",
            'price' => $request->price,
            'category_id' => $request->category_id,
            'created_at' => now(),
            'slug' => Str::slug($request->title).'.'.Str::uuid(),
            'teacher_id' => Auth::guard('teacher')->user()->id,
        ];
        $course = DB::table('courses')->insertGetId($data);
        if ($course) {
            return ApiResponse::sendResponse(201, 'your courses created successfully', ['Course_id'=>$course]);
        }

        return ApiResponse::sendResponse(200, 'Failed to create the course', []);

    }

    public function show(CourseRequest $request)
    {
    }

    public function update(CourseRequest $request, $courseId)
    {
        $course = DB::table('courses')->find($courseId);
        if (! $course) {
            return ApiResponse::sendResponse(404, 'Course not found', []);
        }
        $authenticatedTeacherId = Auth::guard('teacher')->id();
        if ($course->teacher_id !== $authenticatedTeacherId) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to update this section', []);
        }
        $photoPath = $request->file('photo')->storePublicly('course_photos/photo', 's3');

        if ($course->photo) {
            $existingPhotoPath = basename(parse_url($course->photo, PHP_URL_PATH));
            if (Storage::disk('s3')->exists("course_photos/photo/{$existingPhotoPath}")) {
                Storage::disk('s3')->delete("course_photos/photo/{$existingPhotoPath}");
            }
        }

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'photo' => "https://online-bucket.s3.amazonaws.com/$photoPath",
            'price' => $request->price,
            'category_id' => $request->category_id,
            'updated_at' => now(),
        ];

        $course = DB::table('courses')->where('id', $courseId)->update($data);
        if ($course) {
            return ApiResponse::sendResponse(200, 'course updated successfully', ['Course_id'=>$courseId]);
        }

        return ApiResponse::sendResponse(200, 'Course updated successfully', []);
    }

    public function destroy(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id', // Ensure 'course_id' exists in the 'courses' table
        ]);
        if ($validator->fails()) {
            return ApiResponse::sendResponse(400, 'Validation failed', $validator->errors());
        }
        $courseId = $request->input('course_id');

        $course = DB::table('courses')->find($courseId);

        if (! $course) {
            return ApiResponse::sendResponse(200, 'course not found', []);
        }
        $authenticatedTeacher = Auth::guard('teacher')->user()->id;
        if ($course->teacher_id !== $authenticatedTeacher) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to delete this course', []);
        }
        if ($course->photo) {
            $existingPhotoPath = basename(parse_url($course->photo, PHP_URL_PATH));
            if (Storage::disk('s3')->exists("course_photos/photo/{$existingPhotoPath}")) {
                Storage::disk('s3')->delete("course_photos/photo/{$existingPhotoPath}");
            }
        }
        DB::table('courses')->where('id', $courseId)->delete();

        return ApiResponse::sendResponse(200, 'course deleted successfully', []);

    }
}
