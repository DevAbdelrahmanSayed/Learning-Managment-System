<?php

namespace Modules\Section\Actions;

use Modules\Section\Entities\Section;

class UpdateSectionAction
{
    public function execute(Section $section, array $sectionData)
    {
        $section->update($sectionData);

        return $section;
    }
}
