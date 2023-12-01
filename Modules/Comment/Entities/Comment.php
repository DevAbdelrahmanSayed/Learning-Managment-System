<?php

namespace Modules\Comment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'course_id', 'comment_text', 'parent_comment_id','commentable_id'];

    public function commentable(): MorphTo
    {
       return $this->morphTo();
    }
    public function parentComment()
    {
        return $this->belongsTo(Comment::class , 'parent_comment_id');
    }
    public function replies()
    {
        return $this->hasMany(Comment::class , 'parent_comment_id');
    }
    protected static function newFactory()
    {
        return \Modules\Comment\Database\factories\CommentFactory::new();
    }
}
