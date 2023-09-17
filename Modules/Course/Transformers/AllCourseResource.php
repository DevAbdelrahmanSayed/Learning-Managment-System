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
            'id' => $this->id,
            'Course_Name' => $this->title,
            'Course_Description' => $this->description,
            'teacher_name' => $this->teachers->name,
            'Sections' => SectionResource::collection($this->sections),
        ];
    }
}
