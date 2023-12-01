<?php

namespace Modules\Comment\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return[
            'comment_id' => $this->id,
            'user_id' => $this->user_id,
            'course_id' => $this->id,
            'comment_text' => $this->comment_text,
            'commentable_id' => $this->commentable_id,
            'commentable_type' => $this->commentable_type,
            'reply_id' => $this->parent_comment_id,
        ];
    }
}
