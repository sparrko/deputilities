<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Authorize extends FormRequest
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
            'login' => 'required|exists:users,login|min:6|max:255',
            'password' => 'required|min:6|max:255'
        ];
    }

    public function messages()
    {
        return [
            // 'login.required' => 'Поле логина пустое',
            // 'login.exists' => 'Аккаунта с таким логином не существует',
            // 'login.min' => 'Логин не может быть меньше 6 символов',
            // 'login.max' => 'Логин не может быть больше 255 символов',

            // 'password.required' => 'Поле пароля пустое',
            // 'password.min' => 'Пароль не может быть меньше 6 символов',
            // 'password.max' => 'Пароль не может быть больше 255 символов'
        ];
    }
}
