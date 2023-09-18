<?php

namespace Modules\Teacher\Actions;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Modules\Teacher\Entities\Teacher;


class UpdateTeachersAction
{
    public function execute(Teacher $teacher ,array $teacherData)
    {
        if (isset($newCourseData['photo'])) {
            $profilePath = (new StoreTeacherProfileAction())->execute($teacherData['profile']);
            $teacherData['profile'] = "https://online-bucket.s3.amazonaws.com/$profilePath";
        }
        if ($teacher->profile){
            $existingPhotoPath = basename(parse_url($teacher->profile, PHP_URL_PATH));
            if (Storage::disk('s3')->exists("profile_photos/photo/{$existingPhotoPath}")) {
                Storage::disk('s3')->delete("profile_photos/photo/{$existingPhotoPath}");
            }
        }
        $teacherData['password'] = Hash::make($teacherData['password']);

        $teacher->update($teacherData);
        return $teacher;
    }
}
