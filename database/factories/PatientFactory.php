<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    protected $model = Patient::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['male', 'female']);
        $birthDate = fake()->dateTimeBetween('-80 years', '-18 years');

        return [
            'name' => fake()->name(),
            'cpf' => fake()->unique()->numerify('###########'),
            'date_of_birth' => $birthDate,
            'gender' => $gender,
            'address' => fake()->streetAddress(),
            'neighborhood' => fake()->randomElement(['Centro', 'Jardim Paulista', 'Vila Nova', 'Alto da Boa Vista', 'Bela Vista', 'Perdizes']),
            'city' => fake()->city(),
            'weight' => fake()->optional(0.7)->randomFloat(2, 45, 150),
            'height' => fake()->optional(0.7)->numberBetween(150, 210),
            'blood_pressure' => fake()->optional(0.6)->numerify('##/##'),
            'blood_glucose' => fake()->optional(0.6)->numberBetween(70, 200),
            'creatinine' => fake()->optional(0.6)->numerify('#.# mg/dL'),
            'is_diabetic' => fake()->boolean(20),
            'is_hypertensive' => fake()->boolean(25),
            'has_kidney_problem' => fake()->boolean(10),
            'has_family_drc' => fake()->boolean(15),
            'is_obese' => fake()->boolean(20),
            'has_back_eye_exam' => fake()->boolean(30),
        ];
    }
}
