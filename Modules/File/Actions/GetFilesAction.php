<?php

namespace Modules\File\Actions;

use Illuminate\Support\Facades\Storage;
use Modules\File\Entities\File;

class GetFilesAction
{
    public function execute($section_id,array $filters=[])
    {
        $files = File::where('section_id',$section_id)->filter($filters)->latest()->get();
        return $files;

    }
}
