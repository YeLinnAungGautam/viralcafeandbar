<?php

namespace App\Http\Requests\Api;

use App\Rules\CredentialUnique;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'username'   => 'nullable|regex:/^[a-zA-Z][a-zA-Z0-9_-]{2,14}$/|unique:customers,username',
            'password'   => 'required|min:8|confirmed',
            'credential' => 'required',
            'is_oversea' => 'required|boolean',
            'fcm_token'  => 'required',
        ];

        if (!is_numeric($this->credential)) {
            $rules['credential'] = 'email';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'username.regex' => 'The username must start with a letter, be 3 to 15 characters long, and can only contain letters, numbers, underscores, and hyphens.',
            'password.min'   => 'Your password is incorrect.',
        ];
    }
}
