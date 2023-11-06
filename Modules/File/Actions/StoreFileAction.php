<?php

namespace Modules\File\Actions;

use App\Helpers\ApiResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\File\Entities\File;
use Modules\Section\Entities\Section;

class StoreFileAction
{
    public function execute(Section $section, array $fileData)
    {
        if (isset($fileData['fileUrl'])) {
            $filePath = (new StoreCourseFileAction())->execute($fileData['fileUrl']);
            $fileData['fileUrl'] = "https://online-bucket.s3.amazonaws.com/$filePath";
        }

        $fileData['section_id'] = $section->id;
        $fileData['teacher_id'] = $section->teacher_id;

        return File::create($fileData);
    }
}
