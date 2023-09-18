<?php

namespace Modules\Teacher\Actions;



use Illuminate\Http\UploadedFile;

class StoreTeacherProfileAction
{
    public function execute(UploadedFile $profile)
    {
        $path = $profile->storePublicly('profile_photos/photo', 's3');
        return $path;
    }
}
