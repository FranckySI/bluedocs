<?php

use App\Models\User;
use Illuminate\Support\Facades\Log;

it('lists editor accounts for administrators without listing administrators', function () {
    $administrator = User::factory()->create(['role' => 'admin']);
    $editor = User::factory()->create(['name' => 'Editor Account']);

    $response = $this->actingAs($administrator)
        ->get(route('users.index'))
        ->assertOk()
        ->assertSee('Editor Account');

    expect($response->getContent())
        ->toContain('/users/'.$editor->id.'/status')
        ->not->toContain('/users/'.$administrator->id.'/status');
});

it('lets administrators deactivate and reactivate editor accounts with an audit log', function () {
    $administrator = User::factory()->create(['role' => 'admin']);
    $editor = User::factory()->create();
    Log::spy();

    $this->actingAs($administrator)
        ->patch(route('users.update-status', $editor), ['is_active' => false])
        ->assertRedirect(route('users.index'));

    $this->assertDatabaseHas('users', [
        'id' => $editor->id,
        'is_active' => false,
    ]);

    Log::shouldHaveReceived('info')
        ->once()
        ->with('Editor account status changed.', [
            'user_id' => $editor->id,
            'actor_id' => $administrator->id,
            'is_active' => false,
        ]);

    $this->patch(route('users.update-status', $editor), ['is_active' => true])
        ->assertRedirect(route('users.index'));

    $this->assertDatabaseHas('users', [
        'id' => $editor->id,
        'is_active' => true,
    ]);
});

it('does not allow administrators to deactivate administrator accounts', function () {
    $administrator = User::factory()->create(['role' => 'admin']);
    $anotherAdministrator = User::factory()->create(['role' => 'admin']);

    $this->actingAs($administrator)
        ->patch(route('users.update-status', $anotherAdministrator), ['is_active' => false])
        ->assertNotFound();

    $this->assertDatabaseHas('users', [
        'id' => $anotherAdministrator->id,
        'is_active' => true,
    ]);
});

it('forbids editors from managing user accounts', function () {
    $editor = User::factory()->create();

    $this->get(route('users.index'))->assertRedirect(route('login'));

    $this->actingAs($editor)
        ->get(route('users.index'))
        ->assertForbidden();
});

it('validates the requested account status', function () {
    $administrator = User::factory()->create(['role' => 'admin']);
    $editor = User::factory()->create();

    $this->actingAs($administrator)
        ->patch(route('users.update-status', $editor), ['is_active' => 'not-a-boolean'])
        ->assertSessionHasErrors('is_active');

    $this->assertDatabaseHas('users', [
        'id' => $editor->id,
        'is_active' => true,
    ]);
});
