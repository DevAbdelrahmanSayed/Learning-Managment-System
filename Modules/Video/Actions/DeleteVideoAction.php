<?php

namespace Modules\Video\Actions;

use Illuminate\Support\Facades\Storage;
use Modules\Video\Entities\Video;

class DeleteVideoAction
{
    public function execute(Video $video)
    {
        if ($video->videoUrl) {
            $existingVideoPath = basename(parse_url($video->videoUrl, PHP_URL_PATH));
            if (Storage::disk('s3')->exists("course_videos/videos/{$existingVideoPath}")) {
                Storage::disk('s3')->delete("course_videos/videos/{$existingVideoPath}");
            }
        }
        $video->delete();
    }
}
