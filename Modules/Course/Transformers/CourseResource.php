<?php

namespace Modules\Course\Transformers;

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
            'id' => $this->id,
            'teacherId'=>$this->teachers->id,
            'teacherName'=>$this->teachers->name,
            'title' => $this->title,
            'description' => $this->description,
            'photo' => $this->photo,
            'created_at' => $this->created_at,
        ];
    }
}
