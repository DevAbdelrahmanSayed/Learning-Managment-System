<?php

namespace Modules\Course\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Course\Entities\Course;
use Modules\Teacher\Entities\Teacher;


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
