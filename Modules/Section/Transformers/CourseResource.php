<?php

namespace Modules\Section\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'Course Name' => $this->title,
            'Course Description' => $this->description,
            'Sections' => SectionResource::collection($this->sections)
        ];
    }
}
