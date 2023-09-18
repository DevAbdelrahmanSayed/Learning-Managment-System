<?php

namespace Modules\Video\Actions;

use Modules\Section\Entities\Section;
use Modules\Video\Entities\Video;

class StoreVideoAction
{
    public function execute(Section $section, array $videoData)
    {
        $videoPath = (new StoreCourseVideoAction())->execute($videoData['videoUrl']);
        $videoData['videoUrl'] = "https://online-bucket.s3.amazonaws.com/$videoPath";
        $videoData['teacher_id'] = $section->teacher_id;
        $videoData['section_id'] = $section->id;


        return Video::create($videoData);

    }
}
