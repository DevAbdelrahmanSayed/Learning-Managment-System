<?php

namespace Modules\Video\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Section\Entities\Section;
use Modules\Video\Actions\DeleteVideoAction;
use Modules\Video\Actions\GetVideoAction;
use Modules\Video\Actions\StoreVideoAction;
use Modules\Video\Actions\UpdateVideoAction;
use Modules\Video\Entities\Video;
use Modules\Video\Http\Requests\IndexVideoRequest;
use Modules\Video\Http\Requests\VideoRequest;
use Modules\Video\Transformers\VideoResource;

class VideoController extends Controller
{
    public function index()
    {
        //
    }
    public function getVideos(IndexVideoRequest $request,Section $section, GetVideoAction $getVideoAction)
    {
        $teacherId = (int)$request->input('teacher_id');
        $teacher = Auth::guard('teacher')->user();

        if (!$teacher) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, "You must be logged in as a teacher to access this action");
        }

        if ($request->has('teacher_id') ) {
            if ($teacherId!== $teacher->getKey())
                return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'You do not have permission to take this action');
        }

        $videos = $teacherId ? $getVideoAction->execute($section->id,['teacher_id' => $request->input('teacher_id')]) : $getVideoAction->execute($section->id);

        if ($videos->isEmpty()) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'No Videos found');
        }


        $data = VideoResource::collection($videos);

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Videos retrieved successfully.', $data);

    }

    public function store( VideoRequest $request, StoreVideoAction $storeVideoAction)
    {
        $sectionId = $request->input('section_id');
        $section = Section::find($sectionId);
        if (!$section){
            return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'No Sections found');
        }
        if ($section->teacher_id !== Auth::guard('teacher')->user()->getKey()) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not allowed to take this action');
        }
        $video = $storeVideoAction->execute($section,$request->validated());

        return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Video created successfully.', ['video_id' => $video->id]);
    }

    public function show($id)
    {
        //
    }

    public function update(Section $section, Video $video, VideoRequest $request, UpdateVideoAction $updateVideoAction)
    {

        if ($video->teacher_id !== Auth::guard('teacher')->user()->getKey()) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not allowed to take this action');
        }
        $video = $updateVideoAction->execute($section, $video, $request->validated());

        return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Video updated successfully', ['video_id' => $video->id]);

    }

    public function destroy(Section $section, Video $video, DeleteVideoAction $deleteVideoAction)
    {
        if ($video->teacher_id !== Auth::guard('teacher')->user()->getKey()) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not allowed to take this action');
        }
        $deleteVideoAction->execute($video);

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Video deleted successfully');
    }
}
