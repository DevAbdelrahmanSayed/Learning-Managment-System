<?php

namespace Modules\Video\Http\Requests;

use App\Helpers\ApiValidationHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'visible' => 'required|boolean',
            'title' => 'required|string|max:255',
            'videoUrl' => 'required|file|mimes:mp4,avi,mov', // Assuming you accept MP4, AVI, and MOV video formats with a max size of 300 MB (adjust max size as needed).
            'section_id' => 'required|Integer|exists:sections,id',
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
