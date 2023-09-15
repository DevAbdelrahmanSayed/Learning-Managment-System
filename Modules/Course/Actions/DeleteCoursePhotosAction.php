<?php

namespace Modules\Course\Actions;

use Illuminate\Support\Facades\Storage;
use Modules\Course\Entities\Course;

class DeleteCoursePhotosAction
{
    public function execute(Course $course)
    {
        $existingPhotoPath = basename(parse_url($course->photo, PHP_URL_PATH));
        if (Storage::disk('s3')->exists("course_photos/photo/{$existingPhotoPath}")) {
            Storage::disk('s3')->delete("course_photos/photo/{$existingPhotoPath}");
        }

        return true;
    }
}
