<?php

use App\Models\Patient;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

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
        'status' => 'diabetic',
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
        'status' => 'hypertensive',
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
        'status' => 'renal',
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
        'status' => 'obesity',
    ]));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->where('patients.total', 1)
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

test('export generates excel file', function () {
    $user = User::factory()->create();
    Patient::factory()->create(['name' => 'Export Test']);

    $response = $this->actingAs($user)->get(route('patients.export'));

    $response->assertOk();
    $response->assertDownload();
    expect($response->headers->get('content-type'))->toContain('spreadsheet');
});

test('export respects filters', function () {
    $user = User::factory()->create();
    Patient::factory()->create(['name' => 'Included Patient', 'is_diabetic' => true]);
    Patient::factory()->create(['name' => 'Excluded Patient', 'is_diabetic' => false]);

    $response = $this->actingAs($user)->get(route('patients.export', [
        'status' => 'diabetic',
    ]));

    $response->assertOk();
    $response->assertDownload();
});

test('unauthenticated users are redirected when exporting', function () {
    $response = $this->get(route('patients.export'));

    $response->assertRedirect(route('login'));
});
