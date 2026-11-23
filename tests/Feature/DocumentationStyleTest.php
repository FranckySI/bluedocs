<?php

use App\Models\User;

it('lets administrators update the documentation style', function () {
    $administrator = User::factory()->create(['role' => 'admin']);

    $this->actingAs($administrator)
        ->put(route('admin.documentation-style.update'), [
            'documentation_font_family' => 'serif',
            'documentation_font_size' => 18,
            'documentation_text_color' => '#123456',
            'documentation_heading_color' => '#654321',
            'documentation_background_color' => '#fefefe',
            'documentation_line_height' => 1.8,
        ])
        ->assertRedirect(route('admin.documentation-style.edit'))
        ->assertSessionHas('status', 'documentation-style-updated');

    $this->assertDatabaseHas('settings', [
        'key' => 'documentation_font_family',
        'value' => 'serif',
    ]);
    $this->assertDatabaseHas('settings', [
        'key' => 'documentation_font_size',
        'value' => '18',
    ]);
});

it('forbids editors from updating the documentation style', function () {
    $editor = User::factory()->create();

    $this->actingAs($editor)
        ->put(route('admin.documentation-style.update'), [
            'documentation_font_family' => 'serif',
            'documentation_font_size' => 18,
            'documentation_text_color' => '#123456',
            'documentation_heading_color' => '#654321',
            'documentation_background_color' => '#fefefe',
            'documentation_line_height' => 1.8,
        ])
        ->assertForbidden();
});

it('validates documentation style values', function () {
    $administrator = User::factory()->create(['role' => 'admin']);

    $this->actingAs($administrator)
        ->put(route('admin.documentation-style.update'), [
            'documentation_font_family' => 'script',
            'documentation_font_size' => 99,
            'documentation_text_color' => 'red',
            'documentation_heading_color' => '#123456',
            'documentation_background_color' => '#ffffff',
            'documentation_line_height' => 3,
        ])
        ->assertSessionHasErrors([
            'documentation_font_family',
            'documentation_font_size',
            'documentation_text_color',
            'documentation_line_height',
        ]);
});
