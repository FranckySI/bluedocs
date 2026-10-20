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
            --bd-live: #16a34a;

            --bd-font-display: 'Space Grotesk', sans-serif;
            --bd-font-body: 'Inter', sans-serif;
            --bd-font-mono: 'JetBrains Mono', monospace;

            --bd-ease: cubic-bezier(.22, 1, .36, 1);
        }

        /* ---------- Page : fond gris clair + grand panneau blanc ---------- */
        .bd-docs {
            box-sizing: border-box;
            min-height: 100vh;
            padding: 0;
            background: #f7f7f7;
            font-family: var(--bd-font-body);
            color: var(--bd-ink);
        }

        .bd-panel {
            box-sizing: border-box;
            min-height: 100vh;
            width: 100%;
            padding: clamp(28px, 6vh, 72px) clamp(24px, 3vw, 40px);
            background: var(--bd-white);
        }

        /* ---------- En-tête ---------- */
        .bd-docs-head {
            position: relative;
            z-index: 3;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
        }

        .bd-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 12px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.06em;
            color: #52627a;
            background: #f1f5f9;
            border-radius: 6px;
        }

        .bd-tag::before {
            content: "";
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--bd-blue-light);
        }

        .bd-tag--warn {
            color: var(--bd-red-dark);
            background: #fff1f3;
        }

        .bd-tag--warn::before {
            background: var(--bd-red);
        }

        .bd-docs-title {
            margin: 0;
            font-family: var(--bd-font-display);
            font-weight: 300;
            font-size: clamp(20px, 2.2vw, 26px);
            line-height: 1.2;
            letter-spacing: -0.035em;
        }

        .bd-docs-sub {
            margin: 6px 0 0;
            font-size: 13px;
            color: var(--bd-muted);
        }

        .bd-docs-meta {
            margin: 8px 0 0;
            font-size: 12.5px;
            color: var(--bd-muted);
        }

        .bd-docs-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        /* ---------- Boutons ---------- */
        .bd-ico {
            width: 16px;
            height: 16px;
            flex: 0 0 auto;
            stroke-width: 1.8;
        }

        .bd-add-btn,
        .bd-back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 14px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.2;
            text-decoration: none;
            white-space: nowrap;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color .25s var(--bd-ease), border-color .25s var(--bd-ease),
                transform .25s var(--bd-ease), box-shadow .25s var(--bd-ease);
        }

        /* Principal : pilule sombre (comme l'onglet actif du modèle) */
        .bd-add-btn {
            color: var(--bd-white);
            background: var(--bd-ink);
            border: 1px solid var(--bd-ink);
        }

        .bd-add-btn:hover {
            background: #1b2a3d;
            border-color: #1b2a3d;
            transform: translateY(-1px);
            box-shadow: 0 6px 14px -6px rgba(10, 20, 32, 0.45);
        }

        .bd-add-btn--info {
            background: var(--bd-blue-light);
            border-color: var(--bd-blue-light);
        }

        .bd-add-btn--info:hover {
            background: var(--bd-blue);
            border-color: var(--bd-blue);
        }

        .bd-add-btn--success {
            background: var(--bd-live);
            border-color: var(--bd-live);
        }

        .bd-add-btn--success:hover {
            background: #15803d;
            border-color: #15803d;
        }

        /* Secondaire : blanc, fine bordure */
        .bd-back-btn {
            color: var(--bd-ink);
            background: var(--bd-white);
            border: 1px solid var(--bd-line);
        }

        .bd-back-btn:hover {
            background: #f6f7f9;
            border-color: #d5dbe4;
        }

        .bd-add-btn:focus-visible,
        .bd-back-btn:focus-visible {
            outline: 2px solid var(--bd-blue-light);
            outline-offset: 2px;
        }

        /* ---------- Alertes ---------- */
        .bd-sommaire-alert {
            margin-bottom: 18px;
            padding: 11px 14px;
            font-size: 13.5px;
            border-radius: 10px;
        }

        .bd-sommaire-alert p {
            margin: 0;
        }

        .bd-sommaire-alert-success {
            color: #166534;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
        }

        .bd-sommaire-alert-error {
            color: #991b1b;
            background: #fef2f2;
            border: 1px solid #fecaca;
        }

        /* ---------- Ajouter une section (menu déroulant) ---------- */
        .bd-section-create {
            position: relative;
        }

        .bd-section-create summary {
            list-style: none;
        }

        .bd-section-create summary::-webkit-details-marker {
            display: none;
        }

        .bd-section-form {
            position: absolute;
            z-index: 5;
            top: calc(100% + 8px);
            right: 0;
            width: min(340px, calc(100vw - 40px));
            padding: 18px;
            background: var(--bd-white);
            border: 1px solid var(--bd-line);
            border-radius: 14px;
            box-shadow: 0 18px 40px -12px rgba(0, 17, 31, 0.22);
            transform-origin: top right;
        }

        .bd-section-create[open] .bd-section-form {
            animation: bd-pop .3s var(--bd-ease) both;
        }

        .bd-section-form label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
        }

        .bd-section-input,
        .bd-input {
            width: 100%;
            padding: 10px 12px;
            font-family: inherit;
            font-size: 14px;
            color: var(--bd-ink);
            background: var(--bd-white);
            border: 1px solid #d5dbe4;
            border-radius: 10px;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .bd-section-input:focus,
        .bd-input:focus {
            outline: none;
            border-color: var(--bd-blue-light);
            box-shadow: 0 0 0 3px rgba(14, 95, 168, 0.14);
        }

        .bd-section-form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 14px;
        }

        /* ---------- Sommaire : liste façon tableau ---------- */
        .bd-rows {
            --cols: minmax(200px, 1fr) minmax(0, 2.2fr);
        }

        .bd-rows--actions {
            --cols: minmax(200px, 1fr) minmax(0, 2.2fr) minmax(84px, auto);
        }

        .bd-rows-head,
        .bd-row {
            display: grid;
            grid-template-columns: var(--cols);
            align-items: center;
            gap: 16px;
            padding: 0 14px;
        }

        .bd-rows-head {
            padding-top: 10px;
            padding-bottom: 10px;
            font-size: 12px;
            font-weight: 500;
            color: var(--bd-muted);
            background: #fafbfc;
            border-top: 1px solid var(--bd-line);
            border-bottom: 1px solid var(--bd-line);
        }

        .bd-rows-head span:last-child:not(:first-child):not(:nth-child(2)),
        .bd-rows--actions .bd-rows-head span:nth-child(3) {
            text-align: center;
        }

        .bd-row {
            padding-top: 14px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--bd-line);
            transition: background-color .25s ease;
        }

        .bd-row:hover {
            background: #fafbfc;
        }

        .bd-row-title {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .bd-section-title {
            margin: 0;
            font-family: var(--bd-font-display);
            font-size: 15px;
            font-weight: 50;
            letter-spacing: -0.01em;
            overflow-wrap: anywhere;
        }

        .bd-section-title a {
            color: var(--bd-ink);
            text-decoration: none;
            transition: color .2s ease;
        }

        .bd-section-title a:hover {
            color: var(--bd-blue-light);
        }

        .bd-section-documents {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .bd-section-documents a {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            font-size: 12.5px;
            font-weight: 500;
            color: var(--bd-blue);
            text-decoration: none;
            background: var(--bd-white);
            border: 1px solid var(--bd-line);
            border-radius: 8px;
            transition: color .2s ease, border-color .2s ease, background-color .2s ease, transform .25s var(--bd-ease);
        }

        .bd-section-documents a:hover {
            color: var(--bd-blue-light);
            background: var(--bd-paper);
            border-color: #cddbea;
            transform: translateY(-1px);
        }

        .bd-section-empty {
            margin: 0;
            font-size: 13px;
            color: var(--bd-muted);
        }

        .bd-sommaire-empty {
            margin: 0;
            padding: 40px 0;
            text-align: center;
            font-size: 14px;
            color: var(--bd-muted);
        }

        .bd-row-action {
            display: flex;
            justify-content: center;
        }

        .bd-row-action form {
            margin: 0;
        }

        .bd-section-delete {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            font-family: inherit;
            font-size: 12px;
            font-weight: 500;
            color: var(--bd-red);
            background: transparent;
            border: 1px solid transparent;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color .2s ease, border-color .2s ease, transform .25s var(--bd-ease);
        }

        .bd-section-delete:hover {
            background: #fff1f3;
            border-color: #fbd0d8;
            transform: scale(1.06);
        }

        .bd-section-delete:focus-visible {
            outline: 2px solid var(--bd-red);
            outline-offset: 2px;
        }

        /* ---------- Lecture / édition d'un document ---------- */
        .target-links a {
            color: var(--bd-blue-light);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .target-links a:hover {
            color: var(--bd-blue);
            text-decoration: underline;
        }

        .target-links ul {
            list-style-type: disc;
            padding-left: 1.5rem;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }

        .target-links ol {
            list-style-type: decimal;
            padding-left: 1.5rem;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }

        .target-links li {
            margin-bottom: 0.7rem;
            font-size: 16px;
        }

        .target-links img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .target-links table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .target-links th,
        .target-links td {
            padding: 10px 12px;
            text-align: left;
            border: 1px solid var(--bd-line);
        }

        .target-links th {
            font-weight: 500;
            color: var(--bd-muted);
            background: #fafbfc;
        }

        .bd-import {
            margin-bottom: 20px;
            padding: 14px 16px;
            background: #fafbfc;
            border: 1px dashed #cdd5e0;
            border-radius: 12px;
        }

        .bd-label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
        }

        .bd-file-input {
            display: block;
            width: 100%;
            font-size: 13px;
            color: var(--bd-muted);
        }

        .bd-file-input::file-selector-button {
            margin-right: 14px;
            padding: 8px 14px;
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            color: var(--bd-ink);
            background: var(--bd-white);
            border: 1px solid var(--bd-line);
            border-radius: 10px;
            cursor: pointer;
            transition: background-color .2s ease;
        }

        .bd-file-input::file-selector-button:hover {
            background: #f6f7f9;
        }

        .bd-field {
            margin-bottom: 20px;
        }

        .bd-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .tox-tinymce {
            border-color: var(--bd-line) !important;
            border-radius: 12px !important;
        }

        /* ---------- Animation d'arrivée (au chargement) ---------- */
        @keyframes bd-enter {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: none; }
        }

        @keyframes bd-fade {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        @keyframes bd-pop {
            from { opacity: 0; transform: translateY(-6px) scale(.97); }
            to   { opacity: 1; transform: none; }
        }

        .bd-panel {
            animation: bd-enter .75s var(--bd-ease) both;
        }

        .bd-docs-head {
            animation: bd-enter .75s var(--bd-ease) .08s both;
        }

        .bd-rows-head {
            animation: bd-fade .6s ease .14s both;
        }

        .bd-row {
            animation: bd-enter .65s var(--bd-ease) calc(.16s + var(--i, 0) * 55ms) both;
        }

        .bd-enter-body {
            animation: bd-enter .75s var(--bd-ease) .16s both;
        }

        @media (max-width: 820px) {
            .bd-panel {
                padding: 32px 20px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .bd-panel,
            .bd-docs-head,
            .bd-rows-head,
            .bd-row,
            .bd-enter-body,
            .bd-section-create[open] .bd-section-form {
                animation: none;
            }

            .bd-add-btn,
            .bd-back-btn,
            .bd-section-delete,
            .bd-section-documents a {
                transition: none;
            }
        }

        /* ---------- Mobile ---------- */
        @media (max-width: 720px) {
            .bd-rows-head {
                display: none;
            }

            .bd-rows .bd-row,
            .bd-rows--actions .bd-row {
                grid-template-columns: 1fr auto;
                gap: 12px;
                padding-left: 4px;
                padding-right: 4px;
            }

            .bd-rows-head + .bd-row,
            .bd-rows .bd-row:first-of-type {
                border-top: 1px solid var(--bd-line);
            }

            .bd-row-docs {
                grid-column: 1 / -1;
                order: 3;
            }
        }
    </style>

    @php
        $isNewDoc = !$isSommaire && isset($document->is_new) && $document->is_new;
    @endphp

    <div class="bd-docs">
        <div class="bd-panel">

            <!--  En-tête  -->
            <div class="bd-docs-head">
                <div>
                    <span class="bd-tag {{ $isNewDoc ? 'bd-tag--warn' : '' }}">
                        {{ $isNewDoc ? 'DOCUMENT INEXISTANT' : 'WIKI INTERNE' }}
                    </span>
                    <h1 class="bd-docs-title">{{ $isSommaire ? 'Sommaire' : $document->title }}</h1>
                    <p class="bd-docs-sub">
                        {{ $isSommaire ? 'Parcourez les sections et les documents disponibles.' : 'Cliquez sur une section pour ouvrir directement la documentation associée.' }}
                    </p>
                    @if (!$isSommaire)
                        <p class="bd-docs-meta">
                            <x-last-modif :document="$document" />
                        </p>
                    @endif
                </div>

                <div class="bd-docs-actions">
                    <!-- Lien vers les archives / dashboard global -->
                    <a class="bd-back-btn" href="javascript:history.back()">
                        @unless ($isSommaire)
                            <x-lucide-arrow-left class="bd-ico" aria-hidden="true" />
                        @endunless
                        Retour
                    </a>

                    @auth
                        @if ($isSommaire)
                            <details class="bd-section-create" @if ($errors->has('title')) open @endif>
                                <summary class="bd-add-btn">
                                    Ajouter une section
                                </summary>
                                <form class="bd-section-form" action="{{ route('sections.store') }}" method="POST">
                                    @csrf
                                    <label for="section-title">Titre de la section</label>
                                    <input
                                        class="bd-section-input"
                                        id="section-title"
                                        name="title"
                                        type="text"
                                        value="{{ old('title') }}"
                                        maxlength="255"
                                        required
                                    >
                                    <div class="bd-section-form-footer">
                                        <button class="bd-add-btn" type="submit">Créer</button>
                                    </div>
                                </form>
                            </details>
                        @endif

                        @if (!$isSommaire && !request()->has('edit'))
                            @if (isset($document->is_new) && $document->is_new)
                                <!-- S'affiche si le doc n'existe pas en BDD -->
                                <a class="bd-add-btn bd-add-btn--info" href="?edit=1">
                                    <x-lucide-file-plus class="bd-ico" aria-hidden="true" />
                                    Créer un document
                                </a>
                            @else
                                <!-- S'affiche si le doc existe en BDD -->
                                <a class="bd-add-btn" href="?edit=1">
                                    <x-lucide-pencil class="bd-ico" aria-hidden="true" />
                                    Modifier le document
                                </a>
                            @endif
                        @endif
                    @endauth
                </div>
            </div>

            <!--  Corps du Sommaire (Mode ÉDITION ou LECTURE) -->
            <div class="bd-wiki-content {{ $isSommaire ? '' : 'bd-enter-body' }}">

                @if ($isSommaire)
                    @if (session('success'))
                        <div class="bd-sommaire-alert bd-sommaire-alert-success" role="status">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bd-sommaire-alert bd-sommaire-alert-error" role="alert">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    @if ($sections->isEmpty())
                        <p class="bd-sommaire-empty">Aucune section disponible pour le moment.</p>
                    @else
                        <div class="bd-rows @auth bd-rows--actions @endauth">
                            <div class="bd-rows-head" aria-hidden="true">
                                <span>Section</span>
                                <span>Documents</span>
                                @auth
                                    <span>Action</span>
                                @endauth
                            </div>

                            @foreach ($sections as $section)
                                @php
                                    $sectionPage = $section->documents->firstWhere('title', $section->title);
                                    $childDocuments = $section->documents->reject(
                                        fn ($sectionDocument) => $sectionPage && $sectionDocument->is($sectionPage),
                                    );
                                @endphp

                                <article class="bd-row" style="--i: {{ min($loop->index, 14) }}">
                                    <div class="bd-row-title">
                                        <h2 class="bd-section-title">
                                            @if ($sectionPage)
                                                <a href="{{ route('documents.show', $sectionPage->slug) }}">
                                                    {{ $section->title }}
                                                </a>
                                            @else
                                                {{ $section->title }}
                                            @endif
                                        </h2>
                                    </div>

                                    <div class="bd-row-docs">
                                        @if ($sectionPage && $childDocuments->isEmpty())
                                            <p class="bd-section-empty">Ouvrir cette section pour consulter son contenu.</p>
                                        @elseif ($childDocuments->isEmpty())
                                            <p class="bd-section-empty">Cette section ne contient pas encore de documents.</p>
                                        @else
                                            <ul class="bd-section-documents">
                                                @foreach ($childDocuments as $sectionDocument)
                                                    <li>
                                                        <a href="{{ route('documents.show', $sectionDocument->slug) }}">
                                                            {{ $sectionDocument->title }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>

                                    @auth
                                        <div class="bd-row-action">
                                            <form
                                                action="{{ route('sections.destroy', $section) }}"
                                                method="POST"
                                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette section ?');"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    class="bd-section-delete"
                                                    type="submit"
                                                    title="Supprimer"
                                                    aria-label="Supprimer la section {{ $section->title }}"
                                                >
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    @endauth
                                </article>
                            @endforeach
                        </div>
                    @endif
                @elseif (request()->has('edit') && auth()->check())
                    <!-- NOUVEAU : Zone d'importation de fichier Word local -->
                    <div class="bd-import">
                        <label class="bd-label" for="word-import">
                            Importer un fichier Word (.docx)
                        </label>
                        <input type="file" id="word-import" accept=".docx" class="bd-file-input" />
                    </div>

                    <!-- MODE ÉDITION / CRÉATION DIRECTE -->
                    <form
                        action="{{ isset($document->is_new) && $document->is_new ? route('documents.store') : route('documents.update', $document) }}"
                        method="POST">
                        @csrf
                        @if (!isset($document->is_new) || !$document->is_new)
                            @method('PUT')
                        @endif

                        <!-- Slug caché pour la création à la volée -->
                        <input type="hidden" name="slug" value="{{ $document->slug }}">

                        <!-- Titre -->
                        <div class="bd-field">
                            <label for="title" class="bd-label">Titre du document :</label>
                            <input type="text" id="title" name="title" value="{{ $document->title }}"
                                required class="bd-input">
                        </div>

                        <!-- Éditeur TinyMCE -->
                        <div class="bd-field">
                            <label for="mon-editeur" class="bd-label">Contenu :</label>
                            <textarea id="mon-editeur" name="content">{!! isset($document->is_new) && $document->is_new ? '' : $document->content !!}</textarea>
                        </div>

                        <!-- Actions -->
                        <div class="bd-form-actions">
                            <!-- Si c'est un nouveau doc virtuel, annuler revient à l'historique précédent (votre sommaire) -->
                            <a href="{{ isset($document->is_new) && $document->is_new ? 'javascript:history.back()' : route('documents.show', $document) }}"
                                class="bd-back-btn">Annuler</a>
                            <button type="submit" class="bd-add-btn bd-add-btn--success">
                                <x-lucide-check class="bd-ico" aria-hidden="true" />
                                Enregistrer
                            </button>
                        </div>
                    </form>
                @else
                    <!-- MODE LECTURE (Par défaut) -->
                    <div class="target-links">
                        {!! $document->content !!}
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!--  Script TinyMCE (Appelé uniquement si ?edit=1) -->
    @if (!$isSommaire && request()->has('edit') && auth()->check())
        <x-slot name="scripts">
            <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    tinymce.init({
                        selector: '#mon-editeur',
                        license_key: 'gpl',
                        language: 'fr_FR',
                        height: 500,
                        plugins: 'advlist  autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount ',
                        toolbar: 'undo redo | blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table link image | removeformat | help',
                        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
                        relative_urls: false,
                        remove_script_host: true,
                        convert_urls: false,
                        branding: false,
                        help_accessibility: false
                    });
                });
            </script>

            @vite(['resources/js/word-import.js'])
        </x-slot>
    @endif

</x-app-layout>