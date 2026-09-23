<x-app-layout>
    <style>
        :root {
            /* --- Charte graphique BlueDocs --- */
            --bd-blue-dark: #00111f;
            --bd-blue: #003366;
            --bd-blue-light: #0e5fa8;
            --bd-red: #cc0033;
            --bd-red-dark: #98001f;
            --bd-white: #ffffff;
            --bd-paper: #f4f7fb;
            --bd-ink: #0a1420;
            --bd-muted: #93a6c2;
            --bd-line: rgba(255, 255, 255, 0.12);

            --bd-font-display: 'Space Grotesk', sans-serif;
            --bd-font-body: 'Inter', sans-serif;
            --bd-font-mono: 'JetBrains Mono', monospace;
        }

        body {
            background: var(--bd-paper);
            font-family: var(--bd-font-body);
            color: var(--bd-ink);
        }

        .bd-docs {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 40px 70px;
        }

        /* ---------- En-tête de page ---------- */
        .bd-docs-head {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 26px;
        }

        .bd-eyebrow-dark {
            display: inline-block;
            font-family: var(--bd-font-mono);
            font-size: 12px;
            letter-spacing: 2px;
            color: var(--bd-blue);
            border: 1px solid var(--bd-blue);
            border-radius: 20px;
            padding: 5px 14px;
            margin-bottom: 12px;
        }

        .bd-docs-title {
            font-family: var(--bd-font-display);
            font-weight: 700;
            text-transform: uppercase;
            font-size: clamp(24px, 3vw, 34px);
            margin: 0;
        }

        .bd-docs-title .accent {
            color: var(--bd-red);
        }

        .bd-docs-sub {
            font-size: 13.5px;
            color: #5b6b83;
            margin: 6px 0 0;
        }

        .bd-add-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--bd-blue-dark);
            color: var(--bd-white);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 4px;
            transition: background .2s ease;
            white-space: nowrap;
        }

        .bd-add-btn:hover,
        .bd-add-btn:focus-visible {
            background: var(--bd-blue-light);
        }

        /* ---------- Liste & Tableau des Documents ---------- */
        .bd-table-container {
            background: var(--bd-white);
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            overflow: hidden;
            margin-top: 20px;
        }

        .bd-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .bd-table th {
            background: var(--bd-red-dark);
            color: var(--bd-white);
            font-family: var(--bd-font-mono);
            font-size: 13px;
            letter-spacing: 1px;
            padding: 16px 24px;
            text-transform: uppercase;
        }

        .bd-table td {
            padding: 18px 24px;
            border-bottom: 1px solid #eef2f6;
            font-size: 15px;
        }

        .bd-table tr:last-child td {
            border-bottom: none;
        }

        .bd-doc-title-link {
            color: var(--bd-blue);
            font-weight: 600;
            text-decoration: none;
        }

        .bd-doc-title-link:hover {
            color: var(--bd-blue-light);
            text-decoration: underline;
        }

        .bd-doc-date {
            font-family: var(--bd-font-mono);
            font-size: 13px;
            color: var(--bd-muted);
        }

        .bd-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .bd-btn-action {
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 4px;
            transition: all 0.2s ease;
            background: none;
            border: none;
            cursor: pointer;
        }

        .bd-btn-edit {
            color: var(--bd-blue-light);
            border: 1px solid var(--bd-blue-light);
        }

        .bd-btn-edit:hover {
            background: var(--bd-blue-light);
            color: var(--bd-white);
        }

        .bd-btn-delete {
            color: var(--bd-red);
            border: 1px solid var(--bd-red);
        }

        .bd-btn-delete:hover {
            background: var(--bd-red);
            color: var(--bd-white);
        }

        .bd-empty-state {
            padding: 60px;
            text-align: center;
            color: var(--bd-muted);
            font-size: 16px;
        }
    </style>

    <div class="bd-docs">
        <!-- ===== En-tête ===== -->
        <div class="bd-docs-head">
            <div>
                <span class="bd-eyebrow-dark">GESTION DOCUMENTAIRE</span>
                <h1 class="bd-docs-title">Tous les <span class="accent">documents</span></h1>
                <p class="bd-docs-sub">Consultez, modifiez ou supprimez les documents classés par catégorie.</p>
            </div>
            @auth
                <a class="bd-add-btn" href="{{ route('documents.create') }}">+ Ajouter un document</a>
            @endauth
        </div>

        <!-- Liste des documents disponibles -->
        <div class="bd-table-container">
            <table class="bd-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Nom du document</th>
                        <th style="width: 25%;">Date d'ajout</th>
                        <th style="width: 25%; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($documents as $document)
                        <tr>
                            <td>
                                <a href="{{ route('documents.show', $document->slug) }}" class="bd-doc-title-link">
                                    {{ $document->title }}
                                </a>
                            </td>
                            <td>
                                <span class="bd-doc-date">{{ $document->created_at->format('d/m/Y H:i') }}</span>
                            </td>
                            <td>
                                
                                <div class="bd-actions">
                                    <a href="{{ route('documents.edit', $document->id) }}" class="bd-btn-action bd-btn-edit">
                                        Modifier
                                    </a>

                                    <form action="{{ route('documents.destroy', $document->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bd-btn-action bd-btn-delete">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                                
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="bd-empty-state">
                                Aucun document n'a été trouvé dans le système.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
