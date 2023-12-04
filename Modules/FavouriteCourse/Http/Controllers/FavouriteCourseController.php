<?php

namespace Modules\FavouriteCourse\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Transformers\CourseResource;
use Modules\FavouriteCourse\Http\Requests\StoreFavouriteCourseRequest;
use Spatie\FlareClient\Api;

class FavouriteCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $studentFavouriteCourses = auth('student')->user()->favouriteCourses;

        if (count($studentFavouriteCourses) == 0) {
            return ApiResponse::sendResponse(JsonResponse::HTTP_NOT_FOUND, 'No favourite courses found ');
        }
        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Favourite courses retrieved successfully.', CourseResource::collection($studentFavouriteCourses));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(StoreFavouriteCourseRequest $request)
    {
        $courseId = $request->get('course_id');

        auth('student')->user()->favouriteCourses()->sync([$courseId => [
            'updated_at' => now(),
            'created_at' => now(),
        ]]);;

        return ApiResponse::sendResponse(JsonResponse::HTTP_CREATED , 'Course added to favourites succefully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        auth('student')->user()->favouriteCourses()->detach($id);

        return ApiResponse::sendResponse(JsonResponse::HTTP_OK, 'Course removed successfully from favourites');
    }
}
