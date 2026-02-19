<?php

namespace App\Http\Requests;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    use PasswordValidationRules, ProfileValidationRules;

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
        /** @var User $user */
        $user = $this->route('user');

        $rules = [
            'name' => $this->nameRules(),
            'email' => $this->emailRules($user->id),
            'role' => [
                'required',
                'string',
                Rule::exists('roles', 'name')->where('guard_name', 'web'),
            ],
        ];

        if ($this->filled('password')) {
            $rules['password'] = $this->passwordRules();
        }

        return $rules;
    }

    /**
     * Get custom error messages for validator errors (Brazilian Portuguese).
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser um endereço válido.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'role.required' => 'O campo perfil é obrigatório.',
            'role.exists' => 'O perfil selecionado é inválido.',
        ];
    }
}
