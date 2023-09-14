<?php

namespace Modules\Course\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherCourseResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'courseId' => $this->id,
            'courseName' => $this->title,
            'courseDescription' => $this->description,
            'coursePhoto' => $this->photo,

        ];

    }
}
