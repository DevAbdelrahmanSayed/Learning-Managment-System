<?php
namespace  Modules\Course\Actions;
use App\Helpers\ApiResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Course\Entities\Course;
use Modules\Section\Transformers\CourseResource;

class IndexCourseAction
{
    public function execute(LengthAwarePaginator $allCourses)
    {
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
            return ['status' => 'success', 'message' => 'All Courses retrieved successfully','data'=>$data];

        }
        return ['status' => 'error', 'message' => 'No courses Available'];

    }

}

