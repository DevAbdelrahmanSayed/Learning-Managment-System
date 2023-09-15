<?php

namespace Modules\Course\Actions;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Modules\Course\Entities\Course;
use Modules\Teacher\Entities\Teacher;
use Modules\Course\Actions\StoreCoursePhotoAction;

class StoreCourseAction
{
    public function execute(array $courseData, Teacher $teacher)
    {
        $course = null;

        DB::transaction(function () use ($courseData, $teacher, &$course) {
            $photoPath = (new StoreCoursePhotoAction)->execute($courseData['photo']);
            $courseData['photo'] = "https://online-bucket.s3.amazonaws.com/$photoPath";
            $courseData['slug'] = Str::slug($courseData['title']).'.'.Str::uuid();
            $courseData['teacher_id'] = $teacher->getKey();
            $course = Course::create($courseData);
        });

        return $course;
    }
}
