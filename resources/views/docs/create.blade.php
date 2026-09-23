<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h1 class="text-2xl font-bold mb-6">Ajouter un document</h1>

                <!-- NOUVEAU : Zone d'importation de fichier Word local -->
                <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-md">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Importer un fichier Word (.docx)
                    </label>
                    <input type="file" id="word-import" accept=".docx"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                </div>

                <!-- Formulaire Laravel -->
                <form action="{{ route('documents.store') }}" method="POST">
                    @csrf

                    <!-- Champ Titre -->
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre du document
                            :</label>
                        <input type="text" id="title" name="title" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Zone de texte pour Word (TinyMCE) -->
                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Contenu :</label>
                        <textarea id="mon-editeur" name="content"></textarea>
                    </div>

                    <!-- Bouton de validation -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                            Enregistrer le document
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Regroupement de TOUS les scripts dans le même slot "scripts" -->
    <x-slot name="scripts">
        <!-- 1. Chargement de TinyMCE -->
        <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>

        <!-- 2. Configuration de TinyMCE -->
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                tinymce.init({
                    selector: '#mon-editeur',
                    license_key: 'gpl',
                    language: 'fr_FR',
                    height: 500,
                    plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
                    toolbar: 'undo redo | blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table link image | removeformat | help',
                    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',

                    setup: function(editor) {
                        editor.on('change keyup nodechange SetContent', function() {
                            editor.save();
                        });
                    }
                });
            });
        </script>

        <!-- 3. Script d'import Mammoth (compilé par Vite) -->
        @vite(['resources/js/word-import.js'])
    </x-slot>

</x-app-layout>
