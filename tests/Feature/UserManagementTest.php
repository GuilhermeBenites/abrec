<?php

use App\Models\User;
use Database\Seeders\RolesSeeder;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(RolesSeeder::class);
});

function createAdmin(): User
{
    $user = User::factory()->create();
    $user->assignRole('admin');

    return $user;
}

function createRegularUser(): User
{
    $user = User::factory()->create();
    $user->assignRole('user');

    return $user;
}

test('unauthenticated users are redirected to login when visiting users index', function () {
    $response = $this->get(route('users.index'));

    $response->assertRedirect(route('login'));
});

test('non-admin users receive 403 when visiting users index', function () {
    $user = createRegularUser();

    $response = $this->actingAs($user)->get(route('users.index'));

    $response->assertForbidden();
});

test('admin users can view the users list page', function () {
    $admin = createAdmin();
    User::factory()->count(2)->create();

    $response = $this->actingAs($admin)->get(route('users.index'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('users/Index')
        ->has('users')
        ->has('users.data')
    );
});

test('admin users can visit the create user page', function () {
    $admin = createAdmin();

    $response = $this->actingAs($admin)->get(route('users.create'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('users/Create')
        ->has('roles')
    );
});

test('admin users can create a new user', function () {
    $admin = createAdmin();

    $response = $this->actingAs($admin)->post(route('users.store'), [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'user',
    ]);

    $response->assertRedirect(route('users.index'));
    $response->assertSessionHas('success', 'Usuário cadastrado com sucesso.');

    $user = User::query()->where('email', 'newuser@example.com')->first();
    expect($user)->not->toBeNull()
        ->and($user->name)->toBe('New User')
        ->and($user->hasRole('user'))->toBeTrue();
});

test('admin users can visit the edit user page', function () {
    $admin = createAdmin();
    $targetUser = User::factory()->create();
    $targetUser->assignRole('user');

    $response = $this->actingAs($admin)->get(route('users.edit', $targetUser));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('users/Edit')
        ->has('user')
        ->has('roles')
        ->where('user.id', $targetUser->id)
    );
});

test('admin users can update a user', function () {
    $admin = createAdmin();
    $targetUser = User::factory()->create(['name' => 'Old Name', 'email' => 'old@example.com']);
    $targetUser->assignRole('user');

    $response = $this->actingAs($admin)->put(route('users.update', $targetUser), [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'role' => 'admin',
    ]);

    $response->assertRedirect(route('users.index'));
    $response->assertSessionHas('success', 'Usuário atualizado com sucesso.');

    $targetUser->refresh();
    expect($targetUser->name)->toBe('Updated Name')
        ->and($targetUser->email)->toBe('updated@example.com')
        ->and($targetUser->hasRole('admin'))->toBeTrue();
});

test('admin users can delete another user', function () {
    $admin = createAdmin();
    $targetUser = User::factory()->create();
    $targetUser->assignRole('user');
    $targetUserId = $targetUser->id;

    $response = $this->actingAs($admin)->delete(route('users.destroy', $targetUser));

    $response->assertRedirect(route('users.index'));
    $response->assertSessionHas('success', 'Usuário excluído com sucesso.');

    expect(User::find($targetUserId))->toBeNull();
});

test('admin cannot delete their own account', function () {
    $admin = createAdmin();

    $response = $this->actingAs($admin)->delete(route('users.destroy', $admin));

    $response->assertRedirect(route('users.index'));
    $response->assertSessionHas('error', 'Você não pode excluir sua própria conta.');

    expect(User::find($admin->id))->not->toBeNull();
});

test('non-admin users receive 403 when creating a user', function () {
    $user = createRegularUser();

    $response = $this->actingAs($user)->post(route('users.store'), [
        'name' => 'New User',
        'email' => 'newuser@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'user',
    ]);

    $response->assertForbidden();
});

test('non-admin users receive 403 when updating a user', function () {
    $user = createRegularUser();
    $targetUser = User::factory()->create();
    $targetUser->assignRole('user');

    $response = $this->actingAs($user)->put(route('users.update', $targetUser), [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'role' => 'user',
    ]);

    $response->assertForbidden();
});

test('non-admin users receive 403 when deleting a user', function () {
    $user = createRegularUser();
    $targetUser = User::factory()->create();

    $response = $this->actingAs($user)->delete(route('users.destroy', $targetUser));

    $response->assertForbidden();
});
