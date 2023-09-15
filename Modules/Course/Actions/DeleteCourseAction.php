<?php

namespace Modules\Course\Actions;

use Modules\Course\Entities\Course;

class DeleteCourseAction
{
    public function execute(Course $course)
    {
        if ($course->photo) {
            (new DeleteCoursePhotosAction)->execute($course);
        }
        $course->delete();
    }
}
