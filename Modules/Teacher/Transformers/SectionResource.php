<?php

namespace Modules\Teacher\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
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
            'section_id' => $this->id,
            'section_title' => $this->title,
            'section_createdat' => date_format($this->created_at, 'Y-m-d'),
            'section_updatedat' => date_format($this->updated_at, 'Y-m-d'),
        ];
    }
}
