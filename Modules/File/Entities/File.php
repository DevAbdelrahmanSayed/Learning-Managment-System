<?php

namespace Modules\File\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Section\Entities\Section;

class file extends Model
{
    use HasFactory;

    protected $fillable = ['fileUrl'];

    protected static function newFactory()
    {
        return \Modules\File\Database\factories\FileFactory::new();
    }
    public function sections()
    {
        return $this->belongsTo(Section::class,'section_id');
    }
}
