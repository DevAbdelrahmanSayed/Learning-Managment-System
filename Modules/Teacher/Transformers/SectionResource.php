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
            'sectionCreatedAt' => $this->created_at,
            'sectionUpdatedAt' => $this->updated_at,
        ];
    }
}
