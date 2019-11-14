<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;

class SaveFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'formFiles' => 'required|array',
            'formFiles.*.name' => 'required|string',
            'formFiles.*.title' => 'required|string',
            'formFiles.*.originalName' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'A title is required',
            'formFiles.required'  => 'Files not added to form',
        ];
    }
}
