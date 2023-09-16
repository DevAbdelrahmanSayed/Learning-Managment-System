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
            'sectionId' => $this->id,
            'sectionTitle' => $this->title,
            'sectionCreatedAt' => date_format($this->created_at, 'Y-m-d'),
            'sectionUpdatedAt' => date_format($this->updated_at, 'Y-m-d'),
        ];
    }
}
