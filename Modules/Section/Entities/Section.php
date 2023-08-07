<?php

namespace Modules\Section\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Course\Entities\Course;
use Modules\Video\Entities\Video;

class Section extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'description',
    ];

    protected static function newFactory()
    {
        return \Modules\Section\Database\factories\SectionFactory::new();
    }
    public function courses()
    {
        return $this->belongsTo(Course::class,'course_id');

    }
    public function Videos()
    {
        return $this->hasMany(Video::class,'section_id');

    }
}
