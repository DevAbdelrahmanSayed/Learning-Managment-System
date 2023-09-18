<?php

namespace Modules\Teacher\Actions;

use Illuminate\Support\Facades\Auth;
use Modules\Teacher\Entities\Teacher;

class DeleteTeacherAction
{
    public function execute(Teacher $teacher )
    {
        $teacher->delete();
        auth('teacher')->logout();
        return $teacher;
    }
}
