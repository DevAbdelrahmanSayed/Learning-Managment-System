<?php

namespace Modules\Course\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Course\Actions\DestroyCourseAction;
use Modules\Course\Actions\GetCoursesWithPaginationAction;
use Modules\Course\Actions\StoreCourseAction;
use Modules\Course\Actions\UpdateCourseAction;
use Modules\Course\Http\Requests\CourseRequest;
use Modules\Course\Http\Requests\IndexCourseRequest;
use Modules\Section\Transformers\CourseResource;
use Symfony\Component\HttpFoundation\JsonResponse;

class CourseController extends Controller
{
    public function index(IndexCourseRequest $request, GetCoursesWithPaginationAction $getCoursesWithPaginationAction)
    {
        $courses = $getCoursesWithPaginationAction->execute(request(['teacher_id']));
        $data = array_merge(CourseResource::collection($courses)->toArray(request()), $courses->pagination ?? []);

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Courses retrived successfully.', $data);
    }

    public function store(CourseRequest $request, StoreCourseAction $StoreCourseAction)
    {
        $action = $StoreCourseAction->execute($request, Auth::guard('teacher')->user());

        return ($action['status'] === 'success')
            ? ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, $action['message'], $action['data'])
            : ApiResponse::sendResponse(JsonResponse::HTTP_OK, $action['message']);
    }

    public function show($teacherId)
    {
    }

    public function update(CourseRequest $request, $courseId, UpdateCourseAction $updateCourseAction)
    {
        $action = $updateCourseAction->execute(Auth::guard('teacher')->user(), $courseId, $request);

        return ApiResponse::sendResponse($action['status'], $action['message'], $action['data']);
    }

    public function destroy($courseId, DestroyCourseAction $destroyCourseAction)
    {

        $action = $destroyCourseAction->execute(Auth::guard('teacher')->user(), $courseId);

        return ApiResponse::sendResponse($action['status'], $action['message'], $action['data']);
    }
}
