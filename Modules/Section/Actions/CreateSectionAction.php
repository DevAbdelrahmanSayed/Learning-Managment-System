<?php

namespace Modules\Section\Actions;

use Modules\Course\Entities\Course;
use Modules\Section\Entities\Section;

class CreateSectionAction
{
    public function execute(Course $course, array $sectionData)
    {
        $sectionData['teacher_id'] = $course->teacher_id;
        $sectionData['course_id'] = $course->id;

        $section = Section::create($sectionData);

        return $section;
    }
}
