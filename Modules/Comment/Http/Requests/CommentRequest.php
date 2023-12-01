<?php

namespace Modules\Comment\Http\Requests;

use App\Helpers\ApiValidationHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'course_id' => 'required|integer',
            'comment_text' => 'required|string',
            'parent_comment_id' => 'nullable|integer',
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
