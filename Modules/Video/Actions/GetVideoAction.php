<?php

namespace Modules\Video\Actions;

use Illuminate\Support\Facades\Storage;
use Modules\Video\Entities\Video;

class GetVideoAction
{
    public function execute($section_id,array $filters=[])
    {
        $video = Video::where('section_id',$section_id)->filter($filters)->latest()->get();
        return $video;
    }
}
