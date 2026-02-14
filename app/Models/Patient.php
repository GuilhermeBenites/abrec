<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'cpf',
        'date_of_birth',
        'gender',
        'address',
        'neighborhood',
        'city',
        'weight',
        'height',
        'blood_pressure',
        'blood_glucose',
        'creatinine',
        'is_diabetic',
        'is_hypertensive',
        'has_kidney_problem',
        'has_family_drc',
        'is_obese',
        'has_back_eye_exam',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'weight' => 'decimal:2',
            'is_diabetic' => 'boolean',
            'is_hypertensive' => 'boolean',
            'has_kidney_problem' => 'boolean',
            'has_family_drc' => 'boolean',
            'is_obese' => 'boolean',
            'has_back_eye_exam' => 'boolean',
        ];
    }
}
