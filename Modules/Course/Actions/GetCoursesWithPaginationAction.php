<?php

namespace Modules\Course\Actions;

use Modules\Course\Entities\Course;
use Modules\Section\Transformers\CourseResource;

class GetCoursesWithPaginationAction
{
    public function execute(array $filters = [])
    {
        $courses = Course::filter($filters)->with('teachers')->latest()->paginate(2);
        $courses = $this->paginate($courses);

        return $courses;
    }

    private function paginate($data)
    {

        if (count($data) > 0) {
            if ($data->total() > $data->perPage()) {
                $data = [
                    'records' => CourseResource::collection($data),
                    'pagination' => [
                        'currentPage' => $data->currentPage(),
                        'perPage' => $data->perPage(),
                        'total' => $data->total(),
                        'links' => [
                            'first' => $data->url(1),
                            'last' => $data->url($data->lastPage()),
                            'prev' => $data->previousPageUrl(),
                            'next' => $data->nextPageUrl(),
                        ],
                    ],

                ];
            } else {
                $data = CourseResource::collection($data);
            }

            return $data;
        }
    }
}
