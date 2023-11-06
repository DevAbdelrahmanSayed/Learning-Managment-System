<?php

namespace Modules\File\Actions;

use Illuminate\Support\Facades\Storage;
use Modules\File\Entities\File;
use Modules\Section\Entities\Section;
class UpdateFileAction
{
    public function execute( File $file , array $fileData)
    {
        if (isset($fileData['fileUrl'])) {
            $filePath = (new StoreCourseFileAction())->execute($fileData['fileUrl']);
            $fileData['fileUrl'] = "https://online-bucket.s3.amazonaws.com/$filePath";
        }
        if ($file->fileUrl) {
            $existingFilePath = basename(parse_url($file->fileUrl, PHP_URL_PATH));
            if (Storage::disk('s3')->exists("course_file/files/{$existingFilePath}")) {
                Storage::disk('s3')->delete("course_file/files/{$existingFilePath}");
            }
        }

        $file->update($fileData);
        return $file;
    }

}
