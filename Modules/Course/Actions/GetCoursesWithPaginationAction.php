<?php

namespace Modules\Course\Actions;

use Modules\Course\Entities\Course;

class GetCoursesWithPaginationAction
{
    public function execute(array $filters = [])
    {
        $courses = Course::filter($filters)->with('teachers')->latest()->paginate(1000);

        $courses = $this->paginate($courses);

        return $courses;
    }

    private function paginate($data)
    {

        if (count($data) > 0) {
            if ($data->total() > $data->perPage()) {
                $data->pagination = [
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
            }

            return $data;
        }
    }
}
