<?php

namespace Modules\Video\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Section\Entities\Section;
use Modules\Video\Entities\Video;
use Modules\Video\Http\Requests\VideoRequest;

class VideoController extends Controller
{
    public function index()
    {
        //
    }

    public function store(VideoRequest $request)
    {
        $section = DB::table('sections')->find($request->section_id);
        if (! $section) {
            return ApiResponse::sendResponse(404, 'section not found', []);
        }
        $authenticatedTeacher = Auth::guard('teacher')->user()->getKey();

        if ($section->teacher_id !== $authenticatedTeacher) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to access this video', []);
        }

        // Upload Video to S3
        $uploadedVideoPath = $request->file('videoUrl')->storePublicly('course_videos/videos', 's3');

        $data = [
            'visible' => $request->visible,
            'title' => $request->title,
            'videoUrl' => "https://online-bucket.s3.amazonaws.com/$uploadedVideoPath",
            'section_id' => $request->section_id,
            'teacher_id' => $authenticatedTeacher,
            'created_at' => now(),
        ];

        $videoInsert = DB::table('videos')->insertGetId($data);

        if ($videoInsert) {
            return ApiResponse::sendResponse(201, 'Your Video uploaded successfully', ['Video_id' => $videoInsert]);
        }

        return ApiResponse::sendResponse(200, 'Failed to upload the Video', []);
    }

    public function show($id)
    {
        //
    }

    public function update(VideoRequest $request, $videoId)
    {
        $section = Section::find($request->section_id);
        if (! $section) {
            return ApiResponse::sendResponse(404, 'section not found', []);
        }
        $video = Video::find($videoId);
        if (! $video) {
            return ApiResponse::sendResponse(404, 'Video not found', []);
        }

        $authenticatedTeacherId = Auth::guard('teacher')->user()->getKey();

        if ($video->teacher_id !== $authenticatedTeacherId) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to update this video', []);
        }

        if ($video->videoUrl) {
            $existingVideoPath = basename(parse_url($video->videoUrl, PHP_URL_PATH));
            if (Storage::disk('s3')->exists("course_videos/videos/{$existingVideoPath}")) {
                Storage::disk('s3')->delete("course_videos/videos/{$existingVideoPath}");
            }
        }

        $uploadedVideoPath = $request->file('videoUrl')->storePublicly('course_videos/videos', 's3');

        $data = [
            'title' => $request->title,
            'section_id' => $request->section_id,
            'videoUrl' => "https://online-bucket.s3.amazonaws.com/$uploadedVideoPath",
            'updated_at' => now(),
        ];

        $video = Video::where('id', $videoId)->update($data);
        if ($video) {
            return ApiResponse::sendResponse(200, 'Video updated successfully', ['Video_id' => $videoId]);
        }

        return ApiResponse::sendResponse(200, 'Failed to updated the Video', []);
    }

    public function destroy($videoId)
    {
        $video = Video::find($videoId);

        if (! $video) {
            return ApiResponse::sendResponse(404, 'Video not found', []);
        }

        $authenticatedTeacherId = Auth::guard('teacher')->user()->getKey();

        if ($video->teacher_id !== $authenticatedTeacherId) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to delete this video', []);
        }

        if ($video->videoUrl) {
            $existingVideoPath = basename(parse_url($video->videoUrl, PHP_URL_PATH));
            $s3Disk = Storage::disk('s3');

            if ($s3Disk->exists("course_videos/videos/{$existingVideoPath}")) {
                $s3Disk->delete("course_videos/videos/{$existingVideoPath}");
            }
        }

        if ($video->delete()) {
            return ApiResponse::sendResponse(200, 'Video deleted successfully', []);
        }

        return ApiResponse::sendResponse(200, 'Failed to delete the Video', []);
    }
}
