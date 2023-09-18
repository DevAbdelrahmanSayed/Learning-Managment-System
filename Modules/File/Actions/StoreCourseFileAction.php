<?php

namespace Modules\File\Actions;

use Illuminate\Http\UploadedFile;

class StoreCourseFileAction
{
    public function execute(UploadedFile $file)
    {

        return $file->storePublicly('course_videos/videos', 's3');

    }

}
