<?php
namespace Modules\Course\Actions;
use Illuminate\Http\UploadedFile;


class StoreCoursePhotoAction{
    public function execute(UploadedFile $photo){
        $path = $photo->storePublicly('course_photos/photo', 's3');
        return $path;
    }
}
