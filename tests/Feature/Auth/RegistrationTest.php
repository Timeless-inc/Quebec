<?php

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'username' => 'test.user',
        'name' => 'Test User',
        'email' => 'test@example.com',
        'matricula' => '2025IFPEI0001',
        'rg' => '52998224725',
        'cpf' => '529.982.247-25',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('new users can register with legacy rg and uf', function () {
    $response = $this->post('/register', [
        'username' => 'legacy.user',
        'name' => 'Legacy User',
        'email' => 'legacy@example.com',
        'matricula' => '2025IFPEI0002',
        'rg_uf' => 'SP',
        'rg' => 'ABC1234',
        'cpf' => '111.444.777-35',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});
