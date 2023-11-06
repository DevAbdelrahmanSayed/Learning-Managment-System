<?php

namespace Modules\Video\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
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
            'video_id' => $this->id,
            'teacher_id' =>$this->sections->teacher_id,
            'video_url' => $this->videoUrl,
            'video_title' => $this->title,
            'video_visible' => $this->visible,
            'video_createdat' => $this->created_at,
            'video_updatedat' => $this->updated_at,
        ];
    }
}
