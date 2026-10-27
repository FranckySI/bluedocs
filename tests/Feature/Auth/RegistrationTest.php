<?php

use App\Models\User;

test('only administrators can open the registration screen', function () {
    $editor = User::factory()->create(['role' => 'editeur']);

    $this->actingAs($editor)
        ->get('/register')
        ->assertForbidden();

    $administrator = User::factory()->create(['role' => 'admin']);

    $this->actingAs($administrator)
        ->get('/register')
        ->assertOk()
        ->assertSee('value="editeur"', false)
        ->assertDontSee('value="user"', false);
});

test('administrators can create editor accounts with the editeur role', function () {
    $administrator = User::factory()->create(['role' => 'admin']);

    $response = $this->actingAs($administrator)->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'role' => 'editeur',
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));
    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'role' => 'editeur',
    ]);
});

test('registration rejects the legacy user role', function () {
    $administrator = User::factory()->create(['role' => 'admin']);

    $this->actingAs($administrator)
        ->post('/register', [
            'name' => 'Legacy User',
            'email' => 'legacy@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'user',
        ])
        ->assertSessionHasErrors('role');

    $this->assertDatabaseMissing('users', ['email' => 'legacy@example.com']);
});
