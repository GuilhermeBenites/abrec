<?php

use App\Models\Patient;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Inertia\Testing\AssertableInertia as Assert;

function createAdminUser(): User
{
    $user = User::factory()->create();
    $user->assignRole('admin');

    return $user;
}

test('unauthenticated users are redirected to login when visiting patients list', function () {
    $response = $this->get(route('patients.index'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can view the patients list page', function () {
    $user = User::factory()->create();
    Patient::factory()->count(3)->create();

    $response = $this->actingAs($user)->get(route('patients.index'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('patients/Index')
        ->has('patients')
        ->has('patients.data')
        ->has('filters')
        ->where('patients.total', 3)
    );
});

test('patients list can be filtered by name', function () {
    $user = User::factory()->create();
    Patient::factory()->create(['name' => 'João da Silva']);
    Patient::factory()->create(['name' => 'Maria Oliveira']);
    Patient::factory()->create(['name' => 'Carlos Silva']);

    $response = $this->actingAs($user)->get(route('patients.index', [
        'name' => 'Silva',
    ]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->where('patients.total', 2)
        ->where('patients.data.0.name', 'Carlos Silva')
        ->where('patients.data.1.name', 'João da Silva')
    );
});

test('patients list can be filtered by CPF', function () {
    $user = User::factory()->create();
    Patient::factory()->create(['cpf' => '12345678901']);
    Patient::factory()->create(['cpf' => '98765432100']);

    $response = $this->actingAs($user)->get(route('patients.index', [
        'cpf' => '12345678901',
    ]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->where('patients.total', 1)
        ->where('patients.data.0.cpf', '123.456.789-01')
    );
});

test('patients list can be filtered by diabetic status', function () {
    $user = User::factory()->create();
    Patient::factory()->create(['is_diabetic' => true]);
    Patient::factory()->create(['is_diabetic' => false]);

    $response = $this->actingAs($user)->get(route('patients.index', [
        'health_indicators' => ['diabetic'],
    ]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->where('patients.total', 1)
    );
});

test('patients list can be filtered by hypertensive status', function () {
    $user = User::factory()->create();
    Patient::factory()->create(['is_hypertensive' => true]);
    Patient::factory()->create(['is_hypertensive' => false]);

    $response = $this->actingAs($user)->get(route('patients.index', [
        'health_indicators' => ['hypertensive'],
    ]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->where('patients.total', 1)
    );
});

test('patients list can be filtered by kidney problem status', function () {
    $user = User::factory()->create();
    Patient::factory()->create(['has_kidney_problem' => true]);
    Patient::factory()->create(['has_kidney_problem' => false]);

    $response = $this->actingAs($user)->get(route('patients.index', [
        'health_indicators' => ['renal'],
    ]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->where('patients.total', 1)
    );
});

test('patients list can be filtered by obesity status', function () {
    $user = User::factory()->create();
    Patient::factory()->create(['is_obese' => true]);
    Patient::factory()->create(['is_obese' => false]);

    $response = $this->actingAs($user)->get(route('patients.index', [
        'health_indicators' => ['obesity'],
    ]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->where('patients.total', 1)
    );
});

test('patients list can be filtered by multiple health indicators with OR logic', function () {
    $user = User::factory()->create();
    Patient::factory()->create(['is_diabetic' => true, 'is_hypertensive' => false]);
    Patient::factory()->create(['is_diabetic' => false, 'is_hypertensive' => true]);
    Patient::factory()->create(['is_diabetic' => false, 'is_hypertensive' => false]);

    $response = $this->actingAs($user)->get(route('patients.index', [
        'health_indicators' => ['diabetic', 'hypertensive'],
    ]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->where('patients.total', 2)
    );
});

test('pagination works correctly', function () {
    $user = User::factory()->create();
    Patient::factory()->count(20)->create();

    $response = $this->actingAs($user)->get(route('patients.index', ['page' => 2]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->where('patients.current_page', 2)
        ->where('patients.per_page', 15)
        ->where('patients.total', 20)
    );
});

test('admin users can export patient list as excel file', function () {
    $this->seed(RolesSeeder::class);
    $admin = createAdminUser();
    Patient::factory()->create(['name' => 'Export Test']);

    $response = $this->actingAs($admin)->get(route('patients.export'));

    $response->assertOk();
    $response->assertDownload();
    expect($response->headers->get('content-type'))->toContain('spreadsheet');
});

test('admin users can export patient list with filters', function () {
    $this->seed(RolesSeeder::class);
    $admin = createAdminUser();
    Patient::factory()->create(['name' => 'Included Patient', 'is_diabetic' => true]);
    Patient::factory()->create(['name' => 'Excluded Patient', 'is_diabetic' => false]);

    $response = $this->actingAs($admin)->get(route('patients.export', [
        'health_indicators' => ['diabetic'],
    ]));

    $response->assertOk();
    $response->assertDownload();
});

test('non-admin users receive 403 when exporting patient list', function () {
    $this->seed(RolesSeeder::class);
    $user = User::factory()->create();
    $user->assignRole('user');
    Patient::factory()->create(['name' => 'Some Patient']);

    $response = $this->actingAs($user)->get(route('patients.export'));

    $response->assertForbidden();
});

test('unauthenticated users are redirected when exporting', function () {
    $response = $this->get(route('patients.export'));

    $response->assertRedirect(route('login'));
});

test('unauthenticated users are redirected when visiting edit page', function () {
    $patient = Patient::factory()->create();

    $response = $this->get(route('patients.edit', $patient));

    $response->assertRedirect(route('login'));
});

test('unauthenticated users are redirected when updating a patient', function () {
    $patient = Patient::factory()->create();

    $response = $this->put(route('patients.update', $patient), [
        'name' => $patient->name,
        'cpf' => $patient->cpf,
        'date_of_birth' => $patient->date_of_birth->format('Y-m-d'),
        'gender' => $patient->gender,
        'address' => $patient->address,
        'neighborhood' => $patient->neighborhood,
        'city' => $patient->city,
    ]);

    $response->assertRedirect(route('login'));
});

test('unauthenticated users are redirected when deleting a patient', function () {
    $patient = Patient::factory()->create();

    $response = $this->delete(route('patients.destroy', $patient));

    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the edit page and it renders with patient data', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'name' => 'Maria Santos',
        'cpf' => '11122233344',
    ]);

    $response = $this->actingAs($user)->get(route('patients.edit', $patient));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('patients/Edit')
        ->has('patient')
        ->where('patient.id', $patient->id)
        ->where('patient.name', 'Maria Santos')
        ->where('patient.cpf', '111.222.333-44')
    );
});

test('authenticated users can update a patient', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create([
        'name' => 'Old Name',
        'city' => 'Old City',
    ]);

    $response = $this->actingAs($user)->put(route('patients.update', $patient), [
        'name' => 'Updated Name',
        'cpf' => preg_replace('/\D/', '', $patient->cpf),
        'date_of_birth' => $patient->date_of_birth->format('Y-m-d'),
        'gender' => $patient->gender,
        'address' => $patient->address,
        'neighborhood' => $patient->neighborhood,
        'city' => 'Updated City',
    ]);

    $response->assertRedirect(route('patients.index'));
    $response->assertSessionHas('success', 'Paciente atualizado com sucesso.');

    $patient->refresh();
    expect($patient->name)->toBe('Updated Name');
    expect($patient->city)->toBe('Updated City');
});

test('authenticated users can delete a patient', function () {
    $user = User::factory()->create();
    $patient = Patient::factory()->create(['name' => 'To Be Deleted']);

    $response = $this->actingAs($user)->delete(route('patients.destroy', $patient));

    $response->assertRedirect(route('patients.index'));
    $response->assertSessionHas('success', 'Paciente excluído com sucesso.');

    expect(Patient::find($patient->id))->toBeNull();
});
