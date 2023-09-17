<?php

namespace Modules\Video\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Section\Entities\Section;
use Modules\Video\Actions\DeleteVideoAction;
use Modules\Video\Actions\StoreVideoAction;
use Modules\Video\Actions\UpdateVideoAction;
use Modules\Video\Entities\Video;
use Modules\Video\Http\Requests\VideoRequest;

class VideoController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Section $section, VideoRequest $request, StoreVideoAction $storeVideoAction)
    {
        if (! $section) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'section not found', null);
        }
        if ($section->teacher_id !== Auth::guard('teacher')->user()->id) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not allowed to take this action', null);
        }
        $video = $storeVideoAction->execute($section, $request->validated());

        return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Video created successfully.', ['Video_id' => $video->id]);
    }

    public function show($id)
    {
        //
    }

    public function update(Section $section, Video $video, VideoRequest $request, UpdateVideoAction $updateVideoAction)
    {
        if (! $section && ! $video) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'not found', null);
        }
        if ($video->teacher_id !== Auth::guard('teacher')->user()->id) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not allowed to take this action', null);
        }
        $video = $updateVideoAction->execute($section, $video, $request->validated());

        return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Video updated successfully', ['Video_id' => $video->id]);

    }

    public function destroy(Section $section, Video $video, DeleteVideoAction $deleteVideoAction)
    {
        if (! $video) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'Video not found', null);
        }

        if ($video->teacher_id !== Auth::guard('teacher')->user()->id) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not allowed to take this action', null);
        }
        $deleteVideoAction->execute($video);

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Video deleted successfully', null);
    }
}
