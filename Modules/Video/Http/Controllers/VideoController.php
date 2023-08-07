<?php

namespace Modules\Video\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Section\Entities\Section;
use Modules\Video\Http\Requests\VideoRequest;

class VideoController extends Controller
{

    public function create(VideoRequest $request, $sectionId)
    {
        // Find the sectionId
        $section = Section::find($sectionId);

        // Check if the sectionId does not exist
        if (!$section) {
            return ApiResponse::sendResponse(200, 'sectionID not found', []);
        }

        // Get the authenticated teacher's ID
        $authenticatedTeacherId = Auth::guard('teacher')->user()->id;

        // Check if the teacher is authenticated
        if ($section->teacher_id !== $authenticatedTeacherId || empty($authenticatedTeacherId)) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to access this video', []);
        }

        // Upload Video to S3
        $uploadedVideoPath = $request->file('videoUrl')->storePublicly('course_videos/videos', 's3');

        // Make the data of the video to insert them
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'videoUrl' => "https://online-bucket.s3.amazonaws.com/$uploadedVideoPath",
            'section_id' => $sectionId,
            'teacher_id' =>  $authenticatedTeacherId,
            'created_at' => now(),
            'updated_at' => now()
        ];

        // Insert the video into the database
        $videoInsert = DB::table('videos')->insert($data);

        if ($videoInsert) {
            return ApiResponse::sendResponse(201, 'Your Video uploaded successfully', []);
        }

        return ApiResponse::sendResponse(200, 'Failed to upload the Video', []);
    }

}
