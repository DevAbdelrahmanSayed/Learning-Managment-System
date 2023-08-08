<?php

namespace Modules\Section\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        if ($this->relationLoaded('videos')) {
            $videosUrls = $this->videos->pluck('videoUrl')->toArray();
        } else {
            $videosUrls = [];
        }


        return [
            'Section Id' => $this->id,
            'Title' => $this->title,
            'Desctiption' => $this->description,
            'Videos Urls' => $videosUrls
        ];
    }
}
