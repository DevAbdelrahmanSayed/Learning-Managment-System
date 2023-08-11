<?php

namespace Modules\File\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\File\Http\Requests\FileRequest;
use Modules\Section\Entities\Section;
use Modules\Video\Http\Requests\VideoRequest;

class FileController extends Controller
{

    public function index()
    {
        dd('hi');
    }


    public function store(FileRequest $request)
    {
        $section = Section::find($request->section_id);
        if (!$section) {
            return ApiResponse::sendResponse(200, 'sectionID not found', []);
        }

        if ($section->teacher_id !== auth()->user()->id) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to access this files', []);
        }
        $uploadedFilePath = $request->file('fileUrl')->storePublicly('course_file/files', 's3');

        $videoInsert = DB::table('files')->insert([
            'fileUrl' => "https://online-bucket.s3.amazonaws.com/$uploadedFilePath",
            'section_id' => $request->section_id,
            'teacher_id' => auth()->user()->id,
        ]);


        return ApiResponse::sendResponse(201, 'Your files uploaded successfully', []);
    }



    public function show($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
