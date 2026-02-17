<?php

test('guests are redirected to login when visiting home', function () {
    $response = $this->get(route('home'));

    $response->assertRedirect(route('login'));
});

test('authenticated users are redirected to patients when visiting home', function () {
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get(route('home'));

    $response->assertRedirect(route('patients.index'));
});
