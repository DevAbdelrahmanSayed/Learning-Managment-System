<?php

namespace Modules\Video\Actions;

use Illuminate\Http\UploadedFile;

class StoreCourseVideoAction
{
    public function execute(UploadedFile $video)
    {
        $path = $video->storePublicly('course_videos/videos', 's3');
        return $path;
    }

}
