<?php

namespace Modules\Auth\Transformers;

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
            'Teacher ID' => $this->id,
            'Name' => $this->name,
            'Email' => $this->email,
            'Token' => $this->token,
        ];
    }
}
