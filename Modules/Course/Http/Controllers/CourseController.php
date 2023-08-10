<?php

namespace Modules\Course\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Course\Entities\Course;
use Modules\Course\Transformers\CourseResource;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
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
                        ]

                    ]
                ];
            } else {
                $data = CourseResource::collection($allCourses);

            }
            return ApiResponse::sendResponse(200, 'All Courses retrieved successfully', $data);
        }

        return ApiResponse::sendResponse(200, 'No courses Available', []);
    }
    

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        // insert the photo
        $photoPath = $request->file('photo')->storePublicly('course_photos/photo', 's3');
        //we get the data from the form
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'photo' => "https://online-bucket.s3.amazonaws.com/$photoPath",
            'price' => $request->price,
            'category_id' => $request->category_id,
            'created_at' => now(),
            'updated_at' => now(),
            'slug' => Str::slug($request->title) . '.' . Str::uuid(),
            'teacher_id' => Auth::guard('teacher')->user()->id
        ];
        //insert the data in courses table using query builder
        $course = DB::table('courses')->insert($data);
        if ($course) {
            return ApiResponse::sendResponse(201, 'your courses created successfully', []);
        }
        return ApiResponse::sendResponse(200, 'Failed to create the course', []);

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
