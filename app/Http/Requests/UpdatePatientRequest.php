<?php

namespace App\Http\Requests;

use App\Models\Patient;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePatientRequest extends FormRequest
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
        /** @var Patient $patient */
        $patient = $this->route('patient');

        return [
            'name' => ['required', 'string', 'max:255'],
            'cpf' => [
                'required',
                'string',
                'regex:/^\d{11}$/',
                Rule::unique(Patient::class)->ignore($patient),
            ],
            'date_of_birth' => ['required', 'date', 'before:today', 'after:-150 years'],
            'gender' => ['required', 'in:male,female'],
            'address' => ['required', 'string', 'max:255'],
            'neighborhood' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'weight' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
            'height' => ['nullable', 'integer', 'min:1', 'max:300'],
            'blood_pressure' => ['nullable', 'string', 'max:10', 'regex:/^\d{1,3}\/\d{1,3}$/'],
            'blood_glucose' => ['nullable', 'integer', 'min:0', 'max:999'],
            'creatinine' => ['nullable', 'string', 'max:10'],
            'is_diabetic' => ['nullable', 'boolean'],
            'is_hypertensive' => ['nullable', 'boolean'],
            'has_kidney_problem' => ['nullable', 'boolean'],
            'has_family_drc' => ['nullable', 'boolean'],
            'is_obese' => ['nullable', 'boolean'],
            'has_back_eye_exam' => ['nullable', 'boolean'],
        ];
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
            'cpf.required' => 'O campo CPF é obrigatório.',
            'cpf.regex' => 'O CPF deve conter 11 dígitos (com ou sem formatação).',
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'date_of_birth.required' => 'O campo data de nascimento é obrigatório.',
            'date_of_birth.date' => 'A data de nascimento deve ser uma data válida.',
            'date_of_birth.before' => 'A data de nascimento deve ser anterior a hoje.',
            'date_of_birth.after' => 'A data de nascimento deve ser válida.',
            'gender.required' => 'O campo sexo é obrigatório.',
            'gender.in' => 'O sexo deve ser masculino ou feminino.',
            'address.required' => 'O campo endereço é obrigatório.',
            'address.max' => 'O endereço não pode ter mais de 255 caracteres.',
            'neighborhood.required' => 'O campo bairro é obrigatório.',
            'neighborhood.max' => 'O bairro não pode ter mais de 100 caracteres.',
            'city.required' => 'O campo cidade é obrigatório.',
            'city.max' => 'A cidade não pode ter mais de 100 caracteres.',
            'weight.numeric' => 'O peso deve ser um número válido.',
            'weight.min' => 'O peso deve ser maior que zero.',
            'weight.max' => 'O peso não pode ser maior que 999,99 kg.',
            'height.integer' => 'A altura deve ser um número inteiro.',
            'height.min' => 'A altura deve ser maior que zero.',
            'height.max' => 'A altura não pode ser maior que 300 cm.',
            'blood_pressure.regex' => 'A pressão arterial deve estar no formato 12/8.',
            'blood_glucose.integer' => 'A glicemia deve ser um número inteiro.',
            'blood_glucose.min' => 'A glicemia não pode ser negativa.',
            'blood_glucose.max' => 'A glicemia não pode ser maior que 999.',
        ];
    }

    /**
     * Prepare the data for validation - normalize CPF to digits for uniqueness check.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('cpf')) {
            $this->merge([
                'cpf' => preg_replace('/\D/', '', $this->cpf) ?: $this->cpf,
            ]);
        }
    }
}
