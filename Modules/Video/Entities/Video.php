<?php

namespace Modules\Video\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Section\Entities\Section;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'videoUrl',
        'section_id',
        'course_id',
    ];

    protected static function newFactory()
    {
        return \Modules\Video\Database\factories\VideoFactory::new();
    }

    public function sections()
    {
        return $this->belongsTo(Section::class, 'section_id');

    }
}
