<x-app-layout>  
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <h1 class="text-2xl font-bold mb-6">Modifier le document : {{ $document->titre }}</h1>

                @if ($errors->any())
    <div style="background-color: #fee2e2; border: 1px solid #ef4444; color: #b91c1c; padding: 1rem; border-radius: 0.375rem; margin-bottom: 1.5rem;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



                <!-- Formulaire de modification -->
                <form action="{{ route('documents.update', $document->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Champ Titre pré-rempli -->
                    <div class="mb-4">
                        <label for="titre" class="block text-sm font-medium text-gray-700 mb-2">Titre du document :</label>
                        <input type="text" id="titre" name="title" value="{{ old('title', $document->title) }}" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <!-- Zone TinyMCE pré-remplie -->
                    <div class="mb-6">
                        <label for="contenu" class="block text-sm font-medium text-gray-700 mb-2">Contenu :</label>
                        <textarea id="mon-editeur" name="content">{{ old('content', $document->content) }}</textarea>
                    </div>

                    <!-- Bouton de validation -->
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('docs.sommaire') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded shadow text-decoration-none">
                            Annuler
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded shadow">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Injection des scripts TinyMCE -->
    <x-slot name="scripts">
        <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                tinymce.init({
                    selector: '#mon-editeur',
                    license_key:'gpl',
                    language: 'fr_FR',
                    height: 500,
                    plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
                    toolbar: 'undo redo | blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table link image | removeformat | help',
                    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
                    setup: function (editor) {
                        editor.on('change', function () {
                            editor.save();
                        });
                    }
                });
            });
        </script>

        


    </x-slot>
</x-app-layout>
