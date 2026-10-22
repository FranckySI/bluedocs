<?php

use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Log;

it('finds documents containing every keyword and logs search metadata', function () {
    Document::create([
        'title' => 'Guide de sécurité',
        'slug' => 'guide-securite',
        'content' => '<p>Le service applique les règles de sécurité avant un audit annuel.</p>',
        'user_name' => 'Équipe BlueDocs',
    ]);
    Document::create([
        'title' => 'Sécurité des postes',
        'slug' => 'securite-des-postes',
        'content' => '<p>Les appareils sont protégés.</p>',
        'user_name' => 'Équipe BlueDocs',
    ]);
    Document::create([
        'title' => 'Audit des accès',
        'slug' => 'audit-des-acces',
        'content' => '<p>Les accès sont vérifiés chaque mois.</p>',
        'user_name' => 'Équipe BlueDocs',
    ]);
    Log::spy();

    $this->get(route('documents.search', ['q' => 'sécurité audit']))
        ->assertOk()
        ->assertSee('Guide de sécurité')
        ->assertSee('règles de sécurité avant un audit annuel.')
        ->assertDontSee('Sécurité des postes')
        ->assertDontSee('Audit des accès')
        ->assertSee(route('documents.show', [
            'document' => 'guide-securite',
            'q' => 'sécurité audit',
        ]).'#document-content', false);

    Log::shouldHaveReceived('info')
        ->once()
        ->with('Document search performed.', [
            'query_length' => 14,
            'keyword_count' => 2,
            'results_count' => 1,
            'actor_id' => null,
        ]);
});

it('does not treat punctuation in a search as a SQL wildcard', function () {
    Document::create([
        'title' => 'Guide interne',
        'slug' => 'guide-interne',
        'content' => '<p>Contenu interne.</p>',
        'user_name' => 'Équipe BlueDocs',
    ]);

    $this->get(route('documents.search', ['q' => '%%%']))
        ->assertOk()
        ->assertSee('Aucun document trouvé')
        ->assertDontSee('Guide interne');
});

it('matches accented keywords regardless of their case', function () {
    Document::create([
        'title' => 'Sécurité des postes',
        'slug' => 'securite-des-postes',
        'content' => '<p>Protégez vos appareils.</p>',
        'user_name' => 'Équipe BlueDocs',
    ]);

    $this->get(route('documents.search', ['q' => 'sécurité']))
        ->assertOk()
        ->assertSee('Sécurité des postes');
});

it('validates the maximum length of the search query', function () {
    $this->get(route('documents.search', ['q' => str_repeat('mot', 34)]))
        ->assertSessionHasErrors('q');
});

it('displays the search bar on the Sommaire, dashboard, and document list', function () {
    $editor = User::factory()->create();
    $document = Document::create([
        'title' => 'Guide de démarrage',
        'slug' => 'guide-de-demarrage',
        'content' => '<p>Bienvenue dans BlueDocs.</p>',
        'user_name' => $editor->name,
    ]);

    $this->actingAs($editor);

    foreach ([
        route('docs.sommaire'),
        route('dashboard'),
        route('pages.document'),
        route('documents.show', $document->slug),
    ] as $url) {
        $this->get($url)
            ->assertOk()
            ->assertSeeInOrder(['id="app-sidebar"', 'role="search"', '</aside>'], false)
            ->assertSee('role="search"', false)
            ->assertSee('name="q"', false)
            ->assertSee('Rechercher un document...');
    }
});

it('shows the matching document content at a stable highlight target', function () {
    Document::create([
        'title' => 'Procédure de contrôle',
        'slug' => 'procedure-controle',
        'content' => '<h2>Audit annuel</h2><p>Vérifiez les accès.</p>',
        'user_name' => 'Équipe BlueDocs',
    ]);

    $this->get(route('documents.show', [
        'document' => 'procedure-controle',
        'q' => 'audit',
    ]))
        ->assertOk()
        ->assertSee('id="document-content"', false)
        ->assertSee('<h2>Audit annuel</h2>', false)
        ->assertSee('name="q"', false);
});

it('escapes search terms before displaying them in the page', function () {
    $this->get(route('documents.search', ['q' => '<script>alert(1)</script>']))
        ->assertOk()
        ->assertDontSee('<script>alert(1)</script>', false);
});
