<?php

namespace Modules\Teacher\Actions;

use Modules\Teacher\Entities\Teacher;

class GetAllTeachersAction
{
    public function execute()
    {
        return Teacher::all();
    }
}
