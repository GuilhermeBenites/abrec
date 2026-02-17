<?php

use App\Models\Patient;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('unauthenticated users are redirected to login when visiting patient create page', function () {
    $response = $this->get(route('patients.create'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can view the patient registration form', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('patients.create'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('patients/Create')
    );
});

test('valid patient data can be stored', function () {
    $user = User::factory()->create();

    $patientData = [
        'name' => 'João da Silva',
        'cpf' => '12345678901',
        'date_of_birth' => '1990-05-15',
        'gender' => 'male',
        'address' => 'Rua das Flores, 123',
        'neighborhood' => 'Centro',
        'city' => 'São Paulo',
    ];

    $response = $this
        ->actingAs($user)
        ->from(route('patients.create'))
        ->post(route('patients.store'), $patientData);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('patients.index'));
    $response->assertSessionHas('success', 'Paciente cadastrado com sucesso.');

    $this->assertDatabaseHas('patients', [
        'name' => 'João da Silva',
        'cpf' => '12345678901',
        'gender' => 'male',
        'city' => 'São Paulo',
    ]);
});

test('patient with optional health data can be stored', function () {
    $user = User::factory()->create();

    $patientData = [
        'name' => 'Maria Santos',
        'cpf' => '98765432100',
        'date_of_birth' => '1985-03-20',
        'gender' => 'female',
        'address' => 'Av. Brasil, 456',
        'neighborhood' => 'Jardins',
        'city' => 'Rio de Janeiro',
        'weight' => 65.5,
        'height' => 165,
        'blood_pressure' => '120/80',
        'blood_glucose' => 95,
        'creatinine' => '1.0 mg/dL',
        'is_diabetic' => true,
        'is_hypertensive' => false,
    ];

    $response = $this
        ->actingAs($user)
        ->post(route('patients.store'), $patientData);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('patients.index'));

    $patient = Patient::query()->where('cpf', '98765432100')->first();
    expect($patient)->not->toBeNull();
    expect($patient->name)->toBe('Maria Santos');
    expect($patient->weight)->toBe('65.50');
    expect($patient->height)->toBe(165);
    expect($patient->is_diabetic)->toBeTrue();
    expect($patient->is_hypertensive)->toBeFalse();
});

test('validation fails when required personal data fields are missing', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('patients.create'))
        ->post(route('patients.store'), []);

    $response
        ->assertSessionHasErrors(['name', 'cpf', 'date_of_birth', 'gender', 'address', 'neighborhood', 'city'])
        ->assertRedirect(route('patients.create'));
});

test('validation fails for duplicate CPF', function () {
    $user = User::factory()->create();
    Patient::factory()->create(['cpf' => '11122233344']);

    $patientData = [
        'name' => 'Test User',
        'cpf' => '11122233344',
        'date_of_birth' => '1990-01-01',
        'gender' => 'male',
        'address' => 'Rua Teste, 1',
        'neighborhood' => 'Centro',
        'city' => 'São Paulo',
    ];

    $response = $this
        ->actingAs($user)
        ->from(route('patients.create'))
        ->post(route('patients.store'), $patientData);

    $response
        ->assertSessionHasErrors('cpf')
        ->assertRedirect(route('patients.create'));
});

test('validation fails for invalid date of birth in the future', function () {
    $user = User::factory()->create();

    $patientData = [
        'name' => 'Test User',
        'cpf' => '11122233344',
        'date_of_birth' => now()->addYear()->format('Y-m-d'),
        'gender' => 'male',
        'address' => 'Rua Teste, 1',
        'neighborhood' => 'Centro',
        'city' => 'São Paulo',
    ];

    $response = $this
        ->actingAs($user)
        ->from(route('patients.create'))
        ->post(route('patients.store'), $patientData);

    $response->assertSessionHasErrors('date_of_birth');
});

test('validation fails for invalid gender', function () {
    $user = User::factory()->create();

    $patientData = [
        'name' => 'Test User',
        'cpf' => '11122233344',
        'date_of_birth' => '1990-01-01',
        'gender' => 'invalid',
        'address' => 'Rua Teste, 1',
        'neighborhood' => 'Centro',
        'city' => 'São Paulo',
    ];

    $response = $this
        ->actingAs($user)
        ->from(route('patients.create'))
        ->post(route('patients.store'), $patientData);

    $response->assertSessionHasErrors('gender');
});
