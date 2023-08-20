<?php

namespace Modules\Section\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'section_id' => $this->id,
            'section_title' => $this->title,
            'section_description' => $this->description,
            'section_videos' => $this->videos->pluck('videoUrl'),
            'section_Files' => $this->files->pluck('fileUrl'),
        ];

    }
}
