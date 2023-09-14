<?php

namespace Modules\Course\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Section\Entities\Section;
use Modules\Teacher\Entities\Teacher;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'photo',
        'slug',
        'price',
        'category_id',
        'teacher_id',
    ];

    protected static function newFactory()
    {
        return \Modules\Course\Database\factories\CourseFactory::new();
    }

    public function teachers()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');

    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'course_id');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['teacher_id'] ?? false, function ($query) use ($filters) {
            $query->where('teacher_id', $filters['teacher_id']);
        });
    }
}
