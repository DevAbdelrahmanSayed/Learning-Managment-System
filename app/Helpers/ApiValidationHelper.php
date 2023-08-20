<?php

namespace App\Helpers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiValidationHelper
{
    public static function failedValidation(Validator $validator)
    {
        if (request()->is('api/*')) {
            $response = ApiResponse::sendResponse(422, 'Validation Errors', $validator->errors());
            throw new HttpResponseException($response);
        }
    }
}
