<?php

use App\Models\Document;
use App\Models\Section;
use App\Models\User;

it('lists sections on the sommaire page', function () {
    Section::create(['title' => 'Nouvelle section']);

    $this->get(route('docs.sommaire'))
        ->assertOk()
        ->assertSee('Nouvelle section')
        ->assertSee('Cette section ne contient pas encore de documents.');
});

it('creates a section from the sommaire page', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('sections.store'), ['title' => 'Procédures'])
        ->assertRedirect(route('docs.sommaire'))
        ->assertSessionHas('success', 'Section créée avec succès.');

    $this->assertDatabaseHas('sections', ['title' => 'Procédures']);
});

it('requires a title to create a section', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('sections.store'), ['title' => ''])
        ->assertSessionHasErrors('title');

    $this->assertDatabaseCount('sections', 0);
});

it('requires authentication to create a section', function () {
    $this->post(route('sections.store'), ['title' => 'Procédures'])
        ->assertRedirect(route('login'));

    $this->assertDatabaseCount('sections', 0);
});

it('deletes a section without deleting its documents', function () {
    $document = Document::create([
        'title' => 'Document conservé',
        'content' => '<p>Contenu du document.</p>',
        'user_name' => 'Test User',
    ]);
    $section = Section::create(['title' => 'Section temporaire']);
    $section->documents()->attach($document);

    $this->actingAs(User::factory()->create())
        ->delete(route('sections.destroy', $section))
        ->assertRedirect(route('docs.sommaire'))
        ->assertSessionHas('success', 'Section supprimée avec succès.');

    $this->assertDatabaseMissing('sections', ['id' => $section->id]);
    $this->assertDatabaseMissing('document_section', [
        'section_id' => $section->id,
        'document_id' => $document->id,
    ]);
    $this->assertModelExists($document);
});

it('requires authentication to delete a section', function () {
    $section = Section::create(['title' => 'Section protégée']);

    $this->delete(route('sections.destroy', $section))
        ->assertRedirect(route('login'));

    $this->assertModelExists($section);
});
