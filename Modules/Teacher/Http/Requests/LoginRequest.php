<?php

namespace Modules\Teacher\Http\Requests;

use App\Helpers\ApiValidationHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|max:255',
            'password' => ['required', 'max:255', Password::defaults()],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        ApiValidationHelper::failedValidation( $validator);
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
