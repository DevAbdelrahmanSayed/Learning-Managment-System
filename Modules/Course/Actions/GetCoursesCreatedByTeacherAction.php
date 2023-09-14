<?php

namespace Modules\Course\Actions;

use Modules\Course\Transformers\TeacherCourseResource;
use Modules\Teacher\Entities\Teacher;
use Symfony\Component\HttpFoundation\JsonResponse;

class GetCoursesCreatedByTeacherAction
{
    public function execute($user)
    {
        $teacher = Teacher::with('courses')->find($user->getKey());

        if (! $teacher) {
            return [
                'status' => JsonResponse::HTTP_NOT_FOUND,
                'message' => 'Teacher not found',
                'data' => [],
            ];
        }

        if ($teacher->id !== $user->getKey()) {
            return [
                'status' => JsonResponse::HTTP_FORBIDDEN,
                'message' => 'You are not allowed to take this action.',
                'data' => [],
            ];
        }

        return [
            'status' => JsonResponse::HTTP_OK,
            'message' => 'Courses retrieved successfully',
            'data' => TeacherCourseResource::collection($teacher->courses),
        ];
    }
}
