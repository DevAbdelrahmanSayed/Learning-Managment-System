<?php

namespace Modules\Section\Actions;

use Modules\Section\Entities\Section;

class DeleteSectionAction
{
    public function execute(Section $section)
    {
        $section->delete();
    }
}
