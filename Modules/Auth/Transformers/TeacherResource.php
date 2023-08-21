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
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
        isset($this->token) ? $data['token'] = $this->token : '';
        isset($this->email_verified_at) ? $data['verification'] = $this->email_verified_at : '';

        return $data;
    }
}
