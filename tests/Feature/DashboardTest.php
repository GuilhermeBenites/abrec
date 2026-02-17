<?php

test('dashboard route no longer exists and returns 404', function () {
    $response = $this->get('/dashboard');

    $response->assertNotFound();
});
