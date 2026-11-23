<?php

use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

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
            'query_fingerprint' => hash_hmac('sha256', 'sécurité audit', (string) config('app.key')),
            'query_length' => 14,
            'keyword_count' => 2,
            'results_count' => 1,
            'page' => 1,
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

it('matches complete visible words instead of substrings or HTML attributes', function () {
    Document::create([
        'title' => 'Guide du dictionnaire',
        'slug' => 'guide-dictionnaire',
        'content' => '<p>Le dictionnaire est disponible.</p>',
        'user_name' => 'Équipe BlueDocs',
    ]);
    Document::create([
        'title' => 'Guide des mots',
        'slug' => 'guide-mots',
        'content' => '<p>dict : mot-à-mot !</p>',
        'user_name' => 'Équipe BlueDocs',
    ]);
    Document::create([
        'title' => 'Note technique',
        'slug' => 'note-technique',
        'content' => '<p>Un addict partage ses astuces.</p>',
        'user_name' => 'Équipe BlueDocs',
    ]);
    Document::create([
        'title' => 'Document formaté',
        'slug' => 'document-formate',
        'content' => '<p class="dict">Contenu sans ce terme.</p>',
        'user_name' => 'Équipe BlueDocs',
    ]);
    Document::create([
        'title' => 'Document avec script',
        'slug' => 'document-avec-script',
        'content' => '<p>Contenu sans ce terme.</p><script>const dict = true;</script>',
        'user_name' => 'Équipe BlueDocs',
    ]);

    $this->get(route('documents.search', ['q' => 'dict']))
        ->assertSee('Guide des mots')
        ->assertSee('dict : mot-à-mot !')
        ->assertDontSee('Guide du dictionnaire')
        ->assertDontSee('Note technique')
        ->assertDontSee('Document formaté')
        ->assertDontSee('Document avec script');
});

it('keeps matching results paginated in stable title order', function () {
    foreach (range(1, 16) as $number) {
        Document::create([
            'title' => sprintf('Guide dict %02d', $number),
            'slug' => sprintf('guide-dict-%02d', $number),
            'content' => '<p>Contenu dict.</p>',
            'user_name' => 'Équipe BlueDocs',
        ]);
    }

    $this->get(route('documents.search', ['q' => 'dict', 'page' => 2]))
        ->assertSee('Guide dict 16')
        ->assertDontSee('Guide dict 01')
        ->assertSee('16 documents');
});

it('requires every search word as a complete word in the title or visible content', function () {
    Document::create([
        'title' => 'Guide équipe',
        'slug' => 'guide-equipe',
        'content' => '<p>Un manuel destiné aux utilisateurs.</p>',
        'user_name' => 'Équipe BlueDocs',
    ]);
    Document::create([
        'title' => 'Guide équipement',
        'slug' => 'guide-equipement',
        'content' => '<p>Un manuel destiné aux utilisateurs.</p>',
        'user_name' => 'Équipe BlueDocs',
    ]);

    $this->get(route('documents.search', ['q' => 'ÉQUIPE manuel']))
        ->assertSee('Guide équipe')
        ->assertDontSee('Guide équipement');
});

it('shows when a result matches only its title', function () {
    Document::create([
        'title' => 'Guide dict',
        'slug' => 'guide-dict',
        'content' => '<p>Contenu sans correspondance.</p>',
        'user_name' => 'Équipe BlueDocs',
    ]);

    $this->get(route('documents.search', ['q' => 'dict']))
        ->assertSee('Correspondance dans le titre.');
});

it('logs database failures with a search fingerprint and rethrows them', function () {
    Schema::drop('documents');
    Log::spy();

    $this->get(route('documents.search', ['q' => 'dict']))
        ->assertInternalServerError();

    Log::shouldHaveReceived('warning')
        ->once()
        ->with('Document search failed.', Mockery::on(function (array $context): bool {
            return $context['query_fingerprint'] === hash_hmac('sha256', 'dict', (string) config('app.key'))
                && $context['keyword_count'] === 1
                && $context['actor_id'] === null;
        }));
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
