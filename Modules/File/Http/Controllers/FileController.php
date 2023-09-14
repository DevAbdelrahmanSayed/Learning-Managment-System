<?php

namespace Modules\File\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\File\Entities\File;
use Modules\File\Http\Requests\FileRequest;
use Modules\Section\Entities\Section;

class FileController extends Controller
{
    public function index()
    {

    }

    public function store(FileRequest $request)
    {
        $section = DB::table('sections')->find($request->section_id);
        $authenticatedTeacherId = Auth::guard('teacher')->user()->getKey();
        if ($section->teacher_id !== $authenticatedTeacherId) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to access this files', []);
        }
        $uploadedFilePath = $request->file('fileUrl')->storePublicly('course_file/files', 's3');

        $fileInsert = DB::table('files')->insertGetId([
            'fileUrl' => "https://online-bucket.s3.amazonaws.com/$uploadedFilePath",
            'section_id' => $request->section_id,
            'teacher_id' => $authenticatedTeacherId,
            'created_at' => now(),
        ]);
        if ($fileInsert) {
            return ApiResponse::sendResponse(201, 'Your files uploaded successfully', ['File_id' => $fileInsert]);
        }

        return ApiResponse::sendResponse(200, 'Failed to upload the file', []);
    }

    public function show($id)
    {
        //
    }

    public function update(FileRequest $request, $fileId)
    {

        $section = Section::find($request->section_id);
        if (! $section) {
            return ApiResponse::sendResponse(404, 'section not found', []);
        }
        $file = File::find($fileId);
        if (! $file) {
            return ApiResponse::sendResponse(404, 'File not found', []);
        }

        $authenticatedTeacherId = Auth::guard('teacher')->user()->getKey();
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
            'updated_at' => now(),
        ]);
        if ($fileUpdate) {
            return ApiResponse::sendResponse(201, 'Your files updated successfully', ['File_id' => $fileId]);
        }

        return ApiResponse::sendResponse(200, 'Failed to updated the file', []);

    }

    public function destroy($fileId)
    {
        $file = File::find($fileId);

        if (! $file) {
            return ApiResponse::sendResponse(404, 'File not found', []);
        }

        $authenticatedTeacherId = Auth::guard('teacher')->user()->getKey();

        if ($file->teacher_id !== $authenticatedTeacherId) {
            return ApiResponse::sendResponse(403, 'Unauthorized: You do not have permission to delete this file', []);
        }

        if ($file->fileUrl) {
            $existingFilePath = basename(parse_url($file->fileUrl, PHP_URL_PATH));
            $s3Disk = Storage::disk('s3');

            if ($s3Disk->exists("course_file/files/{$existingFilePath}")) {
                $s3Disk->delete("course_file/files/{$existingFilePath}");
            }
        }

        if ($file->delete()) {
            return ApiResponse::sendResponse(200, 'File deleted successfully', []);
        }

        return ApiResponse::sendResponse(200, 'Failed to delete the file', []);
    }
}
