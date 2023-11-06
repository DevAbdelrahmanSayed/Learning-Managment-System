<?php

namespace Modules\Course\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Section\Transformers\SectionResource;

class AllCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'course_id' => $this->id,
            'course_name' => $this->title,
            'course_description' => $this->description,
            'teacher_name' => $this->teachers->name,
            'course_photo' => $this->photo,
            'course_price' => $this->price,

        ];
    }
}
