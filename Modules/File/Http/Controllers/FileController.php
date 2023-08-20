<?php

namespace Modules\File\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\File\Entities\file;
use Modules\File\Http\Requests\FileRequest;

class FileController extends Controller
{
    public function index()
    {

    }

    public function store(FileRequest $request)
    {
        $section = DB::table('sections')->find($request->section_id);

        if ($section->teacher_id !== auth()->user()->id) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to access this files', []);
        }
        $uploadedFilePath = $request->file('fileUrl')->storePublicly('course_file/files', 's3');

        $fileInsert = DB::table('files')->insert([
            'fileUrl' => "https://online-bucket.s3.amazonaws.com/$uploadedFilePath",
            'section_id' => $request->section_id,
            'teacher_id' => auth()->user()->id,
            'created_at' => now(),
        ]);
        if ($fileInsert) {
            return ApiResponse::sendResponse(201, 'Your files uploaded successfully', []);
        }

        return ApiResponse::sendResponse(200, 'Failed to upload the file', []);
    }

    public function show($id)
    {
        //
    }

    public function update(FileRequest $request, $fileId)
    {

        $section = DB::table('sections')->find($request->section_id);
        $course = DB::table('courses')->find($request->course_id);

        $file = File::find($fileId);
        if (! $file) {
            return ApiResponse::sendResponse(404, 'File not found', []);
        }

        $authenticatedTeacherId = Auth::guard('teacher')->id();
        if ($file->teacher_id !== $authenticatedTeacherId) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to update this file', []);
        }
        $uploadedFilePath = $request->file('fileUrl')->storePublicly('course_file/files', 's3');

        if ($file->photo) {
            $existingFilePath = basename(parse_url($file->fileUrl, PHP_URL_PATH));
            if (Storage::disk('s3')->exists("course_file/files/{$existingFilePath}")) {
                Storage::disk('s3')->delete("course_file/files/{$existingFilePath}");
            }
        }
        $fileUpdate = DB::table('files')->update([
            'fileUrl' => "https://online-bucket.s3.amazonaws.com/$uploadedFilePath",
            'section_id' => $request->section_id,
            'course_id' => $request->course_id,
            'updated_at' => now(),
        ]);
        if ($fileUpdate) {
            return ApiResponse::sendResponse(201, 'Your files updated successfully', []);
        }

        return ApiResponse::sendResponse(200, 'Failed to updated the file', []);

    }

    public function destroy($fileId)
    {
        $file = File::find($fileId);
        if (! $file) {
            return ApiResponse::sendResponse(404, 'File not found', []);
        }
        $authenticatedTeacher = Auth::guard('teacher')->user()->id;
        if ($file->teacher_id !== $authenticatedTeacher) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to delete this course', []);
        }
        if ($file->fileUrl) {
            $existingFilePath = basename(parse_url($file->fileUrl, PHP_URL_PATH));
            if (Storage::disk('s3')->exists("course_file/files/{$existingFilePath}")) {
                Storage::disk('s3')->delete("course_file/files/{$existingFilePath}");
            }
        }
        DB::table('files')->where('id', $fileId)->delete();

        return ApiResponse::sendResponse(200, 'file deleted successfully', []);
    }
}
