<?php

namespace Modules\Course\Actions;

use Illuminate\Support\Facades\Storage;
use Modules\Course\Entities\Course;
use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateCourseAction
{
    public function execute(Course $course, $newCourseData)
    {
        $photoPath = $newCourseData->file('photo')->storePublicly('course_photos/photo', 's3');

        if ($course->photo) {
            $existingPhotoPath = basename(parse_url($course->photo, PHP_URL_PATH));
            if (Storage::disk('s3')->exists("course_photos/photo/{$existingPhotoPath}")) {
                Storage::disk('s3')->delete("course_photos/photo/{$existingPhotoPath}");
            }
        }
        $data = [
            'title' => $newCourseData->title,
            'description' => $newCourseData->description,
            'photo' => "https://online-bucket.s3.amazonaws.com/$photoPath",
            'price' => $newCourseData->price,
            'category_id' => $newCourseData->category_id,
            'updated_at' => now(),
        ];
        $course->update($data);

        return [
            'status' => JsonResponse::HTTP_OK,
            'message' => 'course updated successfully.',
            'data' => ['Course_id' => $courseId],
        ];

    }
}
