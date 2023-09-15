<?php

namespace Modules\Course\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatecourseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'string|max:20',
            'description' => 'string|max:255',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'exists:categories,id',
            'price' => 'string|max:10',
        ];
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
