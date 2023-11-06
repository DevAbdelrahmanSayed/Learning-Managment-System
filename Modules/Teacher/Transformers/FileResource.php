<?php

namespace Modules\Teacher\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'file_id' => $this->id,
            'file_url' => $this->fileUrl,
            'file_createdat' => $this->created_at,
            'file_updatedat' => $this->updated_at,
        ];
    }
}
