<x-app-layout>
    <div class="min-h-screen bg-slate-50 px-4 py-8 sm:px-6 lg:px-10">
        <div class="mx-auto max-w-5xl">
            <header class="mb-8">
                <span class="mb-3 inline-flex items-center rounded-md bg-blue-50 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-[#003366]">
                    BlueDocs
                </span>
                <h1 class="text-2xl font-semibold tracking-tight text-slate-900 sm:text-3xl">
                    Résultats de recherche
                </h1>
                @if ($query !== '')
                    <p class="mt-2 text-sm text-slate-600">
                        Résultats pour <span class="font-semibold text-slate-900">{{ $query }}</span>
                        @if ($documents instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            <span class="text-slate-500">({{ $documents->total() }} document{{ $documents->total() > 1 ? 's' : '' }})</span>
                        @endif
                    </p>
                @else
                    <p class="mt-2 text-sm text-slate-600">Saisissez un ou plusieurs mots-clés pour parcourir vos documents.</p>
                @endif
            </header>

            @if ($query === '')
                <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm sm:p-12">
                    <x-lucide-search class="mx-auto size-8 text-slate-400" aria-hidden="true" />
                    <p class="mt-4 text-sm text-slate-600">Votre recherche apparaîtra ici.</p>
                </div>
            @else
                <div class="space-y-4">
                    @forelse ($documents as $document)
                        <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-slate-300 hover:shadow-md sm:p-6">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0 flex-1">
                                    <h2 class="text-lg font-semibold text-[#003366]">
                                        <a
                                            href="{{ route('documents.show', ['document' => $document->slug, 'q' => $query]) }}#document-content"
                                            class="rounded-sm hover:text-[#0e5fa8] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#0e5fa8]"
                                        >
                                            {{ $document->title }}
                                        </a>
                                    </h2>
                                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $document->search_snippet }}</p>
                                    <p class="mt-3 text-xs text-slate-400">
                                        Mis à jour le {{ $document->updated_at->format('d/m/Y') }}
                                    </p>
                                </div>
                                <x-lucide-arrow-up-right class="size-5 shrink-0 text-slate-400" aria-hidden="true" />
                            </div>
                        </article>
                    @empty
                        <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm sm:p-12">
                            <x-lucide-file-search class="mx-auto size-8 text-slate-400" aria-hidden="true" />
                            <h2 class="mt-4 text-base font-semibold text-slate-900">Aucun document trouvé</h2>
                            <p class="mt-2 text-sm text-slate-600">Essayez avec d’autres mots-clés.</p>
                        </div>
                    @endforelse
                </div>

                @if ($documents instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="mt-6">
                        {{ $documents->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
