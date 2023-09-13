<?php

namespace Modules\Teacher\Http\Requests;

use App\Helpers\ApiValidationHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateTeacherRequest extends FormRequest
{

    protected function failedValidation(Validator $validator)
    {
        ApiValidationHelper::failedValidation($validator);
    }
    public function rules(Request $request)
    {
        return [
            'name' => ['string', 'min:3', 'max:25'],
            'email' => ['email', Rule::unique('teachers', 'email')->ignore(auth()->guard('teacher')->user()->id)],
            'about' => ['string', 'max:255'],
            'profile' => ['max:255', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'password' => [Rule::requiredIf(function () use ($request) { return $request->has('old_password'); }), 'max:255', Password::defaults()],
            'old_password' => [Rule::requiredIf(function () use ($request) { return $request->has('password'); }), 'max:255'],
        ];
    }


    public function authorize()
    {
        return true;
    }
}
