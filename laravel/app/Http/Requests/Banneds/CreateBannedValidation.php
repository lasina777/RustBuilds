<?php

namespace App\Http\Requests\Banneds;

use Illuminate\Foundation\Http\FormRequest;

class CreateBannedValidation extends FormRequest
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
            'period' => "required",
            'cause' => 'required'
        ];
    }
}
