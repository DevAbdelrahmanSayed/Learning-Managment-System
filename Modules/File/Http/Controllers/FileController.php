<?php

namespace Modules\File\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\File\Actions\DeleteFileAction;
use Modules\File\Actions\StoreFileAction;
use Modules\File\Actions\UpdateFileAction;
use Modules\File\Entities\File;
use Modules\File\Http\Requests\FileRequest;
use Modules\Section\Entities\Section;

class FileController extends Controller
{
    public function index()
    {

    }
    public function store(Section $section ,FileRequest $request, StoreFileAction $storeFileAction)
    {
        if ($section->teacher_id !== Auth::guard('teacher')->user()->id) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not allowed to take this action');
        }

        $file = $storeFileAction->execute($section, $request->validated());
        return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Your files uploaded successfully', ['fileId' => $file->id]);


    }


    public function show($id)
    {
        //
    }

    public function update(Section $section , File $file , FileRequest $request , UpdateFileAction $updateFileAction)
    {
        if ($file->teacher_id !== Auth::guard('teacher')->user()->id) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not allowed to take this action');
        }
        $file = $updateFileAction->execute($section, $file, $request->validated());

            return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED, 'Your files updated successfully', ['fileId' => $file->id]);

    }

    public function destroy(Section $section , File $file, DeleteFileAction $deleteFileAction)
    {

        if ($file->teacher_id !== Auth::guard('teacher')->user()->id) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_FORBIDDEN, 'Unauthorized: You do not allowed to take this action');
        }
        $deleteFileAction->execute($file);
        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'File deleted successfully');

    }
}
