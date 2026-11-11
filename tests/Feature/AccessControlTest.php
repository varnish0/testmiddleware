<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('lets anyone visit the public pages', function (string $path) {
    $this->get($path)->assertOk();
})->with(['/', '/login', '/register']);

it('redirects guests from private zones to login', function (string $path) {
    $this->get($path)->assertRedirect('/login');
})->with(['/panel', '/casino', '/admin']);

it('shows the user panel to an authenticated user', function () {
    $user = User::factory()->minor()->create();

    $this->actingAs($user)->get('/panel')->assertOk()->assertSee($user->name);
});

it('lets adults into the casino', function () {
    $this->actingAs(User::factory()->create(['age' => 18]))->get('/casino')->assertOk();
});

it('sends minors from the casino back to their panel with a message', function () {
    $this->actingAs(User::factory()->minor()->create())
        ->get('/casino')
        ->assertRedirect('/panel')
        ->assertSessionHas('error', 'Acceso denegado: Debes ser mayor de edad');
});

it('lets admins into the admin office', function () {
    $this->actingAs(User::factory()->admin()->create())->get('/admin')->assertOk();
});

it('forbids non-admins from the admin office', function () {
    $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
});

it('logs in with valid credentials', function () {
    $user = User::factory()->create();

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect('/panel');

    $this->assertAuthenticatedAs($user);
});

it('rejects invalid credentials', function () {
    $user = User::factory()->create();

    $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('registers a new non-admin user', function () {
    $this->post('/register', [
        'name' => 'Nuevo Huésped',
        'email' => 'nuevo@resort.test',
        'age' => 20,
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
        'is_admin' => true,
    ])->assertRedirect('/panel');

    $user = User::where('email', 'nuevo@resort.test')->firstOrFail();

    expect($user->is_admin)->toBeFalse()
        ->and($user->age)->toBe(20);
    $this->assertAuthenticatedAs($user);
});

it('logs out', function () {
    $this->actingAs(User::factory()->create())->post('/logout')->assertRedirect('/');

    $this->assertGuest();
});

it('seeds the three test users', function () {
    $this->seed();

    expect(User::where('is_admin', true)->where('age', '>=', 18)->exists())->toBeTrue()
        ->and(User::where('is_admin', false)->where('age', '>=', 18)->exists())->toBeTrue()
        ->and(User::where('is_admin', false)->where('age', '<', 18)->exists())->toBeTrue();
});
