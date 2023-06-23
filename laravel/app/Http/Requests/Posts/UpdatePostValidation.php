<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostValidation extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|max:40',
            'photo' => 'file|image|nullable|max:2048',
            'fortify' => 'file|nullable|max:2048',
            'headers' => 'array|required',
            'photos' => 'array|nullable',
            'current_imagePost' => 'array|nullable',
            'current_imagePost.*' => 'required',
            'informations' => 'array|required',
            'headers.*' => 'required',
            'photos.*' => 'file|image|nullable|max:2048',
            'informations.*' => 'required',
            'hashtags' => 'array|nullable',
            'hashtags.*' => 'nullable|max:15|regex:/^[А-Яа-яЁёA-Za-z]+$/u',
        ];
    }
}
