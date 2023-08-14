<?php

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Auth\Http\Requests\LoginRequest;

class SessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:teacher', ['except' => ['login']]);
    }

    public function __invoke(LoginRequest $request)
    {
        return ($request->type === 'teacher') ? $this->storeTeacherSession($request) : $this->storeTeacherSession($request);
    }

    public function storeStudentSession(Request $request)
    {
        dd('Student login');
    }

    public function storeTeacherSession(Request $request)
    {
        dd('Teacher login');
    }


    public function destroy($id)
    {
        //
    }
}
