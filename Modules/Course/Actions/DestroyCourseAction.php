<?php

namespace Modules\Course\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Course\Entities\Course;
use Symfony\Component\HttpFoundation\JsonResponse;

class DestroyCourseAction
{
    public function execute($user,$courseId)
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
        if ($course->photo) {
            $existingPhotoPath = basename(parse_url($course->photo, PHP_URL_PATH));
            if (Storage::disk('s3')->exists("course_photos/photo/{$existingPhotoPath}")) {
                Storage::disk('s3')->delete("course_photos/photo/{$existingPhotoPath}");
            }
        }
        $course->delete();
        return [
            'status' => JsonResponse::HTTP_OK,
            'message' => 'course deleted successfully.',
            'data' => [],
        ];

    }

}
