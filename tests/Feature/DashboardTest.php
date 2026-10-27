<?php

use App\Models\User;

it('redirects visitors to login when they open the dashboard', function () {
    $this->get(route('dashboard'))
        ->assertRedirect(route('login'));
});

it('allows authenticated editors and administrators to open the dashboard', function (string $role) {
    $user = User::factory()->create([
        'name' => 'BlueDocs '.$role,
        'role' => $role,
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('BlueDocs '.$role);
})->with(['editeur', 'admin']);
