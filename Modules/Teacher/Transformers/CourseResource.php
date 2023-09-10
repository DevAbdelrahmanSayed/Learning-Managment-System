<?php

namespace Modules\Teacher\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Section\Transformers\SectionResource;

class CourseResource extends JsonResource
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
