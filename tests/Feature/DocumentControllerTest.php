<?php

use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Log;

it('renders the missing Sommaire as a single-document TinyMCE editor', function () {
    $editor = User::factory()->create();

    $response = $this->actingAs($editor)
        ->get(route('docs.sommaire').'?edit=1');

    $response
        ->assertOk()
        ->assertSee('Sommaire')
        ->assertSee('name="slug" value="sommaire"', false)
        ->assertSee('id="mon-editeur"', false)
        ->assertSee('Importer un fichier Word (.docx)')
        ->assertSee("toolbar: 'undo redo | blocks | bold italic | bullist numlist | link unlink'", false)
        ->assertSee('word-import-', false);

    $this->assertDatabaseCount('documents', 0);
});

it('creates the missing Sommaire document and logs metadata without its content', function () {
    $editor = User::factory()->create();
    Log::spy();

    $this->actingAs($editor)
        ->post(route('documents.store'), [
            'title' => 'Sommaire',
            'slug' => 'sommaire',
            'content' => '<h1>Procédures internes</h1>',
        ])
        ->assertRedirect(route('docs.sommaire'))
        ->assertSessionHas('success', 'Document créé avec succès !');

    $document = Document::query()->where('slug', 'sommaire')->firstOrFail();

    $this->assertDatabaseHas('documents', [
        'id' => $document->id,
        'title' => 'Sommaire',
        'content' => '<h1>Procédures internes</h1>',
        'user_name' => $editor->name,
    ]);

    Log::shouldHaveReceived('info')
        ->once()
        ->with('Document created.', [
            'document_id' => $document->id,
            'slug' => 'sommaire',
            'actor_id' => $editor->id,
        ]);
});

it('rejects a duplicate document slug before hitting the database constraint', function () {
    $editor = User::factory()->create();
    $existingDocument = Document::create([
        'title' => 'Sommaire',
        'slug' => 'sommaire',
        'content' => '<p>Contenu existant.</p>',
        'user_name' => 'Ancien éditeur',
    ]);

    $this->actingAs($editor)
        ->post(route('documents.store'), [
            'title' => 'Nouveau document',
            'slug' => 'sommaire',
            'content' => '<p>Nouveau contenu.</p>',
        ])
        ->assertSessionHasErrors('slug');

    $this->assertDatabaseCount('documents', 1);
    $this->assertModelExists($existingDocument);
});

it('updates the existing Sommaire document and logs metadata without its content', function () {
    $editor = User::factory()->create();
    $document = Document::create([
        'title' => 'Sommaire',
        'slug' => 'sommaire',
        'content' => '<p>Ancien contenu.</p>',
        'user_name' => 'Ancien éditeur',
    ]);
    Log::spy();

    $this->actingAs($editor)
        ->put(route('documents.update', $document), [
            'title' => 'Sommaire',
            'content' => '<h2>Liens utiles</h2>',
        ])
        ->assertRedirect(route('docs.sommaire'))
        ->assertSessionHas('success', 'Document mis à jour avec succès !');

    $this->assertDatabaseHas('documents', [
        'id' => $document->id,
        'title' => 'Sommaire',
        'slug' => 'sommaire',
        'content' => '<h2>Liens utiles</h2>',
        'user_name' => $editor->name,
        'updated_by' => $editor->id,
    ]);

    Log::shouldHaveReceived('info')
        ->once()
        ->with('Document updated.', [
            'document_id' => $document->id,
            'slug' => 'sommaire',
            'actor_id' => $editor->id,
        ]);
});

it('keeps the document unchanged when an update fails validation', function () {
    $editor = User::factory()->create();
    $document = Document::create([
        'title' => 'Sommaire',
        'slug' => 'sommaire',
        'content' => '<p>Contenu conservé.</p>',
        'user_name' => 'Ancien éditeur',
    ]);

    $this->actingAs($editor)
        ->put(route('documents.update', $document), [
            'title' => 'Sommaire',
            'content' => '',
        ])
        ->assertSessionHasErrors('content');

    $this->assertDatabaseHas('documents', [
        'id' => $document->id,
        'content' => '<p>Contenu conservé.</p>',
        'user_name' => 'Ancien éditeur',
    ]);
});

it('requires authentication to update a document', function () {
    $document = Document::create([
        'title' => 'Sommaire',
        'slug' => 'sommaire',
        'content' => '<p>Contenu protégé.</p>',
        'user_name' => 'Ancien éditeur',
    ]);

    $this->put(route('documents.update', $document), [
        'title' => 'Sommaire',
        'content' => '<p>Contenu modifié.</p>',
    ])->assertRedirect(route('login'));

    $this->assertDatabaseHas('documents', [
        'id' => $document->id,
        'content' => '<p>Contenu protégé.</p>',
    ]);
});

it('requires authentication to create a document', function () {
    $this->post(route('documents.store'), [
        'title' => 'Sommaire',
        'slug' => 'sommaire',
        'content' => '<p>Contenu protégé.</p>',
    ])->assertRedirect(route('login'));

    $this->assertDatabaseCount('documents', 0);
});

it('logs metadata when a document is deleted', function () {
    $editor = User::factory()->create();
    $document = Document::create([
        'title' => 'Document à supprimer',
        'slug' => 'document-a-supprimer',
        'content' => '<p>Contenu supprimé.</p>',
        'user_name' => 'Ancien éditeur',
    ]);
    Log::spy();

    $this->actingAs($editor)
        ->delete(route('documents.destroy', $document))
        ->assertRedirect(route('pages.document'))
        ->assertSessionHas('success', 'Document supprimé avec succès !');

    $this->assertDatabaseMissing('documents', ['id' => $document->id]);

    Log::shouldHaveReceived('info')
        ->once()
        ->with('Document deleted.', [
            'document_id' => $document->id,
            'slug' => 'document-a-supprimer',
            'actor_id' => $editor->id,
        ]);
});
