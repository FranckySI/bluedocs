@php
    $linkClasses = 'group flex items-center gap-3 rounded-xl border border-transparent px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-white/70 hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900/20';
    $activeLinkClasses = '!border-slate-200/80 !bg-white !text-slate-900 shadow-sm';
    $iconClasses = 'size-5 shrink-0 stroke-[1.6] text-slate-500 transition group-hover:text-slate-900';
@endphp

<aside
    id="app-sidebar"
    class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col rounded-r-3xl border-r border-slate-200/70 bg-[#f7f7f7] text-slate-900 shadow-xl transition-transform duration-300 ease-in-out lg:translate-x-0 lg:shadow-none"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    aria-label="Navigation principale"
>
    <div class="flex h-20 items-center justify-between px-6">
        <a href="{{ route('accueil') }}" class="flex items-center gap-3">
            <img src="{{ asset('images/logo_.png') }}" alt="BlueDocs" class="h-auto w-40">
        </a>
        <button
            type="button"
            @click="sidebarOpen = false"
            aria-label="Fermer le menu"
            class="inline-flex size-9 items-center justify-center rounded-xl text-slate-500 transition hover:bg-white hover:text-slate-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-900/20 lg:hidden"
        >
            <x-lucide-x class="size-5" aria-hidden="true" />
        </button>
    </div>

    <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-2">
        <a
            href="{{ route('docs.sommaire') }}"
            @click="sidebarOpen = false"
            @class([$linkClasses, $activeLinkClasses => request()->routeIs('docs.sommaire')])
        >
            <x-lucide-book-open class="{{ $iconClasses }}" aria-hidden="true" />
            <span>{{ __('Sommaire') }}</span>
        </a>

        @auth
            <a
                href="{{ route('dashboard') }}"
                @click="sidebarOpen = false"
                @class([$linkClasses, $activeLinkClasses => request()->routeIs('dashboard')])
            >
                <x-lucide-layout-dashboard class="{{ $iconClasses }}" aria-hidden="true" />
                <span>{{ __('Tableau de bord') }}</span>
            </a>
        @endauth

        <div class="mx-3 !my-5 border-t border-slate-200"></div>

        <p class="px-3 pb-1 text-xs font-medium text-slate-400">{{ __('Compte') }}</p>

        @guest
            <a
                href="{{ route('login') }}"
                @click="sidebarOpen = false"
                @class([$linkClasses, $activeLinkClasses => request()->routeIs('login')])
            >
                <x-lucide-log-in class="{{ $iconClasses }}" aria-hidden="true" />
                <span>{{ __('Se connecter') }}</span>
            </a>
        @endguest

        @auth
            <a
                href="{{ route('profile.edit') }}"
                @click="sidebarOpen = false"
                @class([$linkClasses, $activeLinkClasses => request()->routeIs('profile.*')])
            >
                <x-lucide-user class="{{ $iconClasses }}" aria-hidden="true" />
                <span>{{ __('Mon profil') }}</span>
            </a>

            @can('create-users')
                <a
                    href="{{ route('register') }}"
                    @click="sidebarOpen = false"
                    @class([$linkClasses, $activeLinkClasses => request()->routeIs('register')])
                >
                    <x-lucide-user-plus class="{{ $iconClasses }}" aria-hidden="true" />
                    <span>{{ __('Ajouter un utilisateur') }}</span>
                </a>
            @endcan
        @endauth
    </nav>

    <div class="space-y-2 p-4">
        <div class="truncate rounded-xl border border-slate-200/80 bg-white px-3 py-2.5 shadow-sm">
            <p class="truncate text-sm font-medium text-slate-900">
                @auth
                    {{ Auth::user()->name }}
                @else
                    {{ __('Visiteur') }}
                @endauth
            </p>
            @auth
                <p class="truncate text-xs text-slate-500">{{ Auth::user()->email }}</p>
            @endauth
        </div>

        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="{{ $linkClasses }} w-full hover:!text-red-600"
                    @click="sidebarOpen = false"
                >
                    <x-lucide-log-out class="{{ $iconClasses }}" aria-hidden="true" />
                    <span>{{ __('Se déconnecter') }}</span>
                </button>
            </form>
        @endauth
    </div>
</aside>