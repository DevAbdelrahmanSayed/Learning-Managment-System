<?php

namespace Modules\Teacher\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
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
            'teacherName' => $this->name,
            'teacherEmail' => $this->email,
            'teacherAbout' => $this->about,
            'teacherProfile' => $this->profile,
        ];
    }
}
