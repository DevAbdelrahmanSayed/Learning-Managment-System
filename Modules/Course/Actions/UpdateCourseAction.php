<?php

namespace Modules\Course\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Course\Entities\Course;
use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateCourseAction
{
    public function execute($teacher, $courseId, $requestData)
    {
        $course = Course::find($courseId);
        if (! $course) {
            return [
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'message' => 'Course not found.',
                'data' => [],
            ];
        }
        if ($course->teacher_id !== Auth::guard('teacher')->user()->getKey()) {
            return [
                'status' => JsonResponse::HTTP_FORBIDDEN,
                'message' => 'You are not allowed to take this action.',
                'data' => [],
            ];

        }
        $photoPath = $requestData->file('photo')->storePublicly('course_photos/photo', 's3');

        if ($course->photo) {
            $existingPhotoPath = basename(parse_url($course->photo, PHP_URL_PATH));
            if (Storage::disk('s3')->exists("course_photos/photo/{$existingPhotoPath}")) {
                Storage::disk('s3')->delete("course_photos/photo/{$existingPhotoPath}");
            }
        }
        $data = [
            'title' => $requestData->title,
            'description' => $requestData->description,
            'photo' => "https://online-bucket.s3.amazonaws.com/$photoPath",
            'price' => $requestData->price,
            'category_id' => $requestData->category_id,
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
