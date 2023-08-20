<?php

namespace Modules\Video\Http\Requests;

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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'videoUrl' => 'required|file|mimes:mp4,avi,mov|max:300000', // Assuming you accept MP4, AVI, and MOV video formats with a max size of 300 MB (adjust max size as needed).
            'section_id' => 'required|Integer|exists:sections,id',
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
