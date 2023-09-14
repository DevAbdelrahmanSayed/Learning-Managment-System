<?php

namespace Modules\Course\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Course\Actions\DestroyCourseAction;
use Modules\Course\Actions\GetCoursesCreatedByTeacherAction;
use Modules\Course\Actions\IndexCourseAction;
use Modules\Course\Actions\StoreCourseAction;
use Modules\Course\Actions\UpdateCourseAction;
use Modules\Course\Entities\Course;
use Modules\Course\Http\Requests\CourseRequest;
use Symfony\Component\HttpFoundation\JsonResponse;

class CourseController extends Controller
{
    public function index(IndexCourseAction $IndexCourseAction)
    {
        $allCourses = Course::with('teachers')->latest()->paginate(2);

        $action = $IndexCourseAction->execute($allCourses);

        return ($action['status'] === 'success')
            ? ApiResponse::sendResponse(JsonResponse::HTTP_OK, $action['message'],$action['data'])
            : ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, $action['message']);
    }
    public function store(CourseRequest $request, StoreCourseAction $StoreCourseAction)
    {
        $action = $StoreCourseAction->execute($request,Auth::guard('teacher')->user());

        return ($action['status'] === 'success')
            ? ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, $action['message'],$action['data'])
            : ApiResponse::sendResponse(JsonResponse::HTTP_OK, $action['message']);
    }
    public function show($teacherId){}
    public function getCoursesCreatedByTeacher(GetCoursesCreatedByTeacherAction $getCourses)
    {
        $action = $getCourses->execute(Auth::guard('teacher')->user());
        return ApiResponse::sendResponse($action['status'], $action['message'], $action['data']);
    }
    public function update(CourseRequest $request, $courseId,UpdateCourseAction $updateCourseAction)
    {
        $action = $updateCourseAction->execute(Auth::guard('teacher')->user(),$courseId,$request);
        return ApiResponse::sendResponse($action['status'], $action['message'], $action['data']);
    }

    public function destroy($courseId,DestroyCourseAction $destroyCourseAction)
    {

        $action = $destroyCourseAction->execute(Auth::guard('teacher')->user(),$courseId);
        return ApiResponse::sendResponse($action['status'], $action['message'], $action['data']);
    }
}
