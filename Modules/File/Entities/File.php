<?php

namespace Modules\File\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Section\Entities\Section;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'fileUrl',
        'section_id',
        'teacher_id'
    ];

    protected static function newFactory()
    {
        return \Modules\File\Database\factories\FileFactory::new();
    }

    public function sections()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
    public function scopeFilter($query, array $filters){
        $query->when(isset($filters['teacher_id']),function ($query) use ($filters){
            $query->where('teacher_id',$filters['teacher_id']);
        });
    }
}
