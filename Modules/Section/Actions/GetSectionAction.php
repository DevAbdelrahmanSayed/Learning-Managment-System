<?php

namespace Modules\Section\Actions;

use Modules\Course\Entities\Course;
use Modules\Section\Entities\Section;

class GetSectionAction
{
    public function execute($courseId, array $filters = [])
    {
        $sections = Section::where('course_id', $courseId)->filter($filters)->latest()->get();
        return $sections;
    }

}
