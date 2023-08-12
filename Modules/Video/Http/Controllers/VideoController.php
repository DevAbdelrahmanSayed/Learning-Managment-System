<?php

namespace Modules\Video\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Section\Entities\Section;
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

        // Get the authenticated teacher's ID
        $authenticatedTeacher = Auth::guard('teacher')->user()->id;

        // Check if the teacher is authenticated
        if ($section->teacher_id !== $authenticatedTeacher) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to access this video', []);
        }

        // Upload Video to S3
        $uploadedVideoPath = $request->file('videoUrl')->storePublicly('course_videos/videos', 's3');

        // Make the data of the video to insert them
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'videoUrl' => "https://online-bucket.s3.amazonaws.com/$uploadedVideoPath",
            'section_id' =>$request->section_id,
            'teacher_id' =>  $authenticatedTeacher,
            'created_at' => now(),
        ];

        // Insert the video into the database
        $videoInsert = DB::table('videos')->insert($data);

        if ($videoInsert) {
            return ApiResponse::sendResponse(201, 'Your Video uploaded successfully', []);
        }

        return ApiResponse::sendResponse(200, 'Failed to upload the Video', []);
    }



    public function show($id)
    {
        //
    }


    public function update(VideoRequest $request, $videoId)
    {
        $video = DB::table('videos')->find($videoId);
        $section = DB::table('sections')->find($request->section_id);
        $course =DB::table('courses')->find($request->course_id);

        if (!$video) {
            return ApiResponse::sendResponse(404, 'Video not found', []);
        }

        $authenticatedTeacherId = Auth::guard('teacher')->user()->id;

        if ($video->teacher_id !== $authenticatedTeacherId) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to update this video', []);
        }

        // Delete the old video from storage
        if ($video->videoUrl) {
            $existingVideoPath = basename(parse_url($video->videoUrl, PHP_URL_PATH));
            if (Storage::disk('s3')->exists("course_videos/videos/{$existingVideoPath}")) {
                Storage::disk('s3')->delete("course_videos/videos/{$existingVideoPath}");
            }
        }

        // Upload the new video
        $uploadedVideoPath = $request->file('videoUrl')->storePublicly('course_videos/videos', 's3');

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'section_id' =>$request->section_id,
            'course_id' =>$request->course_id,
            'videoUrl' => "https://online-bucket.s3.amazonaws.com/$uploadedVideoPath",
            'updated_at' => now(),
        ];

        DB::table('videos')->where('id', $videoId)->update($data);

        return ApiResponse::sendResponse(200, 'Video updated successfully', []);
    }


    public function destroy($videoId)
    {
        $video = DB::table('videos')->find($videoId);

        if (!$video) {
            return ApiResponse::sendResponse(404, 'Video not found', []);
        }

        $authenticatedTeacherId = Auth::guard('teacher')->user()->id;

        if ($video->teacher_id !== $authenticatedTeacherId) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to delete this video', []);
        }

        if ($video->videoUrl) {
            $existingVideoPath = basename(parse_url($video->videoUrl, PHP_URL_PATH));
            if (Storage::disk('s3')->exists("course_videos/videos/{$existingVideoPath}")) {
                Storage::disk('s3')->delete("course_videos/videos/{$existingVideoPath}");
            }
        }

        DB::table('videos')->where('id', $videoId)->delete();

        return ApiResponse::sendResponse(200, 'Video deleted successfully', []);
    }

}
