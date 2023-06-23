<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateValidation extends FormRequest
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
            'name' => 'nullable|regex:/^[а-яА-ЯеЁ]+$/u',
            'surname' => 'nullable|regex:/^[а-яА-ЯеЁ]+$/u',
            'patronymic' => 'nullable|regex:/^[а-яА-ЯеЁ]+$/u',
            'email' => 'required|email|unique:users,email,'.$this->user->id,
            'login' => 'required|unique:users,login,'.$this->user->id,
            'link_steam' => 'nullable',
            'passwordReal' => 'required',
        ];
    }
}
