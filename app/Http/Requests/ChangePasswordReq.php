<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordReq extends FormRequest
{
    /**
     * Определяет, авторизован ли пользователь для выполнения этого запроса.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации данных запроса на смену пароля.
     */
    public function rules(): array
    {
        return [
            'current_password' => 'required|string|min:8',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',      // хотя бы одна строчная
                'regex:/[A-Z]/',      // хотя бы одна заглавная
                'regex:/[0-9]/',      // хотя бы одна цифра
                'regex:/[\W]/',       // хотя бы один спецсимвол
            ],
            'confirm_new_password' => 'required|same:new_password',
        ];
    }

    /**
     * Кастомные сообщения об ошибках.
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'Вы должны указать текущий пароль.',
            'new_password.required' => 'Вы должны указать новый пароль.',
            'new_password.regex' => 'Новый пароль должен содержать как минимум одну заглавную, строчную букву, цифру и специальный символ.',
            'confirm_new_password.same' => 'Пароли не совпадают.',
        ];
    }
}
