<x-app-layout>

    <body style="margin:0;">

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

                --bd-font-display: 'Space Grotesk', sans-serif;
                --bd-font-body: 'Inter', sans-serif;
                --bd-font-mono: 'JetBrains Mono', monospace;
            }

            body {
                margin: 0;
                background: var(--bd-paper);
                font-family: var(--bd-font-body);
                color: var(--bd-ink);
            }

            .bd-docs {
                max-width: 1200px;
                margin: 0 auto;
                padding: 40px 40px 70px;
            }

            /* ---------- En-tête ---------- */
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

            /* ---------- Boutons ---------- */
            .bd-add-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: var(--bd-red);
                color: var(--bd-white);
                text-decoration: none;
                font-size: 14px;
                font-weight: 600;
                padding: 12px 20px;
                border-radius: 4px;
                transition: background .2s ease;
                white-space: nowrap;
                border: none;
                cursor: pointer;
            }

            .bd-add-btn:hover {
                background: var(--bd-red-dark);
            }

            .bd-back-btn {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: #eef2f6;
                color: var(--bd-blue);
                text-decoration: none;
                font-size: 14px;
                font-weight: 600;
                padding: 12px 20px;
                border-radius: 4px;
                transition: background .2s ease;
                white-space: nowrap;
                border: 1px solid #cbd5e1;
            }

            .bd-back-btn:hover {
                background: #cbd5e1;
            }

            /* ---------- Contenu du Sommaire ---------- */
            .bd-wiki-content {
                background: var(--bd-white);
                padding: 32px;
                border-radius: 8px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            }

            /* Target links spécifiques pour la liste DokuWiki */
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

            .target-links li {
                margin-bottom: 0.7rem;
                font-size: 16px;
            }
        </style>

        <div class="bd-docs">
            <!--  En-tête  -->
            <div class="bd-docs-head">
                <div>
                    <span class="bd-eyebrow-dark">
                        {{ isset($document->is_new) && $document->is_new ? 'DOCUMENT INEXISTANT' : 'WIKI INTERNE' }}
                    </span>
                    <h1 class="bd-docs-title">{{ $document->title }}</h1>
                    <p class="bd-docs-sub">Cliquez sur une section pour ouvrir directement la documentation associée.</p>
                    <p>
                        <x-last-modif :document="$document" />
                    </p>
                </div>

                <div>
    
</div>




                <div style="display: flex; gap: 10px;">
                    <!-- Lien vers les archives / dashboard global -->
                    <a class="bd-back-btn" href="javascript:history.back()">Retour</a>


                    @auth
                        @if (!request()->has('edit'))
                            @if (isset($document->is_new) && $document->is_new)
                                <!-- S'affiche si le doc n'existe pas en BDD -->
                                <a class="bd-add-btn" href="?edit=1" style="background: var(--bd-blue-light);">
                                    Créer un document
                                </a>
                            @else
                                <!-- S'affiche si le doc existe en BDD -->
                                <a class="bd-add-btn" href="?edit=1">
                                    Modifier le document
                                </a>
                            @endif
                        @endif
                    @endauth
                </div>
            </div>

            <!--  Corps du Sommaire (Mode ÉDITION ou LECTURE) -->
            <div class="py-6 max-w-4xl mx-auto">
                <div class="bd-wiki-content">

                    @if (request()->has('edit') && auth()->check())


                        <!-- NOUVEAU : Zone d'importation de fichier Word local -->
                        <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-md">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Importer un fichier Word (.docx)
                            </label>
                            <input type="file" id="word-import" accept=".docx"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
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
                            <div style="margin-bottom: 1.5rem;">
                                <label for="title"
                                    style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 0.5rem;">Titre
                                    du document :</label>
                                <input type="text" id="title" name="title" value="{{ $document->title }}"
                                    required
                                    style="width: 100%; border: 1px solid #cbd5e1; padding: 10px; border-radius: 4px;">
                            </div>

                            <!-- Éditeur TinyMCE -->
                            <div style="margin-bottom: 1.5rem;">
                                <label for="mon-editeur"
                                    style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 0.5rem;">Contenu
                                    :</label>
                                <textarea id="mon-editeur" name="content">{!! isset($document->is_new) && $document->is_new ? '' : $document->content !!}</textarea>
                            </div>

                            <!-- Actions -->
                            <div style="display: flex; justify-content: flex-end; gap: 10px;">
                                <!-- Si c'est un nouveau doc virtuel, annuler revient à l'historique précédent (votre sommaire) -->
                                <a href="{{ isset($document->is_new) && $document->is_new ? 'javascript:history.back()' : route('documents.show', $document) }}"
                                    class="bd-back-btn">Annuler</a>
                                <button type="submit" class="bd-add-btn"
                                    style="background: #10b981;">Enregistrer</button>
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
        @if (request()->has('edit') && auth()->check())
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
                            convert_urls: false
                        });
                    });
                </script>

                @vite(['resources/js/word-import.js'])
            </x-slot>
        @endif


    </body>
</x-app-layout>
