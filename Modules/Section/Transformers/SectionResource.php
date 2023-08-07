<?php

namespace Modules\Section\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $videosUrls = [];
        foreach($this->videos as $video)
            $videosUrls[] = $video->videoUrl;

        return [
            'Section Id' => $this->id,
            'Title' => $this->title,
            'Desctiption' => $this->description,
            'Videos Urls' => $videosUrls
        ];
    }
}
