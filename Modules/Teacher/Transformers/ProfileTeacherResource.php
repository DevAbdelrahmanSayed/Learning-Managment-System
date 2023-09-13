<?php

namespace Modules\Teacher\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileTeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request
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
