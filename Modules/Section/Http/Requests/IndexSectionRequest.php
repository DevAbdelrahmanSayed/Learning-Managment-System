<?php

namespace Modules\Section\Http\Requests;

use App\Helpers\ApiValidationHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class IndexSectionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'teacher_id' => 'numeric|exists:teachers,id',
            'course_id' => 'numeric|exists:courses,id',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        ApiValidationHelper::failedValidation($validator);
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
