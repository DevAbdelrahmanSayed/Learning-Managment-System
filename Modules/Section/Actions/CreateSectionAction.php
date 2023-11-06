<?php

namespace Modules\Section\Actions;

use Modules\Course\Entities\Course;
use Modules\Section\Entities\Section;

class CreateSectionAction
{
    public function execute( $course_ID, $teacher_id,$sectionData )
    {
        $sectionData['teacher_id'] = $teacher_id;
        $sectionData['course_id'] = $course_ID;

        $section = Section::create($sectionData);

        return $section;
    }
}
