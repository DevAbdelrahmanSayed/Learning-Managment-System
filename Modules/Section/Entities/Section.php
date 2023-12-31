<?php

namespace Modules\Section\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Course\Entities\Course;
use Modules\File\Entities\File;
use Modules\Video\Entities\Video;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'course_id',
        'teacher_id',
    ];

    protected static function newFactory()
    {
        return \Modules\Section\Database\factories\SectionFactory::new();
    }

    public function courses()
    {
        return $this->belongsTo(Course::class, 'course_id');

    }

    public function Videos()
    {
        return $this->hasMany(Video::class, 'section_id');

    }

    public function files()
    {
        return $this->hasMany(File::class, 'section_id');

    }
    public function scopeFilter($query, array $filters){
        $query->when(isset($filters['teacher_id']), function ($query) use ($filters) {
            $query->where('teacher_id', $filters['teacher_id']);
        });





    }
}
