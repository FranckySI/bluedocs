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
            --bd-line: rgba(255, 255, 255, 0.12);

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

        .bd-add-btn:hover,
        .bd-add-btn:focus-visible {
            background: var(--bd-red-dark);
        }

        /* Style pour bouton secondaire Annuler */
        .bd-btn-cancel {
            background: #e2e8f0;
            color: #475569;
            padding: 12px 20px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: background .2s;
        }
        .bd-btn-cancel:hover {
            background: #cbd5e1;
        }

        /* Actions buttons */
        .bd-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }
    </style>

    <div class="bd-docs">
        <!-- En-tête -->
        <div class="bd-docs-head">
            <div>
                <span class="bd-eyebrow-dark">DOCUMENTATION MODE LECTURE</span>
                <h1 class="bd-docs-title" id="page-main-title">Document : <span class="accent">{{ $document->title }}</span></h1>
                <p class="bd-docs-sub">Consultez le document ou passez en mode édition à l'aide du bouton.</p>
            </div>
            <div class="flex gap-2">
                @auth
                    <button type="button" id="btn-toggle-edit" class="bd-add-btn">
                         Modifier ce document
                    </button>
                @endauth
                <a class="bd-btn-cancel" href="{{ route('docs.sommaire') }}">Retour</a>
            </div>
        </div>

        <!--  Vue principale -->
        <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                
                <!-- AFFICHAGE CLASSIQUE -->
                <div id="wrapper-view-mode">
                    <h1 class="text-3xl font-bold mb-6 text-gray-900">{{ $document->title }}</h1>
                    <p class="text-sm text-gray-500 mb-4">Créé par : {{ $document->user_name }} le {{ $document->created_at->format('d/m/Y H:i') }}</p>

                    <div class="prose max-w-none text-gray-800">
                        {!! $document->content !!}
                    </div>
                </div>

                <!-- FORMULAIRE D'ÉDITION DIRECTE (Caché par défaut) -->
                <div id="wrapper-edit-mode" style="display: none;">
                    <form action="{{ route('documents.update', $document->slug) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre du document :</label>
                            <input type="text" id="title" name="title" value="{{ $document->title }}" required
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- TinyMCE modif -->
                        <div class="mb-6">
                            <label for="mon-editeur" class="block text-sm font-medium text-gray-700 mb-2">Contenu :</label>
                            <textarea id="mon-editeur" name="content">{!! $document->content !!}</textarea>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" id="btn-cancel-edit" class="bd-btn-cancel">Annuler</button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded shadow">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Injection des scripts et gestion de TinyMCE -->
    <x-slot name="scripts">
        <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
        
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Initialisation silencieuse de TinyMCE
                tinymce.init({
                    selector: '#mon-editeur',
                    license_key: 'gpl',
                    language: 'fr_FR',
                    height: 500,
                    plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
                    toolbar: 'undo redo | blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table link image | removeformat | help',
                    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
                    relative_urls: false, 
                    remove_script_host: true, 
                    convert_urls: false
                });

                // Éléments HTML
                const btnToggle = document.getElementById('btn-toggle-edit');
                const btnCancel = document.getElementById('btn-cancel-edit');
                const viewMode = document.getElementById('wrapper-view-mode');
                const editMode = document.getElementById('wrapper-edit-mode');
                const badgeTitle = document.querySelector('.bd-eyebrow-dark');

                // Fonction pour basculer en mode édition
                if(btnToggle) {
                    btnToggle.addEventListener('click', function() {
                        if (editMode.style.display === 'none') {
                            editMode.style.display = 'block';
                            viewMode.style.display = 'none';
                            btnToggle.style.display = 'none'; // Cache le bouton modifier pendant l'édition
                            badgeTitle.textContent = "DOCUMENTATION MODE ÉDITION";
                            badgeTitle.style.color = "var(--bd-red)";
                            badgeTitle.style.borderColor = "var(--bd-red)";
                        }
                    });
                }

                // Fonction pour annuler et revenir au mode lecture
                if(btnCancel) {
                    btnCancel.addEventListener('click', function() {
                        editMode.style.display = 'none';
                        viewMode.style.display = 'block';
                        btnToggle.style.display = 'inline-flex';
                        badgeTitle.textContent = "DOCUMENTATION MODE LECTURE";
                        badgeTitle.style.color = "var(--bd-blue)";
                        badgeTitle.style.borderColor = "var(--bd-blue)";
                    });
                }
            });
        </script>
    </x-slot>

</body>
</x-app-layout>
