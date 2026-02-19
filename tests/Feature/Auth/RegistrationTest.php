<?php

test('registration route is not available when registration is disabled', function () {
    $response = $this->get('/register');

    $response->assertNotFound();
});
