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
            'videoTitle'=>$this->title,
            'videoUrl'=>$this->videoUrl,
            'videoVisible'=>$this->visible,
            'created_at' => $this->vcreated_at,
            'updated_at' => $this->updated_at,


        ];
    }
}
