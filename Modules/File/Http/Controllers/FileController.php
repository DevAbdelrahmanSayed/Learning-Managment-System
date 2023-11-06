<?php

namespace Modules\File\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\File\Actions\DeleteFileAction;
use Modules\File\Actions\GetFilesAction;
use Modules\File\Actions\StoreFileAction;
use Modules\File\Actions\UpdateFileAction;
use Modules\File\Entities\File;
use Modules\File\Http\Requests\FileRequest;
use Modules\File\Http\Requests\IndexFileRequest;
use Modules\Section\Entities\Section;
use Modules\Section\Http\Requests\IndexSectionRequest;
use Modules\Teacher\Transformers\FileResource;

class FileController extends Controller
{
    public function getFiles(IndexFileRequest $request,Section $section, GetFilesAction $getFilesAction)
    {
        $teacherId = (int)$request->input('teacher_id');
        $user = Auth::guard('teacher')->user();

        if (!$user) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, "You must be logged in as a teacher to access this action");
        }

        if ($request->has('teacher_id') ) {
            if ($teacherId!== $user->getKey())
                return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'You do not have permission to take this action');
        }

        $files = $teacherId ? $getFilesAction->execute($section->id,['teacher_id' => $request->input('teacher_id')]) : $getFilesAction->execute($section->id);

        if ($files->isEmpty()) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'No Files found');
        }


        $data = FileResource::collection($files);

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Files retrieved successfully.', $data);

    }
    public function store(FileRequest $request, StoreFileAction $storeFileAction)
    {

        $sectionId = $request->input('section_id');
        $section = Section::find($sectionId);
        if (!$section){
            return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'No Sections found');
        }
        if ($section->teacher_id !== Auth::guard('teacher')->user()->getKey()) {

            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not allowed to take this action');
        }

        $file = $storeFileAction->execute($section, $request->validated());
        return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Your files uploaded successfully', ['fileId' => $file->id]);


    }


    public function show($id)
    {
        //
    }

    public function update( File $file , FileRequest $request , UpdateFileAction $updateFileAction)
    {
        if ($file->teacher_id !== Auth::guard('teacher')->user()->getKey()) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not allowed to take this action');
        }

        $file = $updateFileAction->execute( $file, $request->validated());

            return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Your files updated successfully', ['fileId' => $file->id]);

    }

    public function destroy( File $file, DeleteFileAction $deleteFileAction)
    {

        if ($file->teacher_id !== Auth::guard('teacher')->user()->getKey()) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not allowed to take this action');
        }
        $deleteFileAction->execute($file);
        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'File deleted successfully');

    }
}
