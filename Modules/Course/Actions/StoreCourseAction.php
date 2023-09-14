<?php

namespace Modules\Course\Actions;

use Illuminate\Support\Str;
use Modules\Course\Entities\Course;
use Modules\Teacher\Entities\Teacher;

class StoreCourseAction
{
    public function execute($requestData, Teacher $teacher)
    {

        $photoPath = $requestData->file('photo')->storePublicly('course_photos/photo', 's3');
        $data = [
            'title' => $requestData->title,
            'description' => $requestData->description,
            'photo' => "https://online-bucket.s3.amazonaws.com/$photoPath",
            'price' => $requestData->price,
            'category_id' => $requestData->category_id,
            'created_at' => now(),
            'slug' => Str::slug($requestData->title).'.'.Str::uuid(),
            'teacher_id' => $teacher->getKey(),
        ];
        $course = Course::create($data);
        if ($course) {
            return [
                'status' => 'success',
                'message' => 'Your course was created successfully',
                'data' => ['Course_id' => $course->id],
            ];
        }

        return [
            'status' => 'error',
            'message' => 'Failed to create the course',
        ];
    }
}
