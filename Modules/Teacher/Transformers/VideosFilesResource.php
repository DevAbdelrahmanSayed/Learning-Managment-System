<?php

namespace Modules\Teacher\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\File\Entities\File;

class VideosFilesResource extends JsonResource
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
            'videoId'=>$this->id,
            'videoTitle'=>$this->title,
            'videoUrl'=>$this->videoUrl,
            'videoVisible'=>$this->visible,
            'videoCreatedAt' => $this->vcreated_at,
            'videoUpdatedAt' => $this->updated_at,


        ];
    }
}
