<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @php
            $fontFamilies = [
                'sans' => "'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif",
                'serif' => "Georgia, 'Times New Roman', serif",
                'mono' => "'JetBrains Mono', ui-monospace, monospace",
            ];
            $documentationFontFamily = $fontFamilies[$documentationStyle['documentation_font_family']] ?? $fontFamilies['sans'];
        @endphp
        <style>
            :root {
                --bd-document-font-family: {{ $documentationFontFamily }};
                --bd-document-font-size: {{ $documentationStyle['documentation_font_size'] }}px;
                --bd-document-text-color: {{ $documentationStyle['documentation_text_color'] }};
                --bd-document-heading-color: {{ $documentationStyle['documentation_heading_color'] }};
                --bd-document-background-color: {{ $documentationStyle['documentation_background_color'] }};
                --bd-document-line-height: {{ $documentationStyle['documentation_line_height'] }};
            }

            #document-content {
                font-family: var(--bd-document-font-family);
                font-size: var(--bd-document-font-size);
                line-height: var(--bd-document-line-height);
                color: var(--bd-document-text-color);
                background: var(--bd-document-background-color);
            }

            #document-content h1,
            #document-content h2,
            #document-content h3,
            #document-content h4,
            #document-content h5,
            #document-content h6 {
                color: var(--bd-document-heading-color);
            }
        </style>
    </head>

    
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
            @include('layouts.sidebar')

            <div
                x-cloak
                x-show="sidebarOpen"
                @click="sidebarOpen = false"
                class="fixed inset-0 z-40 bg-gray-950/60 lg:hidden"
                aria-hidden="true"
            ></div>

            <div class="lg:pl-72">
                <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-gray-200 bg-white px-4 shadow-sm lg:hidden">
                    <button
                        type="button"
                        @click="sidebarOpen = true"
                        aria-controls="app-sidebar"
                        :aria-expanded="sidebarOpen.toString()"
                        aria-label="Ouvrir le menu"
                        class="inline-flex size-10 items-center justify-center rounded-md text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-600"
                    >
                        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <a href="{{ route('accueil') }}" class="flex items-center gap-3">
                        <img src="{{ asset('images/logo_.png') }}" alt="BlueDocs" class="h-auto w-32">
                    </a>
                </header>

                @isset($header)
                    <header class="bg-white shadow">
                        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main>
                    {{ $slot }}
                </main>
            </div>
        </div>

    @isset($scripts)
        {{ $scripts }}
    @endisset
    </body>
</html>
