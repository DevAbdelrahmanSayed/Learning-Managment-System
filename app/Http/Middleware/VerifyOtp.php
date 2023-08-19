<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyOtp
{

    public function handle(Request $request, Closure $next, $guard)
    {
        $user = Auth::guard($guard)->user();

        if ($user && $user->email_verified_at !== null) {
            return $next($request);
        }

        return ApiResponse::sendResponse(403, 'Unauthorized: Please verify your OTP first', []);
    }
    }
