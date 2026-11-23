<x-app-layout>
    <div class="mx-auto max-w-4xl space-y-6 px-4 py-10 sm:px-6 lg:px-8">
        <header>
            <p class="text-sm font-semibold uppercase tracking-wide text-blue-800">Administration</p>
            <h1 class="mt-1 text-2xl font-semibold text-slate-900">Style de la documentation</h1>
            <p class="mt-2 text-sm text-slate-600">
                Personnalisez la présentation du contenu affiché dans les documents.
            </p>
        </header>

        @if (session('status') === 'documentation-style-updated')
            <p role="status" class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                Le style de la documentation a été enregistré.
            </p>
        @endif

        <form method="POST" action="{{ route('admin.documentation-style.update') }}"
            class="grid gap-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:grid-cols-2">
            @csrf
            @method('PUT')

            <div>
                <label for="documentation_font_family" class="text-sm font-medium text-slate-700">Police</label>
                <select id="documentation_font_family" name="documentation_font_family"
                    class="mt-2 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
                    <option value="sans" @selected(old('documentation_font_family', $style['documentation_font_family']) === 'sans')>Sans-serif</option>
                    <option value="serif" @selected(old('documentation_font_family', $style['documentation_font_family']) === 'serif')>Serif</option>
                    <option value="mono" @selected(old('documentation_font_family', $style['documentation_font_family']) === 'mono')>Monospace</option>
                </select>
                @error('documentation_font_family')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="documentation_font_size" class="text-sm font-medium text-slate-700">Taille du texte (px)</label>
                <input id="documentation_font_size" name="documentation_font_size" type="number" min="12" max="24"
                    value="{{ old('documentation_font_size', $style['documentation_font_size']) }}"
                    class="mt-2 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
                @error('documentation_font_size')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="documentation_text_color" class="text-sm font-medium text-slate-700">Couleur du texte</label>
                <input id="documentation_text_color" name="documentation_text_color" type="color"
                    value="{{ old('documentation_text_color', $style['documentation_text_color']) }}"
                    class="mt-2 h-10 w-full rounded-lg border-slate-300 p-1">
                @error('documentation_text_color')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="documentation_heading_color" class="text-sm font-medium text-slate-700">Couleur des titres</label>
                <input id="documentation_heading_color" name="documentation_heading_color" type="color"
                    value="{{ old('documentation_heading_color', $style['documentation_heading_color']) }}"
                    class="mt-2 h-10 w-full rounded-lg border-slate-300 p-1">
                @error('documentation_heading_color')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="documentation_background_color" class="text-sm font-medium text-slate-700">Couleur de fond</label>
                <input id="documentation_background_color" name="documentation_background_color" type="color"
                    value="{{ old('documentation_background_color', $style['documentation_background_color']) }}"
                    class="mt-2 h-10 w-full rounded-lg border-slate-300 p-1">
                @error('documentation_background_color')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="documentation_line_height" class="text-sm font-medium text-slate-700">Interligne</label>
                <input id="documentation_line_height" name="documentation_line_height" type="number" step="0.1" min="1" max="2"
                    value="{{ old('documentation_line_height', $style['documentation_line_height']) }}"
                    class="mt-2 w-full rounded-lg border-slate-300 text-sm focus:border-blue-600 focus:ring-blue-600">
                @error('documentation_line_height')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <button type="submit"
                    class="inline-flex items-center justify-center rounded-lg bg-[#003366] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#0e5fa8]">
                    Enregistrer le style
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
