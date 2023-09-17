<?php

namespace Modules\Video\Actions;

use Illuminate\Support\Facades\Storage;
use Modules\Section\Entities\Section;
use Modules\Video\Entities\Video;

class UpdateVideoAction
{
    public function execute(Section $section, Video $video, array $videoData)
    {
        if (isset($videoData['videoUrl'])) {
            $videoPath = (new StoreCourseVideoAction())->execute($videoData['videoUrl']);
            $videoData['videoUrl'] = "https://online-bucket.s3.amazonaws.com/$videoPath";
        }

        if ($video->videoUrl) {
            $existingVideoPath = basename(parse_url($video->videoUrl, PHP_URL_PATH));
            if (Storage::disk('s3')->exists("course_videos/videos/{$existingVideoPath}")) {
                Storage::disk('s3')->delete("course_videos/videos/{$existingVideoPath}");
            }
        }
        $videoData['section_id'] = $section->id;

        return $video->update($videoData);


    }

}
