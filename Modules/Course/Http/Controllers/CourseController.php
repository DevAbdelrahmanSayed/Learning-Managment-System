<?php

namespace Modules\Course\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Course\Actions\DeleteCourseAction;
use Modules\Course\Actions\GetCoursesWithPaginationAction;
use Modules\Course\Actions\StoreCourseAction;
use Modules\Course\Actions\UpdateCourseAction;
use Modules\Course\Entities\Course;
use Modules\Course\Http\Requests\IndexCourseRequest;
use Modules\Course\Http\Requests\StoreCourseRequest;
use Modules\Course\Http\Requests\UpdatecourseRequest;
use Modules\Course\Transformers\AllCourseResource;
use Illuminate\Http\JsonResponse;

class CourseController extends Controller
{
    public function index(IndexCourseRequest $request, GetCoursesWithPaginationAction $getCoursesWithPaginationAction)
    {
        $teacherId = (int)$request->input('teacher_id');
        $user = Auth::guard('teacher')->user();
        if (!$user) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, "You must be logged in as a teacher to access this action");
        }
        if ($request->has('teacher_id') ) {
        if ($teacherId !== $user->getKey())
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'You do not have permission to take this action');
        }

        $courses = $teacherId ? $getCoursesWithPaginationAction->execute(['teacher_id' => $teacherId]) : $getCoursesWithPaginationAction->execute([]);

        if ($courses->isEmpty()) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'No courses found');
        }

        $data = array_merge(AllCourseResource::collection($courses)->toArray(request()), $courses->pagination ?? []);

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Courses retrieved successfully.', $data);
    }



    public function store(StoreCourseRequest $request, StoreCourseAction $StoreCourseAction)
    {
        $teacher = Auth::guard('teacher')->user();

        if (!$teacher) {
            // Handle the case where the user is not authenticated as a teacher
            return ApiResponse::sendResponse(JsonResponse::HTTP_UNAUTHORIZED, 'User is not authenticated as a teacher.');
        }
        $course = $StoreCourseAction->execute($request->validated(), $teacher);

        return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Course created successfully. ',['course_id'=>$course->id]);
    }

    public function show($teacherId)
    {
    }

    public function update(Course $course, UpdatecourseRequest $request, UpdateCourseAction $updateCourseAction)
    {
        if ($course->teacher_id !== Auth::guard('teacher')->user()->getKey()) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'You do not have permission to take this action');
        }

        $course = $updateCourseAction->execute($course, $request->validated());

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Course Updated successfully.', ['course_id'=>$course->id]);
    }

    public function destroy(Course $course, DeleteCourseAction $deleteCourseAction)
    {
        if ($course->teacher_id !== Auth::guard('teacher')->user()->getKey()) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'You do not have permission to take this action');
        }

        $deleteCourseAction->execute($course);

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Course deleted successfully.');
    }
}
