<?php

namespace Modules\Teacher\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{

    public function toArray($request)
    {
       return [
           'fileId'=>$this->id,
           'fileUrl'=>$this->fileUrl,
           'fileCreatedAt' => $this->created_at,
           'fileUpdatedAt' => $this->updated_at,
       ];
    }
}
