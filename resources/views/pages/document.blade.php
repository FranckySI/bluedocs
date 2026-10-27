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
            --bd-muted: #6b7a90;
            --bd-line: #e6eaf0;

            --bd-font-display: 'Plus Jakarta Sans', sans-serif;
            --bd-font-body: 'Plus Jakarta Sans', sans-serif;
            --bd-font-mono: 'JetBrains Mono', monospace;
            --bd-ease: cubic-bezier(.22, 1, .36, 1);
        }

        body {
            background: #f7f7f7;
            font-family: var(--bd-font-body);
            color: var(--bd-ink);
        }

        /* Fond et panneau alignés sur le sommaire */
        .bd-docs {
            box-sizing: border-box;
            width: 100%;
            min-height: 100vh;
            padding: 0;
            background: #f7f7f7;
        }

        .bd-panel {
            box-sizing: border-box;
            width: 100%;
            min-height: 100vh;
            padding: clamp(28px, 6vh, 72px) clamp(24px, 3vw, 40px);
            background: var(--bd-white);
            animation: bd-docs-enter .75s var(--bd-ease) both;
        }

        /* ---------- En-tête de page ---------- */
        .bd-docs-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
            animation: bd-docs-enter .75s var(--bd-ease) .08s both;
        }

        .bd-eyebrow-dark {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.06em;
            color: #52627a;
            background: #f1f5f9;
            border-radius: 6px;
            padding: 4px 10px;
            margin-bottom: 12px;
        }

        .bd-eyebrow-dark::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--bd-blue-light);
        }

        .bd-docs-title {
            margin: 0;
            font-family: var(--bd-font-display);
            font-weight: 600;
            font-size: clamp(20px, 2.2vw, 26px);
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .bd-docs-sub {
            max-width: 540px;
            margin: 6px 0 0;
            font-size: 13px;
            line-height: 1.6;
            color: var(--bd-muted);
        }

        .bd-ico {
            width: 16px;
            height: 16px;
            flex: 0 0 auto;
            stroke-width: 1.8;
        }

        .bd-add-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--bd-ink);
            color: var(--bd-white);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.2;
            padding: 9px 14px;
            border: 1px solid var(--bd-ink);
            border-radius: 10px;
            transition: background-color .25s var(--bd-ease), border-color .25s var(--bd-ease),
                transform .25s var(--bd-ease), box-shadow .25s var(--bd-ease);
            white-space: nowrap;
        }

        .bd-add-btn:hover,
        .bd-add-btn:focus-visible {
            background: #1b2a3d;
            border-color: #1b2a3d;
            transform: translateY(-1px);
            box-shadow: 0 6px 14px -6px rgba(10, 20, 32, 0.45);
        }

        .bd-add-btn:focus-visible,
        .bd-doc-title-link:focus-visible,
        .bd-btn-action:focus-visible {
            outline: 2px solid var(--bd-blue-light);
            outline-offset: 3px;
        }

        /* ---------- Liste façon tableau ---------- */
        .bd-table-container {
            animation: bd-docs-enter .75s var(--bd-ease) .14s both;
        }

        .bd-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            text-align: left;
        }

        .bd-table th {
            padding: 10px 14px;
            font-size: 12px;
            font-weight: 500;
            color: var(--bd-muted);
            background: #fafbfc;
            border-top: 1px solid var(--bd-line);
            border-bottom: 1px solid var(--bd-line);
        }

        .bd-table td {
            padding: 14px;
            font-size: 13.5px;
            border-bottom: 1px solid var(--bd-line);
        }

        .bd-table tbody tr {
            animation: bd-docs-enter .65s var(--bd-ease) calc(.2s + var(--i, 0) * 55ms) both;
            transition: background-color .25s ease;
        }

        .bd-table tbody tr:hover {
            background: #fafbfc;
        }

        .bd-doc-title-link {
            color: var(--bd-ink);
            font-weight: 600;
            text-decoration: none;
            overflow-wrap: anywhere;
            transition: color .2s ease;
        }

        .bd-doc-title-link:hover {
            color: var(--bd-blue-light);
        }

        .bd-doc-date {
            font-family: var(--bd-font-mono);
            font-size: 11.5px;
            color: var(--bd-muted);
        }

        .bd-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: flex-end;
        }

        .bd-actions form {
            margin: 0;
        }

        .bd-btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-family: inherit;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            padding: 7px 10px;
            border-radius: 8px;
            transition: color .2s ease, background-color .2s ease, border-color .2s ease,
                transform .25s var(--bd-ease);
            background: none;
            border: 1px solid transparent;
            cursor: pointer;
        }

        .bd-btn-action svg {
            width: 14px;
            height: 14px;
            stroke-width: 1.8;
        }

        .bd-btn-edit {
            color: var(--bd-blue);
            background: #eff6ff;
            border-color: #dbeafe;
        }

        .bd-btn-edit:hover {
            background: #dbeafe;
            border-color: #bfdbfe;
            color: var(--bd-blue-dark);
            transform: translateY(-1px);
        }

        .bd-btn-delete {
            color: var(--bd-red-dark);
            background: #fff1f3;
            border-color: #fbd0d8;
        }

        .bd-btn-delete:hover {
            background: #ffe4e8;
            border-color: #f9b7c4;
            transform: translateY(-1px);
        }

        .bd-empty-state {
            padding: 40px 24px !important;
            text-align: center;
            color: var(--bd-muted);
            font-size: 14px;
        }

        @keyframes bd-docs-enter {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        @media (max-width: 720px) {
            .bd-docs {
                width: 100%;
                min-height: 100vh;
                padding: 0;
            }

            .bd-panel {
                padding: 28px 16px 36px;
            }

            .bd-docs-head {
                margin-bottom: 20px;
            }

            .bd-add-btn {
                width: 100%;
                justify-content: center;
            }

            .bd-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .bd-btn-action {
                width: 100%;
                overflow-wrap: anywhere;
            }

            .bd-table th,
            .bd-table td {
                overflow-wrap: anywhere;
                padding-right: 10px;
                padding-left: 10px;
            }

            .bd-doc-date {
                font-size: 10px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .bd-panel,
            .bd-docs-head,
            .bd-table-container,
            .bd-table tbody tr {
                animation: none;
            }

            .bd-add-btn,
            .bd-doc-title-link,
            .bd-btn-action,
            .bd-table tbody tr {
                transition: none;
            }
        }
    </style>

    <div class="bd-docs">
        <div class="bd-panel">
            <!-- ===== En-tête ===== -->
            <div class="bd-docs-head">
                <div>
                    <span class="bd-eyebrow-dark">GESTION DOCUMENTAIRE</span>
                    <h1 class="bd-docs-title">Tous les documents</h1>
                    <p class="bd-docs-sub">Consultez, modifiez ou supprimez les documents classés par catégorie.</p>
                </div>
                @auth
                    <a class="bd-add-btn" href="{{ route('documents.create') }}">
                        <x-lucide-plus class="bd-ico" aria-hidden="true" />
                        Ajouter un document
                    </a>
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
                            <tr style="--i: {{ min($loop->index, 14) }};">
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
                                        <a href="{{ route('documents.edit', $document) }}" class="bd-btn-action bd-btn-edit">
                                            <x-lucide-pencil aria-hidden="true" />
                                            Modifier
                                        </a>

                                        <form action="{{ route('documents.destroy', $document) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bd-btn-action bd-btn-delete">
                                                <x-lucide-trash-2 aria-hidden="true" />
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
    </div>
</x-app-layout>