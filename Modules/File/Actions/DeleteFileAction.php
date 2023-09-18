<?php

namespace Modules\File\Actions;

use Illuminate\Support\Facades\Storage;
use Modules\File\Entities\File;

class DeleteFileAction
{
    public function execute(File $file)
    {

        if ($file->fileUrl) {
            $existingFilePath = basename(parse_url($file->fileUrl, PHP_URL_PATH));
            if (Storage::disk('s3')->exists("course_file/files/{$existingFilePath}")) {
                Storage::disk('s3')->delete("course_file/files/{$existingFilePath}");
            }
        }

        $file->delete();
    }
}
