<?php

namespace Modules\Comment\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Comment\Entities\Comment;
use Modules\Comment\Http\Requests\CommentRequest;
use Modules\Comment\Transformers\CommentResource;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        $currentUser =Auth::guard('teacher')->check() ? Auth::guard('teacher')->user() : Auth::guard('student')->user();
        $commentData = array_merge($request->validated(), ['user_id' => $currentUser->id]);
        $comment = Comment::create($commentData);
        return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Comment created successfully.', new CommentResource($comment));
    }
}
