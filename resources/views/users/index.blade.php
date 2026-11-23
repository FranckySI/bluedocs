<x-app-layout>
    <div class="mx-auto max-w-6xl space-y-6 px-4 py-10 sm:px-6 lg:px-8">
        <header class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-800">Administration</p>
                <h1 class="mt-1 text-2xl font-semibold text-slate-900">Gestion des éditeurs</h1>
                <p class="mt-2 text-sm text-slate-600">
                    Désactivez les comptes inactifs pour préserver l’historique des documents.
                </p>
            </div>
            <a href="{{ route('register') }}"
                class="inline-flex items-center justify-center rounded-lg bg-[#003366] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#0e5fa8]">
                Ajouter un éditeur
            </a>
        </header>

        @if (session('status') === 'user-status-updated')
            <p role="status" class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800">
                Le statut du compte a été mis à jour.
            </p>
        @endif

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[42rem] divide-y divide-slate-200 text-left">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <tr>
                            <th scope="col" class="px-5 py-3">Éditeur</th>
                            <th scope="col" class="px-5 py-3">E-mail</th>
                            <th scope="col" class="px-5 py-3">Statut</th>
                            <th scope="col" class="px-5 py-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($editors as $editor)
                            <tr>
                                <td class="px-5 py-4 text-sm font-medium text-slate-900">{{ $editor->name }}</td>
                                <td class="px-5 py-4 text-sm text-slate-600">{{ $editor->email }}</td>
                                <td class="px-5 py-4">
                                    <span @class([
                                        'inline-flex rounded-full px-2.5 py-1 text-xs font-semibold',
                                        'bg-green-50 text-green-700' => $editor->is_active,
                                        'bg-slate-100 text-slate-600' => ! $editor->is_active,
                                    ])>
                                        {{ $editor->is_active ? 'Actif' : 'Désactivé' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <form method="POST" action="{{ route('users.update-status', $editor) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_active" value="{{ $editor->is_active ? '0' : '1' }}">
                                        <button type="submit"
                                            @class([
                                                'rounded-lg px-3 py-2 text-sm font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2',
                                                'bg-slate-100 text-slate-700 hover:bg-slate-200 focus-visible:ring-slate-500' => $editor->is_active,
                                                'bg-green-700 text-white hover:bg-green-800 focus-visible:ring-green-700' => ! $editor->is_active,
                                            ])>
                                            {{ $editor->is_active ? 'Désactiver' : 'Activer' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-10 text-center text-sm text-slate-500">
                                    Aucun compte éditeur n’est enregistré.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>{{ $editors->links() }}</div>
    </div>
</x-app-layout>
