<?php

namespace Modules\Course\Actions;

use Illuminate\Support\Facades\Storage;
use Modules\Course\Entities\Course;

class UpdateCourseAction
{
    public function execute(Course $course, $newCourseData)
    {
        if (isset($newCourseData['photo'])) {
            $photoPath = (new StoreCoursePhotoAction)->execute($newCourseData['photo']);
            $newCourseData['photo'] = "https://online-bucket.s3.amazonaws.com/$photoPath";
        }

        if ($course->photo) {
            $existingPhotoPath = basename(parse_url($course->photo, PHP_URL_PATH));
            if (Storage::disk('s3')->exists("course_photos/photo/{$existingPhotoPath}")) {
                Storage::disk('s3')->delete("course_photos/photo/{$existingPhotoPath}");
            }
        }
        $course->update($newCourseData);

        return $course;
    }
}
