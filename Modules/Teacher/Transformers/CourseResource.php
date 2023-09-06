<?php

namespace Modules\Teacher\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Section\Transformers\SectionResource;

class CourseResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'Course_Name' => $this->title,
            'Course_Description' => $this->description,
            'Course_photo' => $this->photo,

        ];

    }
}
