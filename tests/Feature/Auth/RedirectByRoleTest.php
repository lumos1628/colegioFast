<?php

use App\Enums\UserRole;
use App\Models\User;

it('redirects admin to admin dashboard', function () {
    $user = User::factory()->create(['role' => UserRole::Admin]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('admin', absolute: false));
});

it('redirects director to director dashboard', function () {
    $user = User::factory()->create(['role' => UserRole::Director]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('director', absolute: false));
});

it('redirects secretaria to secretaria dashboard', function () {
    $user = User::factory()->create(['role' => UserRole::Secretaria]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('secretaria', absolute: false));
});

it('redirects docente to docente dashboard', function () {
    $user = User::factory()->create(['role' => UserRole::Docente]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('docente.dashboard', absolute: false));
});

it('redirects alumno to alumno dashboard', function () {
    $user = User::factory()->create(['role' => UserRole::Alumno]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('alumno.dashboard', absolute: false));
});

it('redirects padre to padre dashboard', function () {
    $user = User::factory()->create(['role' => UserRole::Padre]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('padre.dashboard', absolute: false));
});

it('redirects psicologo to psicologo dashboard', function () {
    $user = User::factory()->create(['role' => UserRole::Psicologo]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('psicologo.dashboard', absolute: false));
});
