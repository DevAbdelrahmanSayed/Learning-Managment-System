<?php

namespace Modules\Teacher\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Transformers\TeacherResource;
use Modules\Teacher\Http\Requests\UpdateTeacherRequest;

class TeacherController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(UpdateTeacherRequest $request)
    {
        if (! Hash::check($request->old_password, auth()->user()->password)) {
            return ApiResponse::sendResponse(401, 'The old password does not match .', null);
        }

        Auth::user()->update(
            $request->validated() + ['password' => Hash::make($request->password)]
        );

        return ApiResponse::sendResponse(200, 'User\'s data updated successfully .', new TeacherResource(Auth::user()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
