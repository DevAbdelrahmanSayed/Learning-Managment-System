<?php

namespace Modules\Teacher\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{

    public function toArray($request)
    {
       return [

           'fileUrl'=>$this->fileUrl,
           'created_at' => $this->created_at,
           'updated_at' => $this->updated_at,
       ];
    }
}
