<form action="{{ route('documents.search') }}" method="GET" role="search" class="flex w-full items-center gap-2">
    <label for="document-search-query" class="sr-only">Rechercher dans les documents</label>
    <div class="relative min-w-0 flex-1">
        <x-lucide-search
            class="pointer-events-none absolute left-3 top-1/2 size-5 -translate-y-1/2 text-slate-400"
            aria-hidden="true"
        />
        <input
            id="document-search-query"
            type="search"
            name="q"
            value="{{ is_string(request()->query('q')) ? request()->query('q') : '' }}"
            maxlength="100"
            placeholder="Rechercher un document..."
            autocomplete="off"
            class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-[#0e5fa8] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0e5fa8]/15"
        >
    </div>
    <button
        type="submit"
        aria-label="Rechercher"
        title="Rechercher"
        class="inline-flex size-10 shrink-0 items-center justify-center rounded-xl bg-[#003366] p-0 text-sm font-semibold text-white transition hover:bg-[#0e5fa8] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0e5fa8] focus-visible:ring-offset-2"
    >
        <x-lucide-arrow-right class="size-4" aria-hidden="true" />
    </button>
</form>